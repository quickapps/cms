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

use Cake\Database\Expression\Comparison;
use Cake\Datasource\EntityInterface;
use Cake\Error\FatalErrorException;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
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
 * @link https://github.com/quickapps/docs/blob/2.x/en/developers/field-api.rst
 */
class EavBehavior extends Behavior
{

    /**
     * Table alias.
     *
     * @var string
     */
    protected $_tableAlias = null;

    /**
     * Attributes index by attribute name.
     *
     * ```php
     * [
     *     'user-age' => [
     *         'type' => 'int',
     *         'searchable' => true
     *     ],
     *     'user-phone' => [
     *         'type' => 'string',
     *         'searchable' => true
     *     ]
     * ]
     * ```
     *
     * @var array
     */
    protected $_attributes = [];

    /**
     * Attributes index by bundle, and by name within each bundle.
     *
     * ```php
     * [
     *     'administrator' => [
     *         'admin-address' => [
     *             'type' => 'varchar',
     *             'searchable' => false
     *         ],
     *         'admin-phone' => [
     *             'type' => 'varchar',
     *             'searchable' => true
     *         ]
     *     ],
     *     'editor' => [
     *         'editor-last-login' => [
     *             'type' => 'datetime',
     *             'searchable' => false,
     *         ]
     *     ]
     * ]
     * ```
     *
     * @var array
     */
    protected $_attributesByBundle = [];

    /**
     * List of all valid attributes names.
     *
     * @var array
     */
    protected $_attributeNames = [];

    /**
     * EavValues table model.
     *
     * @var \Eav\Model\Table\EavValuesTable
     */
    public $Values = null;

    /**
     * EavAttributes table model.
     *
     * @var \Eav\Model\Table\EavAttributesTable
     */
    public $Attributes = null;

    protected $_defaultConfig = [
        'implementedMethods' => [
            'addColumn' => 'addColumn',
        ],
    ];

    /**
     * Constructor.
     *
     * @param \Cake\ORM\Table $table The table this behavior is attached to
     * @param array $config Configuration array for this behavior
     */
    public function __construct(Table $table, array $config = [])
    {
        $this->_tableAlias = (string)Inflector::underscore($table->alias());
        $this->_initModels();
        parent::__construct($table, $config);
    }

    /**
     * Registers a new EAV column or update if already exists.
     *
     * ### Usage:
     *
     * ```php
     * $this->Users->addColumn('user-age', [
     *     'type' => 'integer',
     *     'bundle' => 'some-bundle-name',
     *     'extra' => [
     *         'option1' => 'value1'
     *     ]
     * ]);
     * ```
     *
     * @param string $name Column name. e.g. `user-age`
     * @param array $options Column configuration options
     * @return bool True on success
     * @throws \Cake\Error\FatalErrorException When provided column name collides
     *  with existing column names
     */
    public function addColumn($name, array $options = [])
    {
        if (in_array($name, (array)$this->_table->schema()->columns())) {
            throw new FatalErrorException(__d('eav', 'The column name "{0}" cannot be used as it is already defined in the table "{1}"', $name, $this->_table->alias()));
        }

        $data = $options + [
            'type' => 'varchar',
            'bundle' => null,
            'searchable' => true,
            'extra' => null,
        ];

        $data['type'] = $this->_mapType($data['type']);
        $data['name'] = $name;
        $data['table_alias'] = $this->_tableAlias;
        $attr = $this->Attributes
            ->find()
            ->where([
                'name' => $data['name'],
                'table_alias' => $data['table_alias'],
                'bundle' => $data['bundle'],
            ])
            ->limit(1)
            ->first();

        if ($attr) {
            $attr = $this->Attributes->patchEntity($attr, $data);
        } else {
            $attr = $this->Attributes->newEntity($data);
        }

        return (bool)$this->Attributes->save($attr);
    }

