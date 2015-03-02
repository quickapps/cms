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
        $range = $this->_parseRange($value);

        if ($range['lower'] !== $range['upper']) {
            $not = $negate ? ' NOT' : '';
            $conditions = [
                "AND{$not}" => [
                    "{$tableAlias}.{$column} >=" => $range['lower'],
                    "{$tableAlias}.{$column} <=" => $range['upper'],
                ]
            ];
        } else {
            $cmp = $negate ? '<=' : '>=';
            $conditions = ["{$tableAlias}.{$column} {$cmp}" => $range['lower']];
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

    /**
     * Parses and extracts lower and upper date values from the given range given
     * as `lower..upper`.
     *
     * Dates must be in YEAR-MONTH-DATE format. e.g. `2014-12-30`. It automatically
     * reorder dates if they are given in inversed order (upper..lower).
     *
     * @param string $value A date range given as `<dateLower>..<dateUpper>`. For
     *  instance. `2014-12-30..2015-12-30`
     * @return array Associative array with two keys: `lower` and `upper`, returned
     *  dates are fully PHP compliant
     */
    protected function _parseRange($value)
    {
        if (strpos($value, '..') !== false) {
            list($lower, $upper) = explode('..', $value);
        } else {
            $lower = $upper = $value;
        }

        $lower = $this->_normalize($lower);
        $upper = $this->_normalize($upper);

        if (strtotime($lower) > strtotime($upper)) {
            list($lower, $upper) = [$upper, $lower];
        }

        return [
            'lower' => $lower,
            'upper' => $upper,
        ];
    }

    /**
     * Normalizes the given date.
     *
     * @param string $date Date to normalize
     * @return string Date formated as `Y-m-d`
     */
    protected function _normalize($date)
    {
        $date = preg_replace('/[^0-9\-]/', '', $date);
        $parts = explode('-', $date);
        $year = date('Y');
        $month = 1;
        $day = 1;

        if (!empty($parts[0]) &&
            1 <= intval($parts[0]) &&
            intval($parts[0]) <= 32767
        ) {
            $year = intval($parts[0]);
        }

        if (!empty($parts[1]) &&
            1 <= intval($parts[1]) &&
            intval($parts[1]) <= 12
        ) {
            $month = intval($parts[1]);
        }

        if (!empty($parts[2]) &&
            1 <= intval($parts[2]) &&
            intval($parts[2]) <= 31
        ) {
            $day = intval($parts[2]);
        }

        return date('Y-m-d', strtotime("{$year}-{$month}-{$day}"));
    }
}
