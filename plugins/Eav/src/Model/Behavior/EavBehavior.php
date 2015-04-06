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
use Cake\Database\Schema\Table as Schema;
use Cake\Datasource\EntityInterface;
use Cake\Error\FatalErrorException;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
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
 * $this->addBehavior('Eav.Eav', [
 *     'attributes' => [
 *         'user_age' => ['type' => 'integer'],
 *         'user_phone' => ['type' => 'string'],
 *     ]
 * ]);
 * ```
 *
 * Using virtual attributes in WHERE clauses:
 *
 * ```php
 * $users = $this->Users->find()
 *     ->where(['user_age >' => 18])
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
     * @var array
     */
    protected $_attributes = [];

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

    /**
     * Constructor.
     *
     * @param \Cake\ORM\Table $table The table this behavior is attached to
     * @param array $config Configuration array for this behavior
     */
    public function __construct(Table $table, array $config = [])
    {
        $this->_tableAlias = Inflector::underscore($table->alias());
        $this->_initModels();
        parent::__construct($table, $config);
        $this->_fetchAttributes();
    }

    /**
     * Modifies the query object in order to merge custom fields records into each
     * entity under the `_fields` property.
     *
     * @param \Cake\Event\Event $event The beforeFind event that was triggered
     * @param \Cake\ORM\Query $query The original query to modify
     * @param \ArrayObject $options Additional options given as an array
     * @param bool $primary Whether this find is a primary query or not
     * @return bool|null
     */
    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        if ((isset($options['eav']) && $options['eav'] === false)) {
            return true;
        }

        $query = $this->_scopeQuery($query);
        $query->formatResults(function ($results) use ($event, $options, $primary) {
            return $results->map(function ($entity) use ($event, $options, $primary) {
                if ($entity instanceof EntityInterface) {
                    $entity = $this->attachEntityAttributes($entity);
                }
                return $entity;
            });
        });
    }

    /**
     * Look for `:<machine-name>` patterns in query's WHERE clause.
     *
     * Allows to search entities using custom fields as conditions in WHERE clause.
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

        $alias = $this->_table->alias();
        $pk = $this->_table->primaryKey();
        $conn = $query->connection(null);
        list(, $driverClass) = namespaceSplit(strtolower(get_class($conn->driver())));

        $whereClause->traverse(function ($expression) use ($pk, $alias, $driverClass, $bundle) {
            if (!($subQuery = $this->_virtualQuery($expression, $bundle))) {
                return;
            }

            switch ($driverClass) {
                case 'sqlite':
                    $field = "({$alias}.{$pk} || '')";
                    break;
                case 'mysql':
                case 'postgres':
                case 'sqlserver':
                default:
                    $field = "CONCAT({$alias}.{$pk}, '')";
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
        $attributes = array_keys($this->_attributes);
        if (empty($column) ||
            !in_array($column, $attributes) ||
            !$this->_isSearchable($column)
        ) {
            return false;
        }

        $value = $expression->getValue();
        $type = $this->_getType($column);
        $bundle = $bundle !== null ? $bundle : $this->_getBundle($column);
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
        $pk = $this->_table->primaryKey();
        foreach ($this->_attributes as $name => $attr) {
            if ($entity->has($name) && $entity->has($pk)) {
                $type = $this->_getType($name);
                $value = $this->Values
                    ->find()
                    ->where([
                        'table_alias' => $this->_tableAlias,
                        'bundle' => $this->_getBundle($name),
                        'entity_id' => (string)$entity->get($pk),
                        'attribute' => $name,
                    ])
                    ->limit(1)
                    ->first();

                if (!$value) {
                    $value = $this->Values->newEntity([
                        'eav_attribute_id' => $attr->get('id'),
                        'entity_id' => (string)$entity->get($pk),
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

        $pk = $this->_table->primaryKey();
        $valuesToDelete = $this->Values
            ->find()
            ->contain(['EavAttribute'])
            ->where([
                'EavAttribute.table_alias' => $this->_tableAlias,
                'EavValues.entity_id' => $entity->get($pk),
            ])
            ->all();

        foreach ($valuesToDelete as $value) {
            $this->Values->delete($value);
        }
    }

    /**
     * The method which actually fetches custom fields.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity where to fetch fields
     * @return \Cake\Datasource\EntityInterface
     */
    public function attachEntityAttributes(EntityInterface $entity)
    {
        $pk = $this->_table->primaryKey();
        foreach ($this->_attributes as $name => $attr) {
            if (!$entity->has($name) && $entity->has($pk)) {
                $type = $this->_getType($name);
                $value = $this->Values
                    ->find()
                    ->select("value_{$type}")
                    ->where([
                        'EavAttribute.table_alias' => $this->_tableAlias,
                        'EavAttribute.bundle' => $this->_getBundle($name),
                        'EavAttribute.attribute' => $name,
                        'EavValues.entity_id' => (string)$entity->get($pk),
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
            $this->_fetchAttributes();
        }

        if ($bundle === null) {
            return array_keys($this->_attributes);
        }

        $names = [];
        foreach ($this->_attributes as $name => $attr) {
            if ($attr->get('bundle') === $bundle) {
                $name[] = $name;
            }
        }

        return $names;
    }

    /**
     * Fetch attributes information for the table. This includes attributes across
     * all bundles.
     *
     * @return void
     */
    protected function _fetchAttributes()
    {
        if (!empty($this->_attributes)) {
            return;
        }

        $attrs = $this->Attributes
            ->find()
            ->where(['EavAttributes.table_alias' => $this->_tableAlias])
            ->all()
            ->toArray();
        foreach ($attrs as $attr) {
            $this->_attributes[$attr->get('name')] = $attr;
        }
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
        return $this->_mapType($this->_attributes[$attrName]->get('type'));
    }

    /**
     * Gets attribute's bundle.
     *
     * @param string $attrName Attribute name
     * @return string|null
     */
    protected function _getBundle($attrName)
    {
        return $this->_attributes[$attrName]->get('bundle');
    }

    /**
     * Whether the given attribute can be used in WHERE clauses.
     *
     * @param string $attrName Attribute name
     * @return bool
     */
    protected function _isSearchable($attrName)
    {
        return (bool)$this->_attributes[$attrName]->get('searchable');
    }

    /**
     * Maps schema types to EAV supported types.
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
                return 'decimal';
            case 'int':
            case 'integer':
                return 'int';
            case 'text':
                return 'text';
            case 'string':
            case 'varchar':
            default:
                return 'varchar';
        }
    }
}
