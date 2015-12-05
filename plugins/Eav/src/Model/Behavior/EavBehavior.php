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
namespace Eav\Model\Behavior;

use Cake\Cache\Cache;
use Cake\Datasource\EntityInterface;
use Cake\Error\FatalErrorException;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Eav\Model\Behavior\EavToolbox;
use Eav\Model\Behavior\QueryScope\QueryScopeInterface;
use Eav\Model\Behavior\QueryScope\SelectScope;
use Eav\Model\Behavior\QueryScope\WhereScope;
use Eav\Model\Entity\CachedColumn;
use \ArrayObject;

/**
 * EAV Behavior.
 *
 * Allows additional columns to be added to tables without altering its physical
 * schema.
 *
 * ### Usage:
 *
 * ```php
 * $this->addBehavior('Eav.Eav');
 * $this->addColumn('user-age', ['type' => 'integer']);
 * ```
 *
 * Using virtual attributes in WHERE clauses:
 *
 * ```php
 * $adults = $this->Users->find()
 *     ->where(['user-age >' => 18])
 *     ->all();
 * ```
 *
 * ### Using EAV Cache:
 *
 * ```php
 * $this->addBehavior('Eav.Eav', [
 *     'cache' => [
 *         'contact_info' => ['user-name', 'user-address'],
 *         'eav_all' => '*',
 *     ],
 * ]);
 * ```
 *
 * Cache all EAV values into a real column named `eav_all`:
 *
 * ```php
 * $this->addBehavior('Eav.Eav', [
 *     'cache' => 'eav_all',
 * ]);
 * ```
 *
 * @link https://github.com/quickapps/docs/blob/2.x/en/developers/field-api.rst
 */
class EavBehavior extends Behavior
{

