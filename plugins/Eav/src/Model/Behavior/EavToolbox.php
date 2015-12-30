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

use Cake\Database\Type;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

/**
 * Support class for EAV behavior.
 */
class EavToolbox
{

    /**
     * List of accepted value types.
     *
     * @var array
     */
    public static $types = [
        'biginteger',
        'binary',
        'date',
        'float',
        'decimal',
        'integer',
        'time',
        'datetime',
        'timestamp',
        'uuid',
        'string',
        'text',
        'boolean',
    ];

    /**
     * The table being managed.
     *
     * @var \Cake\ORM\Table
     */
    protected $_table = null;

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
    protected $_attributes = [];

    /**
     * Constructor.
     *
     * @param \Cake\ORM\Table $table The table being handled
     */
    public function __construct(Table $table)
    {
        $this->_table = $table;
    }

    /**
     * Gets a clean column name from query expression.
     *
     * ### Example:
     *
     * ```php
     * EavToolbox::columnName('Tablename.some_column');
     * // returns "some_column"
     *
     * EavToolbox::columnName('my_column');
     * // returns "my_column"
     * ```
     *
     * @param string $column Column name from query
     * @return string
     */
    public static function columnName($column)
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
     * Checks if the provided entity has defined certain $property, regardless of
     * its value.
     *
     * @param \Cake\ORM\Entity $entity The entity to check
     * @param string $property The property name
     * @return bool True if exists
     */
    public function propertyExists(Entity $entity, $property)
    {
        $entityArray = $entity->toArray();
        return array_key_exists($property, $entityArray);
    }

    /**
     * Marshalls flat data into PHP objects.
     *
     * @param mixed $value The value to convert
     * @param string $type Type identifier, `integer`, `float`, etc
     * @return mixed Converted value
     */
    public function marshal($value, $type)
    {
        return Type::build($type)->marshal($value);
    }

    /**
     * Gets all attributes added to this table.
     *
     * @param string|null $bundle Get attributes within given bundle, or all of them
     *  regardless of the bundle if not provided
     * @return array List of attributes indexed by name (virtual column name)
     */
    public function attributes($bundle = null)
    {
        $key = empty($bundle) ? '@all' : $bundle;
        if (isset($this->_attributes[$key])) {
            return $this->_attributes[$key];
        }

        $this->_attributes[$key] = [];
        $conditions = ['EavAttributes.table_alias' => $this->_table->table()];
        if (!empty($bundle)) {
            $conditions['EavAttributes.bundle'] = $bundle;
        }

        $cacheKey = $this->_table->table() . '_' . $key;
        $attrs = TableRegistry::get('Eav.EavAttributes')
            ->find()
            ->cache($cacheKey, 'eav_table_attrs')
            ->where($conditions)
            ->all()
            ->toArray();
        foreach ($attrs as $attr) {
            $this->_attributes[$key][$attr->get('name')] = $attr;
        }

        return $this->attributes($bundle);
    }

    /**
     * Gets a list of attribute names.
     *
     * @param string $bundle Filter by bundle name
     * @return array
     */
    public function getAttributeNames($bundle = null)
    {
        $attributes = $this->attributes($bundle);
        return array_keys($attributes);
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
    public function getEntityId(EntityInterface $entity)
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
    public function getType($attrName)
    {
        return $this->mapType($this->attributes()[$attrName]->get('type'));
    }

    /**
     * Gets attribute's bundle.
     *
     * @param string $attrName Attribute name
     * @return string|null
     */
    public function getBundle($attrName)
    {
        return $this->attributes()[$attrName]->get('bundle');
    }

    /**
     * Whether the given attribute can be used in WHERE clauses.
     *
     * @param string $attrName Attribute name
     * @return bool
     */
    public function isSearchable($attrName)
    {
        return (bool)$this->attributes()[$attrName]->get('searchable');
    }

    /**
     * Maps schema data types to EAV's supported types.
     *
     * @param string $type A schema type. e.g. "string", "integer"
     * @return string A EAV type. Possible values are `datetime`, `binary`, `time`,
     *  `date`, `float`, `intreger`, `biginteger`, `text`, `string`, `boolean` or
     *  `uuid`
     */
    public function mapType($type)
    {
        switch ($type) {
            case 'float':
            case 'decimal':
                return 'float';
            case 'timestamp':
                return 'datetime';
            default:
                return $type;
        }
    }
}
