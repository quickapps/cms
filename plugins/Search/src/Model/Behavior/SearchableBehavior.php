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
namespace Search\Model\Behavior;

use Cake\Error\FatalErrorException;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use QuickApps\Event\HookAwareTrait;
use Search\Model\Entity\SearchDataset;

/**
 * This behavior allows entities to be searchable through an auto-generated
 * list of words.
 *
 * ## Using this Behavior
 *
 * You must indicate which fields can be indexed when attaching this behavior
 * to your tables. For example, when attaching this behavior to `Users` table:
 *
 *     $this->addBehavior('Search.Searchable', [
 *         'fields' => ['username', 'email']
 *     ]);
 *
 * In the example above, this behavior will look for words to index in user's
 * "username" and user's "email" properties.
 *
 * If you need a really special selection of words for each entity is being indexed,
 * then you can set the `fields` option as a callable which should return a list of
 * words for the given entity. For example:
 *
 *     $this->addBehavior('Search.Searchable', [
 *         'fields' => function ($user) {
 *             return "{$user->name} {$user->email}";
 *         }
 *     ]);
 *
 * You can return either, a plain text of space-separated words, or an array list
 * of words:
 *
 *     $this->addBehavior('Search.Searchable', [
 *         'fields' => function ($user) {
 *             return [
 *                 'word 1',
 *                 'word 2',
 *                 'word 3',
 *             ];
 *         }
 *     ]);
 *
 * This behaviors will apply a series of filters (converts to lowercase, remove
 * line breaks, etc) to the resulting word list, so you should simply return a RAW
 * string of words and let this behavior do the rest of the job.
 *
 * ### Banned Words
 *
 * You can use the `bannedWords` option to tell which words should not be indexed
 * by this behavior. For example:
 *
 *     $this->addBehavior('Search.Searchable', [
 *         'bannedWords' => ['of', 'the', 'and']
 *     ]);
 *
 * If you need to ban a really specific list of words you can set `bannedWords` option
 * as a callable method that should return true or false to tell if a words should be
 * indexed or not. For example:
 *
 *     $this->addBehavior('Search.Searchable', [
 *         'bannedWords' => function ($word) {
 *             return strlen($word) > 3;
 *         }
 *     ]);
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
 * When attaching this behavior, every entity under your table gets a list of
 * indexed words. The idea is you can use this list of words to locate any entity
 * based on a customized search-criteria. A search-criteria looks as follow:
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
 *     [
 *         this phrase,
 *         special_operator:[100 to 500],
 *         -word,
 *         -more words,
 *         -word_1,
 *         word_2,
 *     ]
 *
 * ---
 *
 * Search criteria allows you to perform complex search conditions in a human-readable
 * way. Allows you, for example, create user-friendly search-forms, or create some
 * RSS feed just by creating a friendly URL using a search-criteria.
 * e.g.: `http://example.com/rss/category:art date:>2014-01-01`
 *
 * You must use the `search()` method to scope any query using a search-criteria.
 * For example, in one controller using `Users` model:
 *
 *     $criteria = '"this phrase" OR -"not this one" AND this';
 *     $query = $this->Users->find();
 *     $query = $this->Users->search($criteria, $query);
 *
 * The above will alter the given $query object according to the given criteria.
 * The second argument (query object) is optional, if not provided this Behavior
 * automatically generates a find-query for you. Previous example and the one
 * below are equivalent:
 *
 *     $criteria = '"this phrase" OR -"not this one" AND this';
 *     $query = $this->Users->search($criteria);
 *
 * ### Creating Operators
 *
 * An `Operator` is a search-criteria command which allows you to perform very
 * specific filter conditions over your queries. An operator **has two parts**,
 * a `name` and its `arguments`, both parts must be separated using the `:`
 * symbol e.g.:
 *
 *     // operator name is: "author"
 *     // operator arguments are: ">2014-03-01"
 *     date:>2014-03-01
 *
 * NOTE: Operators names are treated as **lowercase_and_underscored**, so
 * `AuthorName`, `AUTHOR_NAME` or `AuThoR_naMe` are all treated as: `author_name`.
 *
 * You can define custom operators for your table by using the
 * `addSearchOperator()` method. For example, you might need create a custom
 * operator `author` which allows you to search a `Node` entity by `author name`.
 * A search-criteria using this operator may looks as follow:
 *
 *     // get all nodes containing `this phrase` and created by `JohnLocke`
 *     "this phrase" author:JohnLocke
 *
 * You must define in your table an operator method and register it into this
 * behavior under the `author` name, a full working example may look as follow:
 *
 *     class Nodes extends Table {
 *         public function initialize(array $config) {
 *             // attach the behavior
 *             $this->addBehavior('Search.Searchable');
 *             // register a new operator for handling `author:<author_name>` expressions
 *             $this->addSearchOperator('author', 'operatorAuthor');
 *         }
 *
 *         public function operatorAuthor($query, $value, $negate, $orAnd) {
 *             // $query:
 *             //     The query object to alter
 *             // $value:
 *             //     The value after `author:`. e.g.: `JohnLocke`
 *             // $negate:
 *             //     TRUE if user has negated this command. e.g.: `-author:JohnLocke`.
 *             //     FALSE otherwise.
 *             // $orAnd:
 *             //     or|and|false Indicates the type of condition. e.g.: `OR author:JohnLocke`
 *             //     will set $orAnd to `or`. But, `AND author:JohnLocke` will set $orAnd to `and`.
 *             //     By default is set to FALSE. This allows you to use
 *             //     Query::andWhere() and Query::orWhere() methods.
 *         }
 *     }
 *
 * ### Fallback Operators
 *
 * When an operator is detected in the given search criteria but no operator
 * callable was defined using `addSearchOperator()`, then
 * `SearchableBehavior.operator<OperatorName>` will be fired, so other plugins
 * may respond to any undefined operator. For example, given the search criteria
 * below, lets suppose `date` operator **was not defined** early:
 *
 *     "this phrase" author:JohnLocke date:[2013-06-06..2014-06-06]
 *
 * The `SearchableBehavior.operatorDate` event will be fired. A plugin may
 * respond to this call by implementing this event:
 *
 *     // ...
 *     public function implementedEvents() {
 *         return [
 *             'SearchableBehavior.operatorDate' => 'operatorDate',
 *         ];
 *     }
 *     // ...
 *     public function operatorDate($event, $query, $value, $negate, $orAnd) {
 *         // alter $query object and return it
 *         return $query;
 *     }
 *
 * IMPORTANT:
 *
 * - Event handler method should always return the modified $query object.
 * - The event's context, that is `$event->subject()`, is the table instance that
 *   fired the event.
 */
