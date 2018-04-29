<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Search\Engine\Generic;

use Cake\Cache\Cache;
use Cake\Core\InstanceConfigTrait;
use Cake\Datasource\EntityInterface;
use Cake\Error\FatalErrorException;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Search\Engine\BaseEngine;
use Search\Engine\Generic\Exception\CompoundPrimaryKeyException;
use Search\Parser\MiniLanguage\MiniLanguageParser;
use Search\Parser\TokenInterface;
use \ArrayObject;

/**
 * This Search Engine allows entities to be searchable through an auto-generated
 * list of words.
 *
 * ## Using Generic Engine
 *
 * You must indicate Searchable behavior to use this engine, for example when
 * attaching Searchable behavior to `Articles` table:
 *
 * ```php
 * $this->addBehavior('Search.Searchable', [
 *     'engine' => [
 *         'className' => 'Search\Engine\Generic\GenericEngine',
 *         'config' => [
 *             'bannedWords' => []
 *         ]
 *     ]
 * ]);
 * ```
 *
 * This engine will apply a series of filters (converts to lowercase, remove line
 * breaks, etc) to words list extracted from each entity being indexed.
 *
 * ### Banned Words
 *
 * You can use the `bannedWords` option to tell which words should not be indexed by
 * this engine. For example:
 *
 * ```php
 * $this->addBehavior('Search.Searchable', [
 *     'engine' => [
 *         'className' => 'Search\Engine\Generic\GenericEngine',
 *         'config' => [
 *             'bannedWords' => ['of', 'the', 'and']
 *         ]
 *     ]
 * ]);
 * ```
 *
 * If you need to ban a really specific list of words you can set `bannedWords`
 * option as a callable method that should return true or false to tell if a words
 * should be indexed or not. For example:
 *
 * ```php
 * $this->addBehavior('Search.Searchable', [
 *     'engine' => [
 *         'className' => 'Search\Engine\Generic\GenericEngine',
 *         'config' => [
 *             'bannedWords' => function ($word) {
 *                 return strlen($word) > 3;
 *             }
 *         ]
 *     ]
 * ]);
 * ```
 *
 * - Returning TRUE indicates that the word is safe for indexing (not banned).
 * - Returning FALSE indicates that the word should NOT be indexed (banned).
 *
 * In the example, above any word of 4 or more characters will be indexed
 * (e.g. "home", "name", "quickapps", etc). Any word of 3 or less characters will
 * be banned (e.g. "and", "or", "the").
 *
 * ## Searching Entities
 *
 * When using this engine, every entity under your table gets a list of indexed
 * words. The idea behind this is that you can use this list of words to locate any
 * entity based on a customized search-criteria. A search-criteria looks as follow:
 *
 *     "this phrase" OR -"not this one" AND this
 *
 * ---
 *
 * Use wildcard searches to broaden results; asterisk (`*`) matches any one or
 * more characters, exclamation mark (`!`) matches any single character:
 *
 *     "this *rase" OR -"not th!! one" AND thi!
 *
 * Anything containing space (" ") characters must be wrapper between quotation
 * marks:
 *
 *     "this phrase" special_operator:"[100 to 500]" -word -"more words" -word_1 word_2
 *
 * The search criteria above will be treated as it were composed by the
 * following parts:
 *
 * - `this phrase`
 * - `special_operator:[100 to 500]`
 * - `-word`
 * - `-more words`
 * - `-word_1`
 * - `word_2`
 *
 * ---
 *
 * Search criteria allows you to perform complex search conditions in a
 * human-readable way. Allows you, for example, create user-friendly search-forms,
 * or create some RSS feed just by creating a friendly URL using a search-criteria.
 * e.g.: `http://example.com/rss/category:art date:>2014-01-01`
 *
 * You must use the `search()` method to scope any query using a search-criteria.
 * For example, in one controller using `Users` model:
 *
 * ```php
 * $criteria = '"this phrase" OR -"not this one" AND this';
 * $query = $this->Users->find();
 * $query = $this->Users->search($criteria, $query);
 * ```
 *
 * The above will alter the given $query object according to the given criteria.
 * The second argument (query object) is optional, if not provided this Behavior
 * automatically generates a find-query for you. Previous example and the one
 * below are equivalent:
 *
 * ```php
 * $criteria = '"this phrase" OR -"not this one" AND this';
 * $query = $this->Users->search($criteria);
 * ```
 */
class GenericEngine extends BaseEngine
{