    /**
     * Instance of EavToolbox.
     *
     * @var \Eav\Model\Behavior\EavToolbox
     */
    protected $_toolbox = null;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'enabled' => true,
        'cache' => false,
        'queryScope' => [
            'Eav\\Model\\Behavior\\QueryScope\\SelectScope',
            'Eav\\Model\\Behavior\\QueryScope\\WhereScope',
            'Eav\\Model\\Behavior\\QueryScope\\OrderScope',
        ],
        'implementedMethods' => [
            'enableEav' => 'enableEav',
            'disableEav' => 'disableEav',
            'updateEavCache' => 'updateEavCache',
            'addColumn' => 'addColumn',
            'dropColumn' => 'dropColumn',
            'listColumns' => 'listColumns',
        ],
    ];

    /**
     * Query scopes objects to be applied indexed by unique ID.
     *
     * @var array
     */
    protected $_queryScopes = [];

    /**
     * Constructor.
     *
     * @param \Cake\ORM\Table $table The table this behavior is attached to
     * @param array $config Configuration array for this behavior
     */
    public function __construct(Table $table, array $config = [])
    {
        $config['cacheMap'] = false; // private config, prevent user modifications
        $this->_toolbox = new EavToolbox($table);
        parent::__construct($table, $config);

        if ($this->config('cache')) {
            $info = $this->config('cache');
            $holders = []; // column => [list of virtual columns]

            if (is_string($info)) {
                $holders[$info] = ['*'];
            } elseif (is_array($info)) {
                foreach ($info as $column => $fields) {
                    if (is_integer($column)) {
                        $holders[$fields] = ['*'];
                    } else {
                        $holders[$column] = ($fields === '*') ? ['*'] : $fields;
                    }
                }
            }

            $this->config('cacheMap', $holders);
        }
    }

    /**
     * Enables EAV behavior so virtual columns WILL be fetched from database.
     *
     * @return void
     */
    public function enableEav()
    {
        $this->config('enabled', true);
    }

    /**
     * Disables EAV behavior so virtual columns WLL NOT be fetched from database.
     *
     * @return void
     */
    public function disableEav()
    {
        $this->config('enabled', false);
    }

    /**
     * Defines a new virtual-column, or update if already defined.
     *
     * ### Usage:
     *
     * ```php
     * $errors = $this->Users->addColumn('user-age', [
     *     'type' => 'integer',
     *     'bundle' => 'some-bundle-name',
     *     'extra' => [
     *         'option1' => 'value1'
     *     ]
     * ], true);
     *
     * if (empty($errors)) {
     *     // OK
     * } else {
     *     // ERROR
     *     debug($errors);
     * }
     * ```
     *
     * The third argument can be set to FALSE to get a boolean response:
     *
     * ```php
     * $success = $this->Users->addColumn('user-age', [
     *     'type' => 'integer',
     *     'bundle' => 'some-bundle-name',
     *     'extra' => [
     *         'option1' => 'value1'
     *     ]
     * ]);
     *
     * if ($success) {
     *     // OK
     * } else {
     *     // ERROR
     * }
     * ```
     *
     * @param string $name Column name. e.g. `user-age`
     * @param array $options Column configuration options
     * @param bool $errors If set to true will return an array list of errors
     *  instead of boolean response. Defaults to TRUE
     * @return bool|array True on success or array of error messages, depending on
     *  $error argument
     * @throws \Cake\Error\FatalErrorException When provided column name collides
     *  with existing column names. And when an invalid type is provided
     */
    public function addColumn($name, array $options = [], $errors = true)
    {
        if (in_array($name, (array)$this->_table->schema()->columns())) {
            throw new FatalErrorException(__d('eav', 'The column name "{0}" cannot be used as it is already defined in the table "{1}"', $name, $this->_table->alias()));
        }

        $data = $options + [
            'type' => 'string',
            'bundle' => null,
            'searchable' => true,
        ];

        $data['type'] = $this->_toolbox->mapType($data['type']);
        if (!in_array($data['type'], EavToolbox::$types)) {
            throw new FatalErrorException(__d('eav', 'The column {0}({1}) could not be created as "{2}" is not a valid type.', $name, $data['type'], $data['type']));
        }

        $data['name'] = $name;
        $data['table_alias'] = $this->_table->table();
        $attr = TableRegistry::get('Eav.EavAttributes')->find()
            ->where([
                'name' => $data['name'],
                'table_alias' => $data['table_alias'],
                'bundle IS' => $data['bundle'],
            ])
            ->limit(1)
            ->first();

        if ($attr) {
            $attr = TableRegistry::get('Eav.EavAttributes')->patchEntity($attr, $data);
        } else {
            $attr = TableRegistry::get('Eav.EavAttributes')->newEntity($data);
        }

        $success = (bool)TableRegistry::get('Eav.EavAttributes')->save($attr);
        Cache::clear(false, 'eav_table_attrs');

        if ($errors) {
            return (array)$attr->errors();
        }

        return (bool)$success;
    }

    /**
     * Drops an existing column.
     *
     * @param string $name Name of the column to drop
     * @param string|null $bundle Removes the column within a particular bundle
     * @return bool True on success, false otherwise
     */
    public function dropColumn($name, $bundle = null)
    {
        $attr = TableRegistry::get('Eav.EavAttributes')->find()
            ->where([
                'name' => $name,
                'table_alias' => $this->_table->table(),
                'bundle IS' => $bundle,
            ])
            ->limit(1)
            ->first();

        Cache::clear(false, 'eav_table_attrs');
        if ($attr) {
            return (bool)TableRegistry::get('Eav.EavAttributes')->delete($attr);
        }

        return false;
    }

    /**
     * Gets a list of virtual columns attached to this table.
     *
     * @param string|null $bundle Get attributes within given bundle, or all of them
     *  regardless of the bundle if not provided
     * @return array Columns information indexed by column name
     */
    public function listColumns($bundle = null)
    {
        $columns = [];
        foreach ($this->_toolbox->attributes($bundle) as $name => $attr) {
            $columns[$name] = [
                'id' => $attr->get('id'),
                'bundle' => $attr->get('bundle'),
                'name' => $name,
                'type' => $attr->get('type'),
                'searchable ' => $attr->get('searchable'),
                'extra ' => $attr->get('extra'),
            ];
        }
        return $columns;
    }

    /**
     * Update EAV cache for the specified $entity.
     *
     * @return bool Success
     */
    public function updateEavCache(EntityInterface $entity)
    {
        if (!$this->config('cacheMap')) {
            return false;
        }

        $attrsById = [];
        foreach ($this->_toolbox->attributes() as $attr) {
            $attrsById[$attr['id']] = $attr;
        }

        if (empty($attrsById)) {
            return true; // nothing to cache
        }

        $values = [];
        $query = TableRegistry::get('Eav.EavValues')
            ->find('all')
            ->where([
                'EavValues.eav_attribute_id IN' => array_keys($attrsById),
                'EavValues.entity_id' => $this->_toolbox->getEntityId($entity),
            ]);

        foreach ($query as $v) {
            $type = $attrsById[$v->get('eav_attribute_id')]->get('type');
            $name = $attrsById[$v->get('eav_attribute_id')]->get('name');
            $values[$name] = $this->_toolbox->marshal($v->get("value_{$type}"), $type);
        }

        $toUpdate = [];
        foreach ((array)$this->config('cacheMap') as $column => $fields) {
            $cache = [];
            if (in_array('*', $fields)) {
                $cache = $values;
            } else {
                foreach ($fields as $field) {
                    if (isset($values[$field])) {
                        $cache[$field] = $values[$field];
                    }
                }
            }

            $toUpdate[$column] = (string)serialize(new CachedColumn($cache));
        }

        if (!empty($toUpdate)) {
            $conditions = []; // scope to entity's PK (composed PK supported)
            $keys = $this->_table->primaryKey();
            $keys = !is_array($keys) ? [$keys] : $keys;
            foreach ($keys as $key) {
                // TODO: check key exists in entity's visible properties list.
                // Throw an error otherwise as PK MUST be correctly calculated.
                $conditions[$key] = $entity->get($key);
            }

            if (empty($conditions)) {
                return false;
            }

            return (bool)$this->_table->updateAll($toUpdate, $conditions);
        }

        return true;
    }

    /**
     * Attaches virtual properties to entities.
     *
     * This method iterates over each retrieved entity and invokes the
     * `attachEntityAttributes()` method. This method should return the altered
     * entity object with its virtual properties, however if this method returns
     * NULL the entity will be removed from the resulting collection. And if this
     * method returns FALSE will stop the find() operation.
     *
     * This method is also responsible of looking for virtual columns in SELECT and
     * WHERE clauses (if applicable) and properly scope the Query object. Query
     * scoping is performed by the `_scopeQuery()` method.
     *
     * @param \Cake\Event\Event $event The beforeFind event that was triggered
     * @param \Cake\ORM\Query $query The original query to modify
     * @param \ArrayObject $options Additional options given as an array
     * @param bool $primary Whether this find is a primary query or not
     * @return bool|null
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        if (!$this->config('enabled') ||
            (isset($options['eav']) && $options['eav'] === false)
        ) {
            return true;
        }

        if (!isset($options['bundle'])) {
            $options['bundle'] = null;
        }

        $query = $this->_scopeQuery($query, $options['bundle']);
        return $query->formatResults(function ($results) use ($event, $query, $options, $primary) {
            return $results->map(function ($entity) use ($event, $query, $options, $primary) {
                if ($entity instanceof EntityInterface) {
                    $entity = $this->_prepareCachedColumns($entity);
                    $entity = $this->attachEntityAttributes($entity, compact('event', 'query', 'options', 'primary'));
                }

                if ($entity === false) {
                    $event->stopPropagation();
                    return;
                }

                if ($entity === null) {
                    return false;
                }

                return $entity;
            });
        });
    }

    /**
     * Triggered before data is converted into entities.
     *
     * Converts incoming POST data to its corresponding types.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \ArrayObject $data The POST data to be merged with entity
     * @param \ArrayObject $options The options passed to the marshaller
     * @return void
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        $bundle = !empty($options['bundle']) ? $options['bundle'] : null;
        $attrs = array_keys($this->_toolbox->attributes($bundle));
        foreach ($data as $property => $value) {
            if (!in_array($property, $attrs)) {
                continue;
            }
            $dataType = $this->_toolbox->getType($property);
            $marshaledValue = $this->_toolbox->marshal($value, $dataType);
            $data[$property] = $marshaledValue;
        }
    }

    /**
     * After an entity is saved.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was saved
     * @param \ArrayObject $options Additional options given as an array
     * @return bool True always
     */
    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        $attrsById = [];
        $updatedAttrs = [];
        $valuesTable = TableRegistry::get('Eav.EavValues');

        foreach ($this->_toolbox->attributes() as $name => $attr) {
            if (!$entity->has($name)) {
                continue;
            }

            $attrsById[$attr->get('id')] = $attr;
        }

        if (empty($attrsById)) {
            return true; // nothing to do
        }

        $values = $valuesTable
            ->find()
            ->where([
                'eav_attribute_id IN' => array_keys($attrsById),
                'entity_id' => $this->_toolbox->getEntityId($entity),
            ]);

        foreach ($values as $value) {
            $updatedAttrs[] = $value->get('eav_attribute_id');
            $info = $attrsById[$value->get('eav_attribute_id')];
            $type = $this->_toolbox->getType($info->get('name'));

            $marshaledValue = $this->_toolbox->marshal($entity->get($info->get('name')), $type);
            $value->set("value_{$type}", $marshaledValue);
            $entity->set($info->get('name'), $marshaledValue);
            $valuesTable->save($value);
        }

        foreach ($this->_toolbox->attributes() as $name => $attr) {
            if (!$entity->has($name)) {
                continue;
            }

            if (!in_array($attr->get('id'), $updatedAttrs)) {
                $type = $this->_toolbox->getType($name);
                $value = $valuesTable->newEntity([
                    'eav_attribute_id' => $attr->get('id'),
                    'entity_id' => $this->_toolbox->getEntityId($entity),
                ]);

                $marshaledValue = $this->_toolbox->marshal($entity->get($name), $type);
                $value->set("value_{$type}", $marshaledValue);
                $entity->set($name, $marshaledValue);
                $valuesTable->save($value);
            }
        }

        if ($this->config('cacheMap')) {
            $this->updateEavCache($entity);
        }

        return true;
    }

    /**
     * After an entity was removed from database. Here is when EAV values are
     * removed from DB.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was deleted
     * @param \ArrayObject $options Additional options given as an array
     * @throws \Cake\Error\FatalErrorException When using this behavior in non-atomic mode
     * @return void
     */
    public function afterDelete(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        if (!$options['atomic']) {
            throw new FatalErrorException(__d('eav', 'Entities in fieldable tables can only be deleted using transactions. Set [atomic = true]'));
        }

        $valuesToDelete = TableRegistry::get('Eav.EavValues')
            ->find()
            ->contain(['EavAttribute'])
            ->where([
                'EavAttribute.table_alias' => $this->_table->table(),
                'EavValues.entity_id' => $this->_toolbox->getEntityId($entity),
            ])
            ->all();

        foreach ($valuesToDelete as $value) {
            TableRegistry::get('Eav.EavValues')->delete($value);
        }
    }

    /**
     * The method which actually fetches custom fields, invoked by `beforeFind()`
     * for each entity in the collection.
     *
     * - Returning NULL indicates the entity should be removed from the resulting
     *   collection.
     *
     * - Returning FALSE will stop the entire find() operation.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity where to fetch fields
     * @param array $options Arguments given to `beforeFind()` method, possible keys
     *  are "event", "query", "options", "primary"
     * @return bool|null|\Cake\Datasource\EntityInterface
     */
    public function attachEntityAttributes(EntityInterface $entity, array $options = [])
    {
        $bundle = !empty($options['bundle']) ? $options['bundle'] : null;
        if (empty($this->_queryScopes['Eav\\Model\\Behavior\\QueryScope\\SelectScope'])) {
            return $entity;
        }

        $selectedVirtual = $this->_queryScopes['Eav\\Model\\Behavior\\QueryScope\\SelectScope']->getVirtualColumns($options['query'], $bundle);
        $validColumns = array_values($selectedVirtual);
        $validNames = array_intersect($this->_toolbox->getAttributeNames($bundle), $validColumns);
        $attrsById = [];

        foreach ($this->_toolbox->attributes($bundle) as $name => $attr) {
            if (in_array($name, $validNames)) {
                $attrsById[$attr['id']] = $attr;
            }
        }

        if (empty($attrsById)) {
            return $entity; // no attrs to attach
        }

        $values = TableRegistry::get('Eav.EavValues')
            ->find('all')
            ->where([
                'EavValues.eav_attribute_id IN' => array_keys($attrsById),
                'EavValues.entity_id' => $this->_toolbox->getEntityId($entity),
            ]);

        foreach ($values as $value) {
            $type = $attrsById[$value->get('eav_attribute_id')]->get('type');
            $name = $attrsById[$value->get('eav_attribute_id')]->get('name');
            $value = $value->get("value_{$type}");
            $alias = array_search($name, $selectedVirtual);
            $propertyName = is_string($alias) ? $alias : $name;

            if (!$entity->has($propertyName)) {
                $entity->set($propertyName, $this->_toolbox->marshal($value, $type));
                $entity->dirty($propertyName, false);
            }
        }

        // force cache-columns to be of the proper type as they might be NULL if
        // entity has not been updated yet.
        if ($this->config('cacheMap')) {
            foreach ($this->config('cacheMap') as $column => $fields) {
                if ($entity->has($column) && !($entity->get($column) instanceof Entity)) {
                    $entity->set($column, new Entity);
                }
            }
        }

        return $entity;
    }

    /**
     * Prepares entity's cache-columns (those defined using `cache` option).
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to prepare
     * @return \Cake\Datasource\EntityInterfa Modified entity
     */
    protected function _prepareCachedColumns(EntityInterface $entity)
    {
        if ($this->config('cacheMap')) {
            foreach ((array)$this->config('cacheMap') as $column => $fields) {
                if (in_array($column, $entity->visibleProperties())) {
                    $string = $entity->get($column);
                    if ($string == serialize(false) || @unserialize($string) !== false) {
                        $entity->set($column, unserialize($string));
                    } else {
                        $entity->set($column, new CachedColumn());
                    }
                }
            }
        }

        return $entity;
    }

    /**
     * Look for virtual columns in some query's clauses.
     *
     * @param \Cake\ORM\Query $query The query to scope
     * @param string|null $bundle Consider attributes only for a specific bundle
     * @return \Cake\ORM\Query The modified query object
     */
    protected function _scopeQuery(Query $query, $bundle = null)
    {
        $this->_initScopes();
        foreach ($this->_queryScopes as $scope) {
            if ($scope instanceof QueryScopeInterface) {
                $query = $scope->scope($query, $bundle);
            }
        }
        return $query;
    }

    /**
     * Initializes the scope objects
     *
     * @return void
     */
    protected function _initScopes()
    {
        foreach ((array)$this->config('queryScope') as $className) {
            if (!empty($this->_queryScopes[$className])) {
                continue;
            }

            if (class_exists($className)) {
                $instance = new $className($this->_table);
                if ($instance instanceof QueryScopeInterface) {
                    $this->_queryScopes[$className] = $instance;
                }
            }
        }
    }
}
