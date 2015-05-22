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

use Cake\Datasource\EntityInterface;
use Cake\Error\FatalErrorException;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Search\Operator;
use Search\Token;

/**
 * This behavior allows entities to be searchable through an auto-generated
 * list of words.
 *
 * ## Using this Behavior
 *
 * You must indicate which fields can be indexed when attaching this behavior
 * to your tables. For example, when attaching this behavior to `Users` table:
 *
 * ```php
 * $this->addBehavior('Search.Searchable', [
 *     'fields' => ['username', 'email']
 * ]);
 * ```
 *
 * In the example above, this behavior will look for words to index in user's
 * "username" and user's "email" properties.
 *
 * If you need a really special selection of words for each entity is being indexed,
 * then you can set the `fields` option as a callable which should return a list of
 * words for the given entity. For example:
 *
 * ```php
 * $this->addBehavior('Search.Searchable', [
 *     'fields' => function ($user) {
 *         return "{$user->name} {$user->email}";
 *     }
 * ]);
 * ```
 *
 * You can return either, a plain text of space-separated words, or an array list
 * of words:
 *
 * ```php
 * $this->addBehavior('Search.Searchable', [
 *     'fields' => function ($user) {
 *         return [
 *             'word 1',
 *             'word 2',
 *             'word 3',
 *         ];
 *     }
 * ]);
 * ```
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
 * ```php
 * $this->addBehavior('Search.Searchable', [
 *     'bannedWords' => ['of', 'the', 'and']
 * ]);
 * ```
 *
 * If you need to ban a really specific list of words you can set `bannedWords`
 * option as a callable method that should return true or false to tell if a words
 * should be indexed or not. For example:
 *
 * ```php
 * $this->addBehavior('Search.Searchable', [
 *     'bannedWords' => function ($word) {
 *         return strlen($word) > 3;
 *     }
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
 * You can define custom operators for your table by using the `addSearchOperator()`
 * method. For example, you might need create a custom operator `author` which
 * allows you to search a `Content` entity by `author name`. A search-criteria using
 * this operator may looks as follow:
 *
 *     // get all contents containing `this phrase` and created by `JohnLocke`
 *     "this phrase" author:JohnLocke
 *
 * You can define in your table an operator method and register it into this
 * behavior under the `author` name, a full working example may look as follow:
 *
 * ```php
 * class MyTable extends Table
 * {
 *     public function initialize(array $config)
 *     {
 *         // attach the behavior
 *         $this->addBehavior('Search.Searchable');
 *
 *         // register a new operator for handling `author:<author_name>` expressions
 *         $this->addSearchOperator('author', 'operatorAuthor');
 *     }
 *
 *     public function operatorAuthor(Query $query, Token $token)
 *     {
 *         // $query: The query object to alter
 *         // $token: Token representing the operator to apply.
 *         // Scope query using $token information and return.
 *         return $query;
 *     }
 * }
 * ```
 *
 * You can also define operator as a callable function:
 *
 * ```php
 * class MyTable extends Table
 * {
 *     public function initialize(array $config)
 *     {
 *         $this->addBehavior('Search.Searchable');
 *
 *         $this->addSearchOperator('author', function(Query $query, Token $token) {
 *             // Scope query and return.
 *             return $query;
 *         });
 *     }
 * }
 * ```
 *
 * ### Creating Reusable Operators
 *
 * If your application has operators that are commonly reused, it is helpful to
 * package those operators into re-usable classes:
 *
 * ```php
 * // in MyPlugin/Model/Search/CustomOperator.php
 * namespace MyPlugin\Model\Search;
 *
 * use Search\Operator;
 *
 * class CustomOperator extends Operator
 * {
 *     public function scope($query, $token)
 *     {
 *         // Scope $query
 *         return $query;
 *     }
 * }
 *
 * // In any table class:
 *
 * // Add the custom operator,
 * $this->addSearchOperator('operator_name', 'MyPlugin.Custom', ['opt1' => 'val1', ...]);
 *
 * // OR passing a constructed operator
 * use MyPlugin\Model\Search\CustomOperator;
 * $this->addSearchOperator('operator_name', new CustomOperator($this, ['opt1' => 'val1', ...]));
 * ```
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
 * ```php
 * // ...
 *
 * public function implementedEvents() {
 *     return [
 *         'SearchableBehavior.operatorDate' => 'operatorDate',
 *     ];
 * }
 *
 * // ...
 *
 * public function operatorDate($event, $query, $token) {
 *     // Scope $query object and return it
 *     return $query;
 * }
 *
 * // ...
 * ```
 *
 * IMPORTANT:
 *
 * - Event handler method should always return the modified $query object.
 * - The event's context, that is `$event->subject()`, is the table instance that
 *   fired the event.
 */