    /**
     * {@inheritDoc}
     *
     * - operators: A list of registered operators methods as `name` =>
     *   `methodName`.
     *
     * - strict: Used to filter any invalid word. Set to a string representing a
     *   regular expression describing which charaters should be removed. Or set
     *   to TRUE to used default discard criteria: only letters, digits and few
     *   basic symbols (".", ",", "/", etc). Defaults to TRUE (custom filter
     *   regex). VALID ONLY when `wordsExtractor` is set to null.
     *
     * - bannedWords: Array list of banned words, or a callable that should decide
     *   if the given word is banned or not. Defaults to empty array (allow
     *   everything). VALID ONLY when `wordsExtractor` is set to null.
     *
     * - fulltext: Whether to use FULLTEXT search whenever it is possible. Defaults to
     *   TRUE. This feature is only supported for MySQL InnoDB database engines.
     *
     * - datasetTable: Name of the MySQL table where words dataset should be stored and
     *   read from. This allows you to split large sets into different tables.
     *
     * - wordsExtractor: Callable function used to extract words from each entity being
     *   indexed. Such functions will received an Entity object as first argument, and
     *   should return a string of words. e.g. `lorem ipsum dolorem`. Defaults to internal
     *   method `extractEntityWords()`
     */
    protected $_defaultConfig = [
        'operators' => [],
        'strict' => true,
        'bannedWords' => [],
        'wordsExtractor' => null,
        'fulltext' => true,
        'datasetTable' => 'search_datasets',
    ];

    /**
     * {@inheritDoc}
     *
     * @throws \Search\Engine\Generic\Exception\CompoundPrimaryKeyException When using
     *   compound primary keys
     */
    public function __construct(Table $table, array $config = [])
    {
        $config['tableAlias'] = (string)Inflector::underscore($table->table());
        $config['pk'] = $table->primaryKey();
        $this->_defaultConfig['wordsExtractor'] = function (EntityInterface $entity) {
            return $this->extractEntityWords($entity);
        };

        if (is_array($config['pk'])) {
            throw new CompoundPrimaryKeyException($config['tableAlias']);
        }

        parent::__construct($table, $config);

        $assocOptions = [
            'foreignKey' => 'entity_id',
            'joinType' => 'INNER',
            'conditions' => [
                'SearchDatasets.table_alias' => $config['tableAlias'],
            ],
            'dependent' => true
        ];

        if ($this->config('datasetTable') != $this->_defaultConfig['datasetTable']) {
            $datasetTableObject = clone TableRegistry::get('Search.SearchDatasets');
            $datasetTableObject->table($this->config('datasetTable'));
            $assocOptions['targetTable'] = $datasetTableObject;
        }

        $this->_table->hasOne('Search.SearchDatasets', $assocOptions);
    }

