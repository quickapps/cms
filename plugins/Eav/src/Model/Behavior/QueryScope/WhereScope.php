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
namespace Eav\Model\Behavior\QueryScope;

use Cake\Database\Expression\Comparison;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Eav\Model\Behavior\EavToolbox;
use Eav\Model\Behavior\QueryScope\QueryScopeInterface;

/**
 * Used by EAV Behavior to scope WHERE statements.
 */
class WhereScope implements QueryScopeInterface
{

    /**
     * The table being managed.
     *
     * @var \Cake\ORM\Table
     */
    protected $_table = null;

    /**
     * Instance of toolbox.
     *
     * @var \Eav\Model\Behavior\EavToolbox
     */
    protected $_toolbox = null;

    /**
     * {@inheritDoc}
     */
    public function __construct(Table $table)
    {
        $this->_table = $table;
        $this->_toolbox = new EavToolbox($table);
    }

    /**
     * {@inheritDoc}
     *
     * Look for virtual columns in query's WHERE clause.
     *
     * @param \Cake\ORM\Query $query The query to scope
     * @param string|null $bundle Consider attributes only for a specific bundle
     * @return \Cake\ORM\Query The modified query object
     */
    public function scope(Query $query, $bundle = null)
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

            $ids = $subQuery->all()->extract('entity_id')->toArray();
            $ids = empty($ids) ? ['-1'] : $ids;
            $expression->setField($field);
            $expression->setValue($ids);
            $expression->setOperator('IN');

            $class = new \ReflectionClass($expression);
            $property = $class->getProperty('_type');
            $property->setAccessible(true);
            $property->setValue($expression, 'string[]');
        });

        return $query;
    }

    /**
     * Creates a sub-query for matching virtual fields.
     *
     * @param \Cake\Database\Expression\Comparison $expression Expression to scope
     * @param string|null $bundle Consider attributes only for a specific bundle
     * @return \Cake\ORM\Query|bool False if not virtual field was found, or search
     *  feature was disabled for this field. The query object to use otherwise
     */
    protected function _virtualQuery($expression, $bundle = null)
    {
        if (!($expression instanceof Comparison)) {
            return false;
        }

        $field = $expression->getField();
        $column = is_string($field) ? $this->_toolbox->columnName($field) : '';
        if (empty($column) ||
            in_array($column, (array)$this->_table->schema()->columns()) || // ignore real columns
            !in_array($column, $this->_toolbox->getAttributeNames()) ||
            !$this->_toolbox->isSearchable($column)
        ) {
            return false;
        }

        $attr = $this->_toolbox->attributes($bundle)[$column];
        $value = $expression->getValue();
        $type = $this->_toolbox->getType($column);
        $conjunction = $expression->getOperator();
        $conditions = [
            'EavValues.eav_attribute_id' => $attr['id'],
            "EavValues.value_{$type} {$conjunction}" => $value,
        ];

        return TableRegistry::get('Eav.EavValues')
            ->find()
            ->select('EavValues.entity_id')
            ->where($conditions);
    }
}
