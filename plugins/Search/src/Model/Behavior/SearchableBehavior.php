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
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\Utility\Inflector;
use Search\Engine\EngineInterface;
use Search\Error\EngineNotFoundException;
use Search\Operator\BaseOperator;
use Search\Parser\TokenInterface;
use \ArrayObject;

/**
 * This behavior allows entities to be searchable using interchangeable search
 * engines.
 *
 * By default `GenericEngine` will be used if not provided. New engine interface
 * adapters can be created and attached to this behavior, such as `elasticsearch`,
 * `Apache SOLR`, `Sphinx`, etc.
 */
class SearchableBehavior extends Behavior
{

    /**
     * Instance of the engine being used.
     *
     * @var null|\Search\Engine\EngineInterface
     */
    protected $_engine = null;

    /**
     * Behavior configuration array.
     *
     * - indexOn: Indicates when to index entities. `update` when entity is being
     *   updated, `insert` when a new entity is persisted into DB. Or `both` (by
     *   default).
     *
     * @var array
     */
    protected $_defaultConfig = [
        'engine' => [
            'className' => 'Search\\Engine\\Generic\\GenericEngine',
            'config' => [
                'operators' => [],
                'strict' => [],
                'bannedWords' => [],
            ]
        ],
        'indexOn' => 'both',
        'implementedMethods' => [
            'searchEngine' => 'searchEngine',
            'search' => 'search',
            'applySearchOperator' => 'applySearchOperator',
            'addSearchOperator' => 'addSearchOperator',
            'enableSearchOperator' => 'enableSearchOperator',
            'disableSearchOperator' => 'disableSearchOperator',
        ],
    ];

    /**
     * Constructor.
     *
     * @param \Cake\ORM\Table $table The table this behavior is attached to.
     * @param array $config The config for this behavior.
     * @throws \Search\Error\EngineNotFoundException When no engine was
     *  configured
     */
    public function __construct(Table $table, array $config = [])
    {
        parent::__construct($table, $config);
        $engineClass = $this->config('engine.className');
        $engineClass = empty($engineClass) ? 'Search\\Engine\\Generic\\GenericEngine' : $engineClass;
        if (!class_exists($engineClass)) {
            throw new EngineNotFoundException(__d('search', 'The search engine "{0}" was not found.', $engineClass));
        }

        $this->_engine = new $engineClass($table, (array)$this->config('engine.config'));
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
     * Triggers the following events:
     *
     * - `Model.beforeIndex`: Before entity gets indexed by the configured search
     *   engine adapter. First argument is the entity instance being indexed.
     *
     * - `Model.afterIndex`: After entity was indexed by the configured search
     *   engine adapter. First argument is the entity instance that was indexed, and
     *   second indicates whether the indexing process completed correctly or not.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was saved
     * @param \ArrayObject $options Additional options
     * @return void
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        $isNew = $entity->isNew();
        if (($this->config('on') === 'update' && $isNew) ||
            ($this->config('on') === 'insert' && !$isNew) ||
            (isset($options['index']) && $options['index'] === false)
        ) {
            return;
        }

        $this->_table->dispatchEvent('Model.beforeIndex', $entity);
        $success = $this->searchEngine()->index($entity);
        $this->_table->dispatchEvent('Model.afterIndex', $entity, $success);
    }

    /**
     * Prepares entity to delete its words-index.
     *
     * Triggers the following events:
     *
     * - `Model.beforeRemoveIndex`: Before entity's index is removed. First argument
     *   is the affected entity instance.
     *
     * - `Model.afterRemoveIndex`: After entity's index is removed. First argument
     *   is the affected entity instance, and second indicates whether the
     *   index-removing process completed correctly or not.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was removed
     * @param \ArrayObject $options Additional options
     * @return bool
     */
    public function beforeDelete(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        $this->_table->dispatchEvent('Model.beforeRemoveIndex', $entity);
        $success = $this->searchEngine()->delete($entity);
        $this->_table->dispatchEvent('Model.afterRemoveIndex', $entity, $success);
        return $success;
    }

    /**
     * Gets entities matching the given search criteria.
     *
     * @param mixed $criteria A search-criteria compatible with the Search Engine
     *  being used
     * @param \Cake\ORM\Query|null $query The query to scope, or null to create one
     * @return \Cake\ORM\Query Scoped query
     * @throws Cake\Error\FatalErrorException When query gets corrupted while
     *  processing tokens
     */
    public function search($criteria, Query $query = null)
    {
        if ($query === null) {
            $query = $this->_table->find();
        }
        return $this->searchEngine()->search($criteria, $query);
    }

    /**
     * Gets/sets search engine instance.
     *
     * @return \Search\Engine\EngineInterface
     */
    public function searchEngine(EngineInterface $engine = null)
    {
        if ($engine !== null) {
            $this->_engine = $engine;
        }
        return $this->_engine;
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
                    $className = $plugin === 'Search' ? "Search\\Operator\\{$class}Operator" : "{$plugin}\\Model\\Search\\{$class}Operator";
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
     * Given a query instance applies the provided token representing a search
     * operator.
     *
     * @param \Cake\ORM\Query $query The query to be scope
     * @param \Search\TokenInterface $token Token describing an operator. e.g
     *  `-op_name:op_value`
     * @return \Cake\ORM\Query Scoped query
     */
    public function applySearchOperator(Query $query, TokenInterface $token)
    {
        if (!$token->isOperator()) {
            return $query;
        }

        $callable = $this->_operatorCallable($token->name());
        if (is_callable($callable)) {
            $query = $callable($query, $token);
            if (!($query instanceof Query)) {
                throw new FatalErrorException(__d('search', 'Error while processing the "{0}" token in the search criteria.', $operator));
            }
        } else {
            $result = $this->_triggerOperator($query, $token);
            if ($result instanceof Query) {
                $query = $result;
            }
        }

        return $query;
    }

    /**
     * Triggers an event for handling undefined operators. Event listeners may
     * capture this event and provide operator handling logic, such listeners should
     * alter the provided Query object and then return it back.
     *
     * The triggered event follows the pattern:
     *
     * ```
     * Search.operator<CamelCaseOperatorName>
     * ```
     *
     * For example, `Search.operatorAuthorName` will be triggered for
     * handling an operator named either `author-name` or `author_name`.
     *
     * @param \Cake\ORM\Query $query The query that is expected to be scoped
     * @param \Search\TokenInterface $token Token describing an operator. e.g
     *  `-op_name:op_value`
     * @return mixed Scoped query object expected or null if event was not captured
     *  by any listener
     */
    protected function _triggerOperator(Query $query, TokenInterface $token)
    {
        $eventName = 'Search.' . (string)Inflector::variable('operator_' . $token->name());
        $event = new Event($eventName, $this->_table, compact('query', 'token'));
        return EventManager::instance()->dispatch($event)->result;
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
            } elseif ($handler instanceof BaseOperator) {
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
}
