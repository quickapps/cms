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
namespace Search\Model\Search;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Search\Operator;

/**
 * Handles date ranges operators.
 *
 * For instance:
 *
 * ```
 * created:<date>
 * created:<date1>..<date2>
 * ```
 *
 * Dates must be in YEAR-MONTH-DATE format. e.g. `2014-12-30`
 */
class DateOperator extends Operator
{

    /**
     * Default configuration for this operator.
     *
     * ### Options:
     *
     * - field: Name of the table column which should be scoped, defaults to
     *   `created`. This column should be of type Date or DateTime.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'field' => 'created',
    ];

    /**
     * {@inheritdoc}
     */
    public function scope(Query $query, $value, $negate, $orAnd)
    {
        $tableAlias = $this->_table->alias();
        $column = $this->config('field');
        if (strpos($value, '..') !== false) {
            list($dateLeft, $dateRight) = explode('..', $value);
        } else {
            $dateLeft = $dateRight = $value;
        }

        $dateLeft = preg_replace('/[^0-9\-]/', '', $dateLeft);
        $dateRight = preg_replace('/[^0-9\-]/', '', $dateRight);
        $range = [$dateLeft, $dateRight];
        foreach ($range as &$date) {
            $parts = explode('-', $date);
            $year = !empty($parts[0]) ? intval($parts[0]) : date('Y');
            $month = !empty($parts[1]) ? intval($parts[1]) : 1;
            $day = !empty($parts[2]) ? intval($parts[2]) : 1;

            $year = (1 <= $year && $year <= 32767) ? $year : date('Y');
            $month = (1 <= $month && $month <= 12) ? $month : 1;
            $day = (1 <= $month && $month <= 31) ? $day : 1;

            $date = date('Y-m-d', strtotime("{$year}-{$month}-{$day}"));
        }

        list($dateLeft, $dateRight) = $range;
        if (strtotime($dateLeft) > strtotime($dateRight)) {
            $tmp = $dateLeft;
            $dateLeft = $dateRight;
            $dateRight = $tmp;
        }

        if ($dateLeft !== $dateRight) {
            $not = $negate ? ' NOT' : '';
            $conditions = [
                "AND{$not}" => [
                    "{$tableAlias}.{$column} >=" => $dateLeft,
                    "{$tableAlias}.{$column} <=" => $dateRight,
                ]
            ];
        } else {
            $cmp = $negate ? '<=' : '>=';
            $conditions = ["{$tableAlias}.{$column} {$cmp}" => $dateLeft];
        }

        if ($orAnd === 'or') {
            $query->orWhere($conditions);
        } elseif ($orAnd === 'and') {
            $query->andWhere($conditions);
        } else {
            $query->where($conditions);
        }

        return $query;
    }
}
