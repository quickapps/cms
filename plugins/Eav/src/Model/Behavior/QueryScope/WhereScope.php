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
use Cake\Database\Expression\IdentifierExpression;
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

        $whereClause->traverse(function (&$expression) use ($bundle, $query) {
            if ($expression instanceof ExpressionInterface) {
                $expression = $this->_inspectExpression($expression, $bundle, $query);
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
    protected function _inspectExpression(ExpressionInterface $expression, $bundle, Query $query)
    {
        if ($expression instanceof Comparison) {
            $expression = $this->_inspectComparisonExpression($expression, $bundle, $query);
        } elseif ($expression instanceof UnaryExpression) {
            $expression = $this->_inspectUnaryExpression($expression, $bundle, $query);
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
    protected function _inspectComparisonExpression(Comparison $expression, $bundle, Query $query)
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
        $pk = $this->_tablePrimaryKey();
        $driverClass = $this->_driverClass($query);

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

        // compile query, faster than raw subquery in most cases
        $ids = $subQuery->all()->extract('entity_id')->toArray();
        $ids = empty($ids) ? ['-1'] : $ids;
        $expression->setField($field);
        $expression->setValue($ids);
        $expression->setOperator('IN');

        $class = new \ReflectionClass($expression);
        $property = $class->getProperty('_type');
        $property->setAccessible(true);
        $property->setValue($expression, 'string');

        $property = $class->getProperty('_isMultiple');
        $property->setAccessible(true);
        $property->setValue($expression, true);

        return $expression;
    }

    /**
     * Analyzes the given unary expression and alters it according.
     *
     * @param \Cake\Database\Expression\UnaryExpression $expression Unary expression
     * @param string $bundle Consider attributes only for a specific bundle
     * @param \Cake\ORM\Query $query The query instance this expression comes from
     * @return \Cake\Database\Expression\UnaryExpression Scoped expression (or not)
     */
    protected function _inspectUnaryExpression(UnaryExpression $expression, $bundle, Query $query)
    {
        $class = new \ReflectionClass($expression);
        $property = $class->getProperty('_value');
        $property->setAccessible(true);
        $value = $property->getValue($expression);

        if ($value instanceof IdentifierExpression) {
            $field = $value->getIdentifier();
            $column = is_string($field) ? $this->_toolbox->columnName($field) : '';

            if (empty($column) ||
                in_array($column, (array)$this->_table->schema()->columns()) || // ignore real columns
                !in_array($column, $this->_toolbox->getAttributeNames($bundle)) ||
                !$this->_toolbox->isSearchable($column) // ignore no searchable virtual columns
            ) {
                // nothing to alter
                return $expression;
            }

            $pk = $this->_tablePrimaryKey();
            $driverClass = $this->_driverClass($query);

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

            $attr = $this->_toolbox->attributes($bundle)[$column];
            $type = $this->_toolbox->getType($column);
            $subQuery = TableRegistry::get('Eav.EavValues')
                ->find()
                ->select("EavValues.value_{$type}")
                ->where([
                    'EavValues.entity_id' => $field,
                    'EavValues.eav_attribute_id' => $attr['id']
                ])
                ->sql();
            $subQuery = str_replace([':c0', ':c1'], [$field, $attr['id']], $subQuery);
            $property->setValue($expression, "({$subQuery})");
        }

        return $expression;
    }

    /**
     * Gets table's PK as an array.
     *
     * @return array
     */
    protected function _tablePrimaryKey()
    {
        $alias = $this->_table->alias();
        $pk = $this->_table->primaryKey();

        if (!is_array($pk)) {
            $pk = [$pk];
        }

        $pk = array_map(function ($key) use ($alias) {
            return "{$alias}.{$key}";
        }, $pk);

        return $pk;
    }

    /**
     * Gets the name of the class driver used by the given $query to access the DB.
     *
     * @param \Cake\ORM\Query $query The query to inspect
     * @return string Lowercased drive name. e.g. `mysql`
     */
    protected function _driverClass(Query $query)
    {
        $conn = $query->connection(null);
        list(, $driverClass) = namespaceSplit(strtolower(get_class($conn->driver())));
        return $driverClass;
    }
}