class SearchableBehavior extends Behavior
{

    /**
     * Behavior configuration array.
     *
     * - operators: A list of registered operators methods as `name` =>
     *   `methodName`.
     *
     * - strict: Set to TRUE to filter any invalid word, if so any invalid character
     *   will be removed. Defaults to FALSE.
     *
     * - fields: List of entity fields where to look for words. Or a callable
     *   method, it receives and entity as first argument and it must return a list
     *   of words for that entity (as an array list, or a string of space-separated
     *   words). Or NULL to let this behavior to automatically extract words from
     *   every entity's property if possible. Defaults to NULL.
     *
     * - bannedWords: Array list of banned words, or a callable that should decide
     *   if the given word is banned or not. Defaults to empty array (allow
     *   everything).
     *
     * - on: Indicates when to extract words, `update` when entity is being updated,
     * `insert` when a new entity is inserted into table. Or `both` (by default).
     *
     * @var array
     */
    protected $_defaultConfig = [
        'operators' => [],
        'strict' => false,
        'fields' => null,
        'bannedWords' => [],
        'on' => 'both',
        'implementedMethods' => [
            'search' => 'search',
            'indexEntity' => 'indexEntity',
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
            'conditions' => ['table_alias' => (string)Inflector::underscore($this->_table->alias())],
            'dependent' => true
        ]);
        $config['pk'] = $this->_table->primaryKey();
        $config['table_alias'] = (string)Inflector::underscore($this->_table->alias());
        parent::__construct($table, $config);