    /**
     * Modifies the query object in order to merge custom fields records into each
     * entity under the `_fields` property.
     *
     * This method iterates over each retrieved entity and invokes the
     * `attachEntityAttributes()` method. This method should return the altered
     * entity object with its virtual properties, however if this method returns
     * FALSE the entity will be removed from the resulting collection. And if this
     * method returns NULL will stop the find() operation.
     *
     * @param \Cake\Event\Event $event The beforeFind event that was triggered
     * @param \Cake\ORM\Query $query The original query to modify
     * @param \ArrayObject $options Additional options given as an array
     * @param bool $primary Whether this find is a primary query or not
     * @return bool|null
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        if (isset($options['eav']) && $options['eav'] === false) {
            return true;
        }

        if (!isset($options['bundle'])) {
            $options['bundle'] = null;
        }

        $query = $this->_scopeQuery($query, $options['bundle']);
        return $query->formatResults(function ($results) use ($event, $options, $primary) {
            return $results->map(function ($entity) use ($event, $options, $primary) {
                if ($entity instanceof EntityInterface) {
                    $entity = $this->attachEntityAttributes($entity, compact('event', 'query', 'options', 'primary'));
                }

                if ($entity === null) {
                    $event->stopPropagation();
                    return;
                }
                return $entity;
            });
        });
    }

    /**
     * Look for virtual columns in query's WHERE clause.
     *
     * @param \Cake\ORM\Query $query The query to scope
     * @param  string|null $bundle Consider attributes only for a specific bundle
     * @return \Cake\ORM\Query The modified query object
     */
    protected function _scopeQuery(Query $query, $bundle = null)
    {
        $whereClause = $query->clause('where');
        if (!$whereClause) {
            return $query;
        }

        $conn = $query->connection(null);
        list(, $driverClass) = namespaceSplit(strtolower(get_class($conn->driver())));
        $alias = $this->_table->alias();
        $pk = $this->_table->primaryKey();
        if (!is_array($pk)) {
            $pk = [$pk];
        }
        $pk = array_map(function ($key) use ($alias) {
            return "{$alias}.{$key}";
        }, $pk);

        $whereClause->traverse(function ($expression) use ($pk, $alias, $driverClass, $bundle) {
            if (!($subQuery = $this->_virtualQuery($expression, $bundle))) {
                return;
            }

            switch ($driverClass) {
                case 'sqlite':
                    $concat = implode(' || ', $pk);
                    $field = "({$concat} || '')";
                    break;
                case 'mysql':
                case 'postgres':
                case 'sqlserver':
                default:
                    $concat = implode(', ', $pk);
                    $field = "CONCAT({$concat}, '')";
                    break;
            }

            $expression->setField($field);
            $expression->setValue($subQuery);
            $expression->setOperator('IN');
        });

        return $query;
    }

    /**
     * Creates a sub-query for matching virtual fields.
     *
     * @param \Cake\Database\Expression\Comparison $expression Expression to scope
     * @param  string|null $bundle Consider attributes only for a specific bundle
     * @return \Cake\ORM\Query|bool False if not virtual field was found, or search
     *  feature was disabled for this field. The query object to use otherwise
     */
    protected function _virtualQuery($expression, $bundle = null)
    {
        if (!($expression instanceof Comparison)) {
            return false;
        }

        $field = $expression->getField();
        $column = is_string($field) ? $this->_columnName($field) : false;
        if (empty($column) ||
            !in_array($column, $this->_getAttributeNames()) ||
            !$this->_isSearchable($column)
        ) {
            return false;
        }

        $value = $expression->getValue();
        $type = $this->_getType($column);
        $conjunction = $expression->getOperator();
        $conditions = [
            'EavAttribute.table_alias' => $this->_tableAlias,
            "EavAttribute.name" => $column,
            "EavValues.value_{$type} {$conjunction}" => $value,
        ];

        if (!empty($bundle)) {
            $conditions['EavAttribute.bundle'] = $bundle;
        }

        return $this->Values
            ->find()
            ->contain(['EavAttribute'])
            ->select('EavValues.entity_id')
            ->where($conditions);
    }

