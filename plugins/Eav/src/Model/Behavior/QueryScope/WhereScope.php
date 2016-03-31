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

use Cake\Database\ExpressionInterface;
use Cake\Database\Expression\Comparison;
use Cake\Database\Expression\UnaryExpression;
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

        $whereClause->traverse(function ($expression) use ($bundle, $query) {
            if ($expression instanceof ExpressionInterface) {
                $expression = $this->_alterExpression($expression, $bundle, $query);
            }
        });

        return $query;
    }

    /**
     * Analyzes the given WHERE expression, looks for virtual columns and alters
     * the expressions according.
     *
     * @param \Cake\Database\ExpressionInterface $expression Expression to scope
     * @param string $bundle Consider attributes only for a specific bundle
     * @param \Cake\ORM\Query $query The query instance this expression comes from
     * @return \Cake\Database\ExpressionInterface The altered expression (or not)
     */
    protected function _alterExpression(ExpressionInterface $expression, $bundle, Query $query)
    {
        if ($expression instanceof Comparison) {
            $expression = $this->_alterComparisonExpression($expression, $bundle, $query);
        } elseif ($expression instanceof UnaryExpression) {
            // TODO: unary expressions scoping
        }

        return $expression;
    }

    /**
     * Analyzes the given comparison expression and alters it according.
     *
     * @param \Cake\Database\Expression\Comparison $expression Comparison expression
     * @param string $bundle Consider attributes only for a specific bundle
     * @param \Cake\ORM\Query $query The query instance this expression comes from
     * @return \Cake\Database\Expression\Comparison Scoped expression (or not)
     */
    protected function _alterComparisonExpression(Comparison $expression, $bundle, Query $query)
    {
        $field = $expression->getField();
        $column = is_string($field) ? $this->_toolbox->columnName($field) : '';

        if (empty($column) ||
            in_array($column, (array)$this->_table->schema()->columns()) || // ignore real columns
            !in_array($column, $this->_toolbox->getAttributeNames()) ||
            !$this->_toolbox->isSearchable($column) // ignore no searchable virtual columns
        ) {
            // nothing to alter
            return $expression;
        }

        $attr = $this->_toolbox->attributes($bundle)[$column];
        $value = $expression->getValue();
        $type = $this->_toolbox->getType($column);
        $conjunction = $expression->getOperator();
        $conditions = [
            'EavValues.eav_attribute_id' => $attr['id'],
            "EavValues.value_{$type} {$conjunction}" => $value,
        ];

        // subquery scope
        $subQuery = TableRegistry::get('Eav.EavValues')
            ->find()
            ->select('EavValues.entity_id')
            ->where($conditions);

        // some variables
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

        return $expression;
    }
}