class SearchableBehavior extends Behavior
{

    use HookAwareTrait;

    /**
     * The table this behavior is attached to.
     *
     * @var Table
     */
    protected $_table;

    /**
     * Behavior configuration array.
     *
     * - operators: A list of registered operators methods as `name` => `methodName`
     * - fields: List of entity fields where to look for words. Or a callable method,
     *   it receives and entity as first argument, and it must return a list of words
     *   for that entity (as an array list, or a string space-separated words).
     * - bannedWords: List of banned words.
     * - on: Indicates when to extract words, `update` when entity is being updated,
     * `insert` when a new entity is inserted into table. Or `both` (by default)
     *
     * @var array
     */
    protected $_defaultConfig = [
        'operators' => [],
        'fields' => [],
        'bannedWords' => [],
        'on' => 'both',
        'implementedMethods' => [
            'search' => 'search',
            'addSearchOperator' => 'addSearchOperator',
            'enableSearchOperator' => 'enableSearchOperator',
            'disableSearchOperator' => 'disableSearchOperator',
        ],
    ];

    /**
     * Constructor
     *
     * @param \Cake\ORM\Table $table The table this behavior is attached to.
     * @param array $config The config for this behavior.
     */
    public function __construct(Table $table, array $config = [])
    {
        $this->_table = $table;
        $this->_table->hasOne('Search.SearchDatasets', [
            'foreignKey' => 'entity_id',
            'conditions' => ['table_alias' => Inflector::underscore($this->_table->alias())],
            'dependent' => true
        ]);
        parent::__construct($table, $config);
    }