    /**
     * {@inheritDoc}
     */
    public function index(EntityInterface $entity)
    {
        $set = $this->_table->SearchDatasets->find()
            ->where([
                'entity_id' => $this->_entityId($entity),
                'table_alias' => $this->config('tableAlias'),
            ])
            ->limit(1)
            ->first();

        if (!$set) {
            $set = $this->_table->SearchDatasets->newEntity([
                'entity_id' => $this->_entityId($entity),
                'table_alias' => $this->config('tableAlias'),
                'words' => '',
            ]);
        }

        // We add starting and trailing space to allow LIKE %something-to-match%
        $extractor = $this->config('wordsExtractor');
        $set = $this->_table->SearchDatasets->patchEntity($set, [
            'words' => ' ' . $extractor($entity) . ' '
        ]);

        return (bool)$this->_table->SearchDatasets->save($set);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(EntityInterface $entity)
    {
        $this->_table->SearchDatasets->deleteAll([
            'entity_id' => $this->_entityId($entity),
            'table_alias' => $this->config('tableAlias'),
        ]);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function get(EntityInterface $entity)
    {
        return $this->_table->SearchDatasets->find()
            ->where([
                'entity_id' => $this->_entityId($entity),
                'table_alias' => $this->config('tableAlias'),
            ])
            ->limit(1)
            ->first();
    }

    /**
     * {@inheritDoc}
     *
     * It looks for search-criteria and applies them over the query object. For
     * example, given the criteria below:
     *
     *     "this phrase" -"and not this one"
     *
     * Alters the query object as follow:
     *
     * ```php
     * $query->where([
     *    'indexed_words LIKE' => '%this phrase%',
     *    'indexed_words NOT LIKE' => '%and not this one%'
     * ]);
     * ```
     *
     * The `AND` & `OR` keywords are allowed to create complex conditions. For
     * example:
     *
     *     "this phrase" OR -"and not this one" AND "this"
     *
     * Will produce something like:
     *
     * ```php
     * $query
     *     ->where(['indexed_words LIKE' => '%this phrase%'])
     *     ->orWhere(['indexed_words NOT LIKE' => '%and not this one%']);
     *     ->andWhere(['indexed_words LIKE' => '%this%']);
     * ```
     *
     * ### Options
     *
     * - `missingOperators`: Controls what to do when an undefined operator is found.
     *    Possible values are:
     *
     *    - `event` (default): Triggers an event so other parts of the system can react
     *      to any missing operator.
     *
     *    - `ignore`: Ignore any undefined operator.
     *
     *    - `words`: Converts operator information into a set of literal words.
     *
     * - `tokenDecorator`: Callable function which is applied to every token before it
     *   gets applied. Retuning anything that is not a `TokenInterface` will skip that
     *   token from being used.
     */
    public function search($criteria, Query $query, array $options = [])
    {
        $tokens = $this->tokenizer($criteria);
        $options += [
            'missingOperators' => 'event',
            'tokenDecorator' => function ($t) {
                return $t;
            },
        ];

        if (!empty($tokens)) {
            $query->innerJoinWith('SearchDatasets');
            $decorator = $options['tokenDecorator'];
            $operators = $this->_table->behaviors()
                ->get('Searchable')
                ->config('operators');

            foreach ($tokens as $token) {
                $token = $decorator($token);
                $method = '_scopeWords';

                if (!($token instanceof TokenInterface)) {
                    continue;
                }

                if ($token->isOperator()) {
                    $method = '_scopeOperator';
                    $operatorName = mb_strtolower($token->operatorName());

                    if (!isset($operators[$operatorName])) {
                        switch ($options['missingOperators']) {
                            case 'ignore':
                                $method = null;
                                break;

                            case 'words':
                                $method = '_scopeWords';
                                break;

                            case 'event':
                            default:
                                // `event` is how missing operator are handled by default by
                                // Searchable Behavior, so no specific action is required.
                                break;
                        }
                    }
                }

                if ($method) {
                    $query = $this->$method($query, $token);
                }
            }
        }

        return $query;
    }

    /**
     * Extracts every token found on the given search criteria.
     *
     * @param string $criteria A search criteria. e.g. `-hello +world`
     * @return array List of tokens found
     */
    public function tokenizer($criteria)
    {
        return (array)(new MiniLanguageParser($criteria))->parse();
    }

    /**
     * Scopes the given query using the given operator token.
     *
     * @param \Cake\ORM\Query $query The query to scope
     * @param \Search\Token $token Token describing an operator. e.g `-op_name:op_value`
     * @return \Cake\ORM\Query Scoped query
     */
    protected function _scopeOperator(Query $query, TokenInterface $token)
    {
        return $this->_table->applySearchOperator($query, $token);
    }

    /**
     * Scopes the given query using the given words token.
     *
     * @param \Cake\ORM\Query $query The query to scope
     * @param \Search\TokenInterface $token Token describing a words sequence. e.g `this is a phrase`
     * @return \Cake\ORM\Query Scoped query
     */
    protected function _scopeWords(Query $query, TokenInterface $token)
    {
        if ($this->_isFullTextEnabled()) {
            return $this->_scopeWordsInFulltext($query, $token);
        }

        $like = 'LIKE';
        if ($token->negated()) {
            $like = 'NOT LIKE';
        }

        // * Matches any one or more characters.
        // ! Matches any single character.
        $value = str_replace(['*', '!'], ['%', '_'], $token->value());

        if ($token->where() === 'or') {
            $query->orWhere(["SearchDatasets.words {$like}" => "%{$value}%"]);
        } elseif ($token->where() === 'and') {
            $query->andWhere(["SearchDatasets.words {$like}" => "%{$value}%"]);
        } else {
            $query->where(["SearchDatasets.words {$like}" => "%{$value}%"]);
        }

        return $query;
    }

    /**
     * Similar to "_scopeWords" but using MySQL's fulltext indexes.
     *
     * @param \Cake\ORM\Query $query The query to scope
     * @param \Search\TokenInterface $token Token describing a words sequence. e.g `this is a phrase`
     * @return \Cake\ORM\Query Scoped query
     */
    protected function _scopeWordsInFulltext(Query $query, TokenInterface $token)
    {
        $value = str_replace(['*', '!'], ['*', '*'], $token->value());
        $value = mb_strpos($value, '+') === 0 ? mb_substr($value, 1) : $value;

        if (empty($value) || in_array($value, $this->_stopWords())) {
            return $query;
        }

        $not = $token->negated() ? 'NOT' : '';
        $value = str_replace(["'", '@'], ['"', ' '], $value);
        $conditions = ["{$not} MATCH(SearchDatasets.words) AGAINST('{$value}' IN BOOLEAN MODE) > 0"];

        if ($token->where() === 'or') {
            $query->orWhere($conditions);
        } elseif ($token->where() === 'and') {
            $query->andWhere($conditions);
        } else {
            $query->where($conditions);
        }

        return $query;
    }

    /**
     * Whether FullText index is available or not and should be used.
     *
     * @return bool True if enabled and should be used, false otherwise
     */
    protected function _isFullTextEnabled()
    {
        if (!$this->config('fulltext')) {
            return false;
        }

        static $enabled = null;
        if ($enabled !== null) {
            return $enabled;
        }

        list(, $driverClass) = namespaceSplit(strtolower(get_class($this->_table->connection()->driver())));
        if ($driverClass != 'mysql') {
            $enabled = false;

            return false;
        }

        $schema = $this->_table->SearchDatasets->schema();
        foreach ($schema->indexes() as $index) {
            $info = $schema->index($index);
            if (in_array('words', $info['columns']) &&
                strtolower($info['type']) == 'fulltext'
            ) {
                $enabled = true;

                return true;
            }
        }

        $enabled = false;

        return false;
    }

    /**
     * Gets a list of storage engine's stopwords. That is words that is considered
     * common or Trivial enough that it is omitted from the search index and ignored
     * in search queries
     *
     * @return array List of words
     */
    protected function _stopWords()
    {
        $conn = $this->_table->find()->connection();
        $cacheKey = $conn->configName() . '_generic_engine_stopwords_list';
        if ($cache = Cache::read($cacheKey, '_cake_model_')) {
            return (array)$cache;
        }

        $words = [];
        $sql = $conn
            ->execute('SELECT * FROM INFORMATION_SCHEMA.INNODB_FT_DEFAULT_STOPWORD')
            ->fetchAll('assoc');

        foreach ((array)$sql as $row) {
            if (!empty($row['value'])) {
                $words[] = $row['value'];
            }
        }

        Cache::write($cacheKey, $words, '_cake_model_');

        return $words;
    }

    /**
     * Calculates entity's primary key.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity
     * @return string
     * @deprecated Use direct access as `$entity->get($this->config('pk'))`
     */
    protected function _entityId(EntityInterface $entity)
    {
        return $entity->get($this->config('pk'));
    }

    /**
     * Extracts a list of words to by indexed for given entity.
     *
     * NOTE: Words can be repeated, this allows to search phrases.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity for which generate
     *  the list of words
     * @return string Space-separated list of words. e.g. `cat dog this that`
     */
    public function extractEntityWords(EntityInterface $entity)
    {
        $text = '';
        $entityArray = $entity->toArray();
        $entityArray = Hash::flatten($entityArray);
        foreach ($entityArray as $key => $value) {
            if (is_string($value) || is_numeric($value)) {
                $text .= " {$value}";
            }
        }

        $text = str_replace(["\n", "\r"], '', trim((string)$text)); // remove new lines
        $text = strip_tags($text); // remove HTML tags, but keep their content
        $strict = $this->config('strict');

        if (!empty($strict)) {
            // only: space, digits (0-9), letters (any language), ".", ",", "-", "_", "/", "\"
            $pattern = is_string($strict) ? $strict : '[^\p{L}\p{N}\s\@\.\,\-\_\/\\0-9]';
            $text = preg_replace('/' . $pattern . '/ui', ' ', $text);
        }

        $text = trim(preg_replace('/\s{2,}/i', ' ', $text)); // remove double spaces
        $text = mb_strtolower($text); // all to lowercase
        $text = $this->_filterText($text); // filter
        $text = iconv('UTF-8', 'UTF-8//IGNORE', mb_convert_encoding($text, 'UTF-8')); // remove any invalid character

        return trim($text);
    }

    /**
     * Removes any invalid word from the given text.
     *
     * @param string $text The text to filter
     * @return string Filtered text
     */
    protected function _filterText($text)
    {
        // return true means `yes, it's banned`
        if (is_callable($this->config('bannedWords'))) {
            $isBanned = function ($word) {
                $callable = $this->config('bannedWords');

                return $callable($word);
            };
        } else {
            $isBanned = function ($word) {
                return in_array($word, (array)$this->config('bannedWords')) || empty($word);
            };
        }

        $words = explode(' ', $text);
        foreach ($words as $i => $w) {
            if ($isBanned($w)) {
                unset($words[$i]);
            }
        }

        return implode(' ', $words);
    }
}