    /**
     * Gets a clean column name from query expression.
     *
     * ### Example:
     *
     * ```php
     * $this->_columnName('Tablename.some_column');
     * // returns "some_column"
     *
     * $this->_columnName('my_column');
     * // returns "my_column"
     * ```
     *
     * @param string $column Column name from query
     * @return string
     */
    protected function _columnName($column)
    {
        list($tableName, $fieldName) = pluginSplit((string)$column);
        if (!$fieldName) {
            $fieldName = $tableName;
        }
        $fieldName = preg_replace('/\s{2,}/', ' ', $fieldName);
        list($fieldName, ) = explode(' ', trim($fieldName));
        return $fieldName;
    }

    /**
     * After an entity is saved.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was saved
     * @param array $options Additional options given as an array
     * @return void
     */
    public function afterSave(Event $event, EntityInterface $entity, $options)
    {
        foreach ($this->_attributes() as $name => $attr) {
            if ($entity->has($name)) {
                $type = $this->_getType($name);
                $value = $this->Values
                    ->find()
                    ->where([
                        'eav_attribute_id' => $attr->get('id'),
                        'entity_id' => $this->_getEntityId($entity),
                    ])
                    ->limit(1)
                    ->first();

                if (!$value) {
                    $value = $this->Values->newEntity([
                        'eav_attribute_id' => $attr->get('id'),
                        'entity_id' => $this->_getEntityId($entity),
                    ]);
                }

                // set the rest to NULL
                foreach (['datetime', 'decimal', 'int', 'text', 'varchar'] as $suffix) {
                    if ($type != $suffix) {
                        $value->set("value_{$suffix}", null);
                    } else {
                        $value->set("value_{$suffix}", $entity->get($name));
                    }
                }
                $this->Values->save($value);
            }
        }
    }

    /**
     * After an entity was removed from database. Here is when EAV values are
     * removed from DB.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was deleted
     * @param array $options Additional options given as an array
     * @throws \Cake\Error\FatalErrorException When using this behavior in non-atomic mode
     * @return void
     */
    public function afterDelete(Event $event, EntityInterface $entity, $options)
    {
        if (!$options['atomic']) {
            throw new FatalErrorException(__d('field', 'Entities in fieldable tables can only be deleted using transactions. Set [atomic = true]'));
        }

        $valuesToDelete = $this->Values
            ->find()
            ->contain(['EavAttribute'])
            ->where([
                'EavAttribute.table_alias' => $this->_tableAlias,
                'EavValues.entity_id' => $this->_getEntityId($entity),
            ])
            ->all();

        foreach ($valuesToDelete as $value) {
            $this->Values->delete($value);
        }
    }

    /**
     * The method which actually fetches custom fields, invoked by `beforeFind()`
     * for each entity in the collection.
     *
     * - Returning FALSE indicates the entity should be removed from the resulting
     *   collection.
     *
     * - Returning NULL will stop the entire find() operation.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity where to fetch fields
     * @param array $options Arguments given to `beforeFind()` method, possible keys
     *  are "event", "query", "options", "primary"
     * @return bool|null|\Cake\Datasource\EntityInterface
     */
    public function attachEntityAttributes(EntityInterface $entity, array $options = [])
    {
        foreach ($this->_attributes() as $name => $attr) {
            $bundle = $this->_getBundle($name);
            if (!empty($options['bundle']) && $bundle != $options['bundle']) {
                continue;
            }

            if (!$entity->has($name)) {
                $type = $this->_getType($name);
                $value = $this->Values
                    ->find()
                    ->select("value_{$type}")
                    ->where([
                        'EavAttribute.table_alias' => $this->_tableAlias,
                        'EavAttribute.bundle' => $bundle,
                        'EavAttribute.attribute' => $name,
                        'EavValues.entity_id' => $this->_getEntityId($entity),
                    ])
                    ->limit(1)
                    ->first();

                if ($value) {
                    $entity->set($name, $value->get("value_{$type}"));
                } else {
                    $entity->set($name, null);
                }
            }
        }
        return $entity;
    }