    /**
     * Generates a list of words after each entity is saved.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Entity $entity The entity that was saved
     * @return void
     */
    public function afterSave(Event $event, Entity $entity)
    {
        $isNew = $entity->isNew();
        $pk = $this->_table->primaryKey();
        $tableAlias = Inflector::underscore($this->_table->alias());
        $text = '';

        if (($this->config('on') === 'update' && $isNew) ||
            ($this->config('on') === 'insert' && !$isNew) ||
            ($this->config('on') !== 'both')
        ) {
            continue;
        }

        if (is_callable($this->config('fields'))) {
            $callable = $this->config('fields');
            $text = $callable($entity);

            if (is_array($text)) {
                $text = implode(' ', (string)$text);
            }
        } else {
            foreach ($this->config('fields') as $f) {
                if ($entity->has($f)) {
                    $newWords = trim($entity->get($f));
                    $text .= ' ' . $newWords;
                }
            }
        }

        $words = $this->_extractWords($text);
        $bannedCallable = is_callable($this->config('bannedWords')) ? $this->config('bannedWords') : false;

        foreach ($words as $i => $w) {
            if ($bannedCallable) {
                if (!$bannedCallable($w)) {
// false means it's banned
                    unset($words[$i]);
                }
            } else {
                if (in_array($w, $this->config('bannedWords')) || empty($w)) {
                    unset($words[$i]);
                }
            }
        }

        $Datasets = TableRegistry::get('Search.SearchDatasets');
        $dataset = $Datasets->find()
            ->where([
                'entity_id' => $entity->get($pk),
                'table_alias' => $tableAlias
            ])
            ->first();

        if (!$dataset) {
            $dataset = new SearchDataset([
                'entity_id' => $entity->get($pk),
                'table_alias' => $tableAlias,
            ]);
        }

        $dataset->set('words', ' ' . implode(' ', $words) . ' ');
        $Datasets->save($dataset);
    }

    /**
     * Prepares entity to delete its words-index.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\ORM\Entity $entity The entity that was removed
     * @return void
     */
    public function beforeDelete(Event $event, Entity $entity)
    {
        $tableAlias = Inflector::underscore($this->_table->alias());
        $this->_table->hasMany('SearchDatasets', [
            'className' => 'Search.SearchDatasets',
            'foreignKey' => 'entity_id',
            'conditions' => ['table_alias' => $tableAlias],
            'dependent' => true,
        ]);
        return true;
    }

    /**
     * Scopes the given query object.
     *
     * It looks for search-criteria and applies them over the query object. For example,
     * given the criteria below:
     *
     *     "this phrase" -"and not this one"
     *
     * Alters the query object as follow:
     *
     *     $query->where([
     *        'indexed_words LIKE' => '%this phrase%',
     *        'indexed_words NOT LIKE' => '%and not this one%'
     *     ]);
     *
     * The `AND` & `OR` keywords are allowed to create complex conditions. For example:
     *
     *     "this phrase" OR -"and not this one" AND "this"
     *
     * Will produce something like:
     *
     *     $query->where(['indexed_words LIKE' => '%this phrase%'])
     *         ->orWhere(['indexed_words NOT LIKE' => '%and not this one%']);
     *         ->andWhere(['indexed_words LIKE' => '%this%']);
     *
     * @param string $criteria A search-criteria. e.g. `"this phrase" author:username`
     * @param null|\Cake\ORM\Query $query The query to scope, or null to create one
     * @return \Cake\ORM\Query Scoped query
     * @throws Cake\Error\FatalErrorException When query gets corrupted while
     *  processing tokens
     */
    public function search($criteria, $query = null)
    {
        $query = is_null($query) ? $this->_table->find() : $query;
        $tokens = $this->_getTokens($criteria);
        $query->contain('SearchDatasets');

        foreach ($tokens as $k => $token) {
            if (in_array(strtolower($token), ['or', 'and'])) {
                continue;
            }

            $previousToken = $k > 0 && isset($tokens[$k - 1]) ? $tokens[$k - 1] : null;
            $orAnd = in_array(strtolower($previousToken), ['or', 'and']) ? strtolower($previousToken) : null;

            if (strpos($token, ':') !== false) {
                $parts = explode(':', $token);
                $operator = array_shift($parts);
                $negate = str_starts_with($operator, '-');
                $operator = Inflector::underscore(preg_replace('/\PL/u', '', $operator));
                $callable = $this->_operatorCallable($operator);
                $value = implode('', $parts);

                if ($callable) {
                    $query = $callable($query, $value, $negate, $orAnd);

                    if (!($query instanceof Query)) {
                        throw new FatalErrorException(__d('search', 'Error while processing the "{0}" token in the search criteria.', $operator));
                    }
                } else {
                    $hookName = Inflector::variable("operator_{$operator}");
                    $result = $this->trigger(["SearchableBehavior.{$hookName}", $this->_table], $query, $value, $negate, $orAnd)->result;

                    if ($result instanceof Query) {
                        $query = $result;
                    }
                }
            } else {
                if (strpos($token, '-') === 0) {
                    $token = str_replace_once('-', '', $token);
                    $LIKE = 'NOT LIKE';
                } else {
                    $LIKE = 'LIKE';
                }

                $token = str_replace('*', '%', $token); // * Matches any one or more characters.
                $token = str_replace('!', '_', $token); // ! Matches any single character.

                if ($orAnd === 'or') {
                    $query->orWhere(["SearchDatasets.words {$LIKE}" => "%{$token}%"]);
                } elseif ($orAnd === 'and') {
                    $query->andWhere(["SearchDatasets.words {$LIKE}" => "%{$token}%"]);
                } else {
                    $query->where(["SearchDatasets.words {$LIKE}" => "%{$token}%"]);
                }
            }
        }

        return $query;
    }

