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

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Eav\Model\Behavior\EavToolbox;
use Eav\Model\Behavior\QueryScope\QueryScopeInterface;

/**
 * Used by EAV Behavior to scope ORDER BY statements.
 */
class OrderScope implements QueryScopeInterface
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
        $orderClause = $query->clause('order');
        if (!$orderClause) {
            return $query;
        }

        $class = new \ReflectionClass($orderClause);
        $property = $class->getProperty('_conditions');
        $property->setAccessible(true);
        $conditions = $property->getValue($orderClause);

        foreach ($conditions as $column => $direction) {
            if (empty($column) ||
                in_array($column, (array)$this->_table->schema()->columns()) || // ignore real columns
                !in_array($column, $this->_toolbox->getAttributeNames())
            ) {
                continue;
            }

            $conditions['(' . $this->_subQuery($column, $bundle) . ')'] = $direction;
            unset($conditions[$column]);
        }

        $property->setValue($orderClause, $conditions);
        return $query;
    }

    /**
     * Generates a SQL sub-query for replacing in ORDER BY clause.
     *
     * @param string $column Name of the column being replaced by this sub-query
     * @param string|null $bundle Consider attributes only for a specific bundle
     * @return string SQL sub-query statement
     */
    protected function _subQuery($column, $bundle = null)
    {
        $alias = $this->_table->alias();
        $pk = $this->_table->primaryKey();
        $type = $this->_toolbox->getType($column);
        $subConditions = [
            'EavAttribute.table_alias' => $this->_table->table(),
            'EavValues.entity_id' => "{$alias}.{$pk}",
            'EavAttribute.name' => $column,
        ];

        if (!empty($bundle)) {
            $subConditions['EavAttribute.bundle'] = $bundle;
        }

        $subQuery = TableRegistry::get('Eav.EavValues')
            ->find()
            ->contain(['EavAttribute'])
            ->select(["EavValues.value_{$type}"])
            ->where($subConditions)
            ->sql();

        return str_replace([':c0', ':c1', ':c2', ':c3'], [
            '"' . $this->_table->table() . '"',
            "{$alias}.{$pk}",
            '"' . $column . '"',
            '"' . $bundle . '"'
        ], $subQuery);
    }
}