    /**
     * Gets all attributes added to this table.
     *
     * @return array
     */
    protected function _attributes()
    {
        if (!empty($this->_attributeNames)) {
            return $this->_attributes;
        }

        $attrs = $this->Attributes
            ->find()
            ->where(['EavAttributes.table_alias' => $this->_tableAlias])
            ->all()
            ->toArray();
        foreach ($attrs as $attr) {
            $this->_attributesByBundle[$attr->get('bundle')][$attr->get('name')] = $attr;
            $this->_attributes[$attr->get('name')] = $attr;
            $this->_attributeNames[] = $attr->get('name');
        }
        return $this->_attributes;
    }

    /**
     * Initializes Values and Attributes tables if there were not set before.
     *
     * @return void
     */
    protected function _initModels()
    {
        if (empty($this->Values)) {
            $this->Values = TableRegistry::get('Eav.EavValues');
        }

        if (empty($this->Attributes)) {
            $this->Attributes = TableRegistry::get('Eav.EavAttributes');
        }
    }

    /**
     * Gets a list of attribute names.
     *
     * @param string $bundle Filter by bundle name
     * @return array
     */
    protected function _getAttributeNames($bundle = null)
    {
        if (empty($this->_attributes)) {
            $this->_attributes = $this->_attributes();
        }

        if ($bundle === null) {
            return array_keys($this->_attributes);
        }

        $names = [];
        foreach ($this->_attributes as $name => $attr) {
            if ($attr->get('bundle') === $bundle) {
                $names[] = $name;
            }
        }

        return $names;
    }

    /**
     * Calculates entity's primary key.
     *
     * If PK is composed of multiple columns they will be merged with `:` symbol.
     * For example, consider `Users` table with composed PK <nick, email>, then for
     * certain User entity this method could return:
     *
     *     john-locke:john@the-island.com
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity
     * @return string
     */
    protected function _getEntityId(EntityInterface $entity)
    {
        $pk = [];
        $keys = $this->_table->primaryKey();
        $keys = !is_array($keys) ? [$keys] : $keys;

        foreach ($keys as $key) {
            $pk[] = $entity->get($key);
        }
        return implode(':', $pk);
    }

    /**
     * Gets attribute's EAV type.
     *
     * @param string $attrName Attribute name
     * @return string Attribute's EAV type
     * @see \Eav\Model\Behavior\EavBehavior::_mapType()
     */
    protected function _getType($attrName)
    {
        return $this->_mapType($this->_attributes()[$attrName]->get('type'));
    }

    /**
     * Gets attribute's bundle.
     *
     * @param string $attrName Attribute name
     * @return string|null
     */
    protected function _getBundle($attrName)
    {
        return $this->_attributes()[$attrName]->get('bundle');
    }

    /**
     * Whether the given attribute can be used in WHERE clauses.
     *
     * @param string $attrName Attribute name
     * @return bool
     */
    protected function _isSearchable($attrName)
    {
        return (bool)$this->_attributes()[$attrName]->get('searchable');
    }

    /**
     * Maps schema data types to EAV's supported types.
     *
     * - datetime: "date", "time", "datetime"
     * - decimal: "dec", "float", "decimal"
     * - int: "integer", "int", "bool", "boolean"
     * - text: "text"
     * - varchar: "string", "varchar", "char"
     *
     * @param string $type A schema type. e.g. "string", "integer"
     * @return string A EAV type. Possible values are `datetime`, `decimal`, `int`,
     *  `text` or `varchar`
     */
    protected function _mapType($type)
    {
        switch ($type) {
            case 'date':
            case 'time':
            case 'datetime':
                return 'datetime';
            case 'dec':
            case 'decimal':
            case 'float':
                return 'decimal';
            case 'int':
            case 'integer':
            case 'boolean':
            case 'bool':
                return 'int';
            case 'text':
                return 'text';
            case 'string':
            case 'varchar':
            case 'char':
            default:
                return 'varchar';
        }
    }
}
