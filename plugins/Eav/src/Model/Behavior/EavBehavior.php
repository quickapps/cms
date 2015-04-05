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
     * Default configuration.
     *
     * These are merged with user-provided configuration when the behavior is used.
     * Available options are:
     *
     * - `enabled`: Whether this behavior is enabled or not.
     * - `deleteUnlinked`: Whether to delete any unlinked value (values that belongs
     *   to an unexisting attribute).
     * - `attributes`: Array list of EAV attributes.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'enabled' => true,
        'deleteInvalids' => true,
        'attributes' => [],
    ];

    /**
     * Virtual attributes schema.
     *
     * @var \Cake\Database\Schema\Table
     */
    protected $_schema = null;

    /**
     * Constructor.
     *
     * @param \Cake\ORM\Table $table The table this behavior is attached to
     * @param array $config Configuration array for this behavior
     */
    public function __construct(Table $table, array $config = [])
    {
        $config['tableAlias'] = Inflector::underscore($table->alias());
        parent::__construct($table, $config);
        $this->_schema = clone $this->_table->schema();
        foreach ((array)$this->config('attributes') as $name => $attrs) {
            $this->_schema->addColumn($name, $attrs);
        }

        if ($this->config('deleteUnlinked')) {
            TableRegistry::get('Eav.EavValues')->deleteAll([
                'table_alias' => $this->config('tableAlias'),
                'attribute NOT IN' => array_keys($this->config('attributes'))
            ]);
        }
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
        if ((isset($options['eav']) && $options['eav'] === false) ||
            !$this->config('enabled')
        ) {
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
     * @return \Cake\ORM\Query The modified query object
     */
    protected function _scopeQuery(Query $query)
    {
        $whereClause = $query->clause('where');
        if (!$whereClause) {
            return $query;
        }

        $alias = $this->_table->alias();
        $pk = $this->_table->primaryKey();
        $conn = $query->connection(null);
        list(, $driverClass) = namespaceSplit(strtolower(get_class($conn->driver())));

        $whereClause->traverse(function ($expression) use ($pk, $alias, $driverClass) {
            if (!($subQuery = $this->_virtualQuery($expression))) {
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
     * @return \Cake\ORM\Query|bool False if not virtual field was found, or search
     *  feature was disabled for this field. The query object to use otherwise
     */
    protected function _virtualQuery($expression)
    {
        if (!($expression instanceof Comparison)) {
            return false;
        }

        $field = $this->_parseFieldName($expression->getField());
        $attributes = array_keys($this->config('attributes'));
        if (!in_array($field, $attributes)) {
            return false;
        }

        $value = $expression->getValue();
        $conjunction = $expression->getOperator();
        $type = $this->_getType($field);

        $subQuery = TableRegistry::get('Eav.EavValues')->find()
            ->select('EavValues.entity_id')
            ->where([
                "EavValues.attribute" => $field,
                "EavValues.value_{$type} {$conjunction}" => $value,
            ]);

        $subQuery->where(['EavValues.table_alias' => $this->config('tableAlias')]);
        return $subQuery;
    }

    /**
     * Gets a clean field name from query expression.
     *
     * ### Example:
     *
     * ```php
     * $this->_parseFieldName('Tablename.:virtual');
     * // returns ":virtual"
     *
     * $this->_parseFieldName('Tablename.non_virtual');
     * // returns "non_virtual"
     *
     * $this->_parseFieldName('my_column');
     * // returns "my_column"
     * ```
     *
     * @param string $column Column name from query
     * @return string
     */
    protected function _parseFieldName($column)
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
        if (!$this->config('enabled')) {
            return;
        }

        $Values = TableRegistry::get('Eav.EavValues');
        $pk = $this->_table->primaryKey();
        foreach ($this->config('attributes') as $name => $attrs) {
            if ($entity->has($name) && $entity->has($pk)) {
                $type = $this->_getType($name);
                $value = $Values
                    ->find()
                    ->where([
                        'table_alias' => $this->config('tableAlias'),
                        'entity_id' => (string)$entity->get($pk),
                        'attribute' => $name,
                    ])
                    ->limit(1)
                    ->first();

                if (!$value) {
                    $value = $Values->newEntity([
                        'table_alias' => $this->config('tableAlias'),
                        'entity_id' => (string)$entity->get($pk),
                        'attribute' => $name,
                    ]);
                }

                foreach (['datetime', 'decimal', 'int', 'text', 'varchar'] as $suffix) {
                    if ($type != $suffix) {
                        $value->set("value_{$suffix}", null);
                    } else {
                        $value->set("value_{$suffix}", $entity->get($name));
                    }
                }
                $Values->save($value);
            }
        }
    }

    /**
     * After an entity was removed from database.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Datasource\EntityInterface $entity The entity that was deleted
     * @param array $options Additional options given as an array
     * @throws \Cake\Error\FatalErrorException When using this behavior in non-atomic mode
     * @return void
     */
    public function afterDelete(Event $event, EntityInterface $entity, $options)
    {
        if (!$this->config('enabled')) {
            return;
        }

        if (!$options['atomic']) {
            throw new FatalErrorException(__d('field', 'Entities in fieldable tables can only be deleted using transactions. Set [atomic = true]'));
        }

        $pk = $this->_table->primaryKey();
        TableRegistry::get('Eav.Values')->deleteAll([
            'table_alias' => $this->config('tableAlias'),
            'entity_id' => (string)$entity->get($pk),
        ]);
    }

    /**
     * The method which actually fetches custom fields.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity where to fetch fields
     * @return \Cake\Datasource\EntityInterface
     */
    public function attachEntityAttributes(EntityInterface $entity)
    {
        $Values = TableRegistry::get('Eav.Values');
        $pk = $this->_table->primaryKey();
        foreach ($this->config('attributes') as $name => $attrs) {
            if (!$entity->has($name) && $entity->has($pk)) {
                $type = $this->_getType($name);
                $value = $Values
                    ->find()
                    ->where([
                        'table_alias' => $this->config('tableAlias'),
                        'entity_id' => (string)$entity->get($pk),
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
     * Guess attribute type mapping.
     *
     * @param string $attrName Attribute name
     * @return string Possible values are 'datetime', 'decimal', 'int', 'text' or
     *  'varchar'
     */
    protected function _getType($attrName)
    {
        $schema = $this->_schema->column($attrName);
        $type = !empty($schema['type']) ? $schema['type'] : null;

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