        if ($this->config('fields') === null) {
            $this->config('fields', $this->_defaultFieldsCallable());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function implementedEvents()
    {
        $events = parent::implementedEvents();
        $events['afterSave'] = [
            'callable' => 'afterSave',
            'priority' => 500,
            'passParams' => true
        ];
        return $events;
    }

    /**
     * Generates a list of words after each entity is saved.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was saved
     * @return void
     */
    public function afterSave(Event $event, EntityInterface $entity)
    {
        $isNew = $entity->isNew();
        if (($this->config('on') === 'update' && $isNew) ||
            ($this->config('on') === 'insert' && !$isNew)
        ) {
            return;
        }
        $this->indexEntity($entity);
    }

    /**
     * Indexes the given entity.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity that was saved
     * @return bool True success
     */
    public function indexEntity(EntityInterface $entity)
    {
        $Datasets = TableRegistry::get('Search.SearchDatasets');
        $set = $Datasets->find()
            ->where([
                'entity_id' => $entity->get($this->config('pk')),
                'table_alias' => $this->config('table_alias'),
            ])
            ->limit(1)
            ->first();

        if (!$set) {
            $set = $Datasets->newEntity([
                'entity_id' => $entity->get($this->config('pk')),
                'table_alias' => $this->config('table_alias'),
                'words' => '',
            ]);
        }

        // We add starting and trailing space to allow LIKE %something-to-match%
        $set = $Datasets->patchEntity($set, [
            'words' => ' ' . $this->_extractEntityWords($entity) . ' '
        ]);

        return (bool)$Datasets->save($set);
    }

    /**
     * Prepares entity to delete its words-index.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was removed
     * @return bool
     */
    public function beforeDelete(Event $event, EntityInterface $entity)
    {
        $this->_table->hasMany('SearchDatasets', [
            'className' => 'Search.SearchDatasets',
            'foreignKey' => 'entity_id',
            'conditions' => ['table_alias' => $this->config('table_alias')],
            'dependent' => true,
        ]);
        return true;
    }

    /**
     * Scopes the given query object.
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
     * The `AND` & `OR` keywords are allowed to create complex conditions. For example:
     *
     *     "this phrase" OR -"and not this one" AND "this"
     *
     * Will produce something like:
     *
     * ```php
     * $query->where(['indexed_words LIKE' => '%this phrase%'])
     *     ->orWhere(['indexed_words NOT LIKE' => '%and not this one%']);
     *     ->andWhere(['indexed_words LIKE' => '%this%']);
     * ```
     *
     * @param string $criteria A search-criteria. e.g. `"this phrase" author:username`
     * @param \Cake\ORM\Query|null $query The query to scope, or null to create one
     * @return \Cake\ORM\Query Scoped query
     * @throws Cake\Error\FatalErrorException When query gets corrupted while
     *  processing tokens
     */
    public function search($criteria, $query = null)
    {
        if ($query === null) {
            $query = $this->_table->find();
        }
        $query->contain('SearchDatasets');

        foreach ($this->_getTokens($criteria) as $token) {
            if ($token->isOperator()) {
                $this->_scopeOperator($query, $token);
            } else {
                $this->_scopeWords($query, $token);
            }
        }

        return $query;
    }

    /**
     * Scopes the given query using the given operator token.
     *
     * @param \Cake\ORM\Query $query The query to scope
     * @param \Search\Token $token Token describing an operator. e.g `-op_name:op_value`
     * @return \Cake\ORM\Query Scoped query
     */
    protected function _scopeOperator(Query $query, Token $token)
    {
        $callable = $this->_operatorCallable($token->name());

        if (is_callable($callable)) {
            $query = $callable($query, $token);
            if (!($query instanceof Query)) {
                throw new FatalErrorException(__d('search', 'Error while processing the "{0}" token in the search criteria.', $operator));
            }
        } else {
            $result = $this->_triggerScope($query, $token);
            if ($result instanceof Query) {
                $query = $result;
            }
        }

        return $query;
    }

    /**
     * Generates default callable object for extracting entity's words.
     *
     * @return callable
     */
    protected function _defaultFieldsCallable()
    {
        return function ($entity) {
            $words = [];
            foreach ($entity->visibleProperties() as $property) {
                $value = $entity->get($property);

                if (is_string($value) ||
                    is_integer($value) ||
                    is_bool($value) ||
                    (is_object($value) && method_exists($value, '__toString'))
                ) {
                    $words[] = (string)$value;
                } elseif (is_array($value)) {
                    foreach (array_values(Hash::flatten($value)) as $value) {
                        $words[] = (string)$value;
                    }
                }
            }
            return $words;
        };
    }

    /**
     * Triggers an event for handling undefined operators. Event listeners may
     * capture this event and provide operator handling logic, such listeners should
     * alter the provided Query object and then return it back.
     *
     * The triggered event follows the pattern:
     *
     * ```
     * SearchableBehavior.operator<CamelCaseOperatorName>
     * ```
     *
     * For example, `SearchableBehavior.operatorAuthorName` will be triggered for
     * handling an operator named either `author-name` or `author_name`.
     *
     * @param \Cake\ORM\Query $query The query that is expected to be scoped
     * @param \Search\Token $token Token describing an operator. e.g `-op_name:op_value`
     * @return mixed Scoped query object expected or null if event was not captured
     *  by any listener
     */
    protected function _triggerScope(Query $query, Token $token)
    {
        $eventName = 'SearchableBehavior.' . (string)Inflector::variable('operator_' . $token->name());
        $event = new Event($eventName, $this, compact('query', 'token'));
        return EventManager::instance()->dispatch($event)->result;
    }

    /**
     * Scopes the given query using the given words token.
     *
     * @param \Cake\ORM\Query $query The query to scope
     * @param \Search\Token $token Token describing a words sequence. e.g `this is a phrase`
     * @return \Cake\ORM\Query Scoped query
     */
    protected function _scopeWords(Query $query, Token $token)
    {
        $LIKE = 'LIKE';
        if ($token->negated()) {
            $LIKE = 'NOT LIKE';
        }

        // * Matches any one or more characters.
        // ! Matches any single character.
        $value = str_replace(['*', '!'], ['%', '_'], $token->value());

        if ($token->where() === 'or') {
            $query->orWhere(["SearchDatasets.words {$LIKE}" => "%{$value}%"]);
        } elseif ($token->where() === 'and') {
            $query->andWhere(["SearchDatasets.words {$LIKE}" => "%{$value}%"]);
        } else {
            $query->where(["SearchDatasets.words {$LIKE}" => "%{$value}%"]);
        }
    }

    /**
     * Registers a new operator method.
     *
     * Allowed formats are:
     *
     * ```php
     * $this->addSearchOperator('created', 'operatorCreated');
     * ```
     *
     * The above will use Table's `operatorCreated()` method to handle the "created"
     * operator.
     *
     * ---
     *
     * ```php
     * $this->addSearchOperator('created', 'MyPlugin.Limit');
     * ```
     *
     * The above will use `MyPlugin\Model\Search\LimitOperator` class to handle the
     * "limit" operator. Note the `Operator` suffix.
     *
     * ---
     *
     * ```php
     * $this->addSearchOperator('created', 'MyPlugin.Limit', ['my_option' => 'option_value']);
     * ```
     *
     * Similar as before, but in this case you can provide some configuration
     * options passing an array as above.
     *
     * ---
     *
     * ```php
     * $this->addSearchOperator('created', 'Full\ClassName');
     * ```
     *
     * Or you can indicate a full class name to use.
     *
     * ---
     *
     * ```php
     * $this->addSearchOperator('created', function ($query, $token) {
     *     // scope $query
     *     return $query;
     * });
     * ```
     *
     * You can simply pass a callable function to handle the operator, this callable
     * must return the altered $query object.
     *
     * ---
     *
     * ```php
     * $this->addSearchOperator('created', new CreatedOperator($table, $options));
     * ```
     *
     * In this case you can directly pass an instance of an operator handler,
     * this object should extends the `Search\Operator` abstract class.
     *
     * @param string $name Underscored operator's name. e.g. `author`
     * @param mixed $handler A valid handler as described above
     * @return void
     */
    public function addSearchOperator($name, $handler, array $options = [])
    {
        $name = Inflector::underscore($name);
        $operator = [
            'name' => $name,
            'handler' => false,
            'options' => [],
        ];

        if (is_string($handler)) {
            if (method_exists($this->_table, $handler)) {
                $operator['handler'] = $handler;
            } else {
                list($plugin, $class) = pluginSplit($handler);

                if ($plugin) {
                    $className = "{$plugin}\\Model\\Search\\{$class}Operator";
                    $className = str_replace('OperatorOperator', 'Operator', $className);
                } else {
                    $className = $class;
                }

                $operator['handler'] = $className;
                $operator['options'] = $options;
            }
        } elseif (is_object($handler) || is_callable($handler)) {
            $operator['handler'] = $handler;
        }

        $this->config("operators.{$name}", $operator);
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
     * Extracts a list of words to by indexed for given entity.
     *
     * NOTE: Words can be repeated, this allows to search phrases.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity for which generate
     *  the list of words
     * @return string Space-separated list of words. e.g. `cat dog this that`
     */
    protected function _extractEntityWords(EntityInterface $entity)
    {
        $text = '';
        $fields = $this->config('fields');
        if (is_callable($fields)) {
            $callable = $this->config('fields');
            $text = $callable($entity);
            if (is_array($text)) {
                $text = implode(' ', (array)$text);
            }
        } elseif (is_array($fields)) {
            foreach ($fields as $f) {
                if ($entity->has($f)) {
                    $text .= ' ' . trim($entity->get($f));
                }
            }
        }

        $text = str_replace(["\n", "\r"], '', (string)$text); // remove new lines
        $text = strip_tags($text); // remove HTML tags, but keep their content

        if ($this->config('strict')) {
            $text = preg_replace('/[^\p{L}\s]/i', ' ', $text); // letters (any language) ands white spaces only
        }

        $text = trim(preg_replace('/\s{2,}/i', ' ', $text)); // remove double spaces
        $text = strtolower($text); // all to lowercase
        $text = $this->_filterText($text); // filter
        $text = iconv("UTF-8", "UTF-8//IGNORE", $text); // remove any invalid character
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

    /**
     * Gets the callable method for a given operator method.
     *
     * @param string $name Name of the method to get
     * @return bool|callable False if no callback was found for the given operator
     *  name. Or the callable if found.
     */
    protected function _operatorCallable($name)
    {
        $operator = $this->config("operators.{$name}");

        if ($operator) {
            $handler = $operator['handler'];

            if (is_callable($handler)) {
                return function ($query, $token) use ($handler) {
                    return $handler($query, $token);
                };
            } elseif ($handler instanceof Operator) {
                return function ($query, $token) use ($handler) {
                    return $handler->scope($query, $token);
                };
            } elseif (is_string($handler) && method_exists($this->_table, $handler)) {
                return function ($query, $token) use ($handler) {
                    return $this->_table->$handler($query, $token);
                };
            } elseif (is_string($handler) && class_exists($handler)) {
                return function ($query, $token) use ($operator) {
                    $instance = new $operator['handler']($this->_table, $operator['options']);
                    return $instance->scope($query, $token);
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
        $parts = str_getcsv($criteria, ' ');
        $tokens = [];

        foreach ($parts as $i => $t) {
            if (in_array(strtolower($t), ['or', 'and'])) {
                continue;
            }

            $where = null;
            if (isset($parts[$i - 1]) &&
                in_array(strtolower($parts[$i - 1]), ['or', 'and'])
            ) {
                $where = $parts[$i - 1];
            }

            $tokens[] = new Token($t, $where);
        }

        return $tokens;
    }
}