    /**
     * Registers a new operator method.
     *
     * @param string $name Operator name. e.g. `author`
     * @param mixed $methodName A string indicating the table's method name
     *  which will take care of this operator, or an array compatible with
     *  call_user_func_array or a callable function
     * @return void
     */
    public function addSearchOperator($name, $methodName)
    {
        $name = Inflector::underscore($name);
        $this->config("operators.{$name}", $methodName);
    }

    /**
     * Enables a an operator.
     *
     * @param string $name Name of the operator to be enabled
     * @return void
     */
    public function enableSearchOperator($name)
    {
        if (isset($this->_config['operators'][":{$name}"])) {
            $this->_config['operators'][$name] = $this->_config['operators'][":{$name}"];
            unset($this->_config['operators'][":{$name}"]);
        }
    }

    /**
     * Disables an operator.
     *
     * @param string $name Name of the operator to be disabled
     * @return void
     */
    public function disableSearchOperator($name)
    {
        if (isset($this->_config['operators'][$name])) {
            $this->_config['operators'][":{$name}"] = $this->_config['operators'][$name];
            unset($this->_config['operators'][$name]);
        }
    }

    /**
     * Extracts words from given text.
     *
     * @param string $text The text from where extract words
     * @return array List of words
     */
    protected function _extractWords($text)
    {
        $text = str_replace(["\n", "\r"], '', $text);
        $text = preg_replace('/[^a-z\s]/i', ' ', $text); // letters ands white spaces only
        $text = trim(preg_replace('/\s{2,}/i', ' ', $text)); // remove double spaces
        $text = strtolower($text); // all to lowercase
        $words = explode(' ', $text); // convert to array
        return $words;
    }

    /**
     * Gets the callable method for a given operator method.
     *
     * @param string $name Name of the method to get
     * @return callable
     */
    protected function _operatorCallable($name)
    {
        $operators = $this->config('operators');

        if (isset($operators[$name])) {
            $callableName = $operators[$name];

            if (is_array($callableName)) {
                return function ($query, $value, $negate, $orAnd) use ($callableName) {
                    return call_user_func_array($callableName, [$query, $value, $negate, $orAnd]);
                };
            } elseif (is_callable($callableName)) {
                return function ($query, $value, $negate, $orAnd) use ($callableName) {
                    return $callableName($query, $value, $negate, $orAnd);
                };
            } elseif (method_exists($this->_table, $callableName)) {
                return function ($query, $value, $negate, $orAnd) use ($callableName) {
                    return $this->_table->$callableName($query, $value, $negate, $orAnd);
                };
            }
        }

        return false;
    }

    /**
     * Extract tokens from search-criteria.
     *
     * @param string $criteria A search-criteria
     * @return array List of extracted tokens
     */
    protected function _getTokens($criteria)
    {
        $criteria = trim(urldecode($criteria));
        $criteria = preg_replace('/(-?[\w]+)\:"([\]\[\w\s]+)/', '"${1}:${2}', $criteria);
        $criteria = str_replace(['-"', '+"'], ['"-', '"+'], $criteria);
        $tokens = str_getcsv($criteria, ' ');
        return $tokens;
    }
}
