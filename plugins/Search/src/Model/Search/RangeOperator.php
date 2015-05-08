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
use Search\Token;

/**
 * Handles range-based operators. Values must be separated using ".." characters and
 * follow the pattern "<lower>..<upper>" For instance:
 *
 * ```
 * age:15..21
 * ```
 *
 * This class can handle ranges given in incorrect order `<upper>..<lower>`. For
 * instance: `age:21..15`. In these cases this class will treat values as numeric
 * values for determinate the correct order.
 */
class RangeOperator extends Operator
{

    /**
     * Default configuration for this operator.
     *
     * ### Options:
     *
     * - field: Name of the table column which should be scoped.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'field' => false,
    ];

    /**
     * {@inheritDoc}
     */
    public function scope(Query $query, Token $token)
    {
        $column = $this->config('field');
        $value = $token->value();
        if (!$column || empty($value)) {
            return $query;
        }

        $tableAlias = $this->_table->alias();
        $range = $this->_parseRange($token->value());

        if ($range['lower'] !== $range['upper']) {
            $conjunction = $token->negated() ? 'AND NOT' : 'AND';
            $conditions = [
                "{$conjunction}" => [
                    "{$tableAlias}.{$column} >=" => $range['lower'],
                    "{$tableAlias}.{$column} <=" => $range['upper'],
                ]
            ];
        } else {
            $cmp = $token->negated() ? '<=' : '>=';
            $conditions = ["{$tableAlias}.{$column} {$cmp}" => $range['lower']];
        }

        if ($token->where() === 'or') {
            $query->orWhere($conditions);
        } elseif ($token->where() === 'and') {
            $query->andWhere($conditions);
        } else {
            $query->where($conditions);
        }

        return $query;
    }

    /**
     * Parses and extracts lower and upper values from the given range given
     * as `lower..upper`.
     *
     * @param string $value A values range given as `<lowerValue>..<upperValue>`. For
     *  instance. `100..200`
     * @return array Associative array with two keys: `lower` and `upper`
     */
    protected function _parseRange($value)
    {
        if (strpos($value, '..') !== false) {
            list($lower, $upper) = explode('..', $value);
        } else {
            $lower = $upper = $value;
        }

        if (intval($lower) > intval($upper)) {
            list($lower, $upper) = [$upper, $lower];
        }

        return [
            'lower' => $lower,
            'upper' => $upper,
        ];
    }
}
