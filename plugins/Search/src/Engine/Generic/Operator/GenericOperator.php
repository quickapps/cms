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
namespace Search\Engine\Generic\Operator;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Search\Engine\Generic\Operator\BaseOperator;
use Search\Engine\Generic\Token;

/**
 * Generic handler for any search operator.
 *
 * For instance:
 *
 * ```
 * operator_name:operator_value
 * ```
 */
class GenericOperator extends BaseOperator
{

    /**
     * Default configuration for this operator.
     *
     * ### Options:
     *
     * - field: Name of the table column which should be scoped.
     *
     * - conjunction: Indicates which conjunction type should be used when scoping
     *   the column. Defaults to `auto`, accepted values are:
     *
     *   - `LIKE`: Useful when matching string values, accepts wildcard `*` for
     *     matching "any" sequence of chars and `!` for matching any single char.
     *     e.g. `author:c*` or `author:ca!`, mixing: `author:c!r*`.
     *
     *   - `IN`: Useful when operators accepts a list of possible values.
     *     e.g. `author:chris,carter,lisa`.
     *
     *   - `=`: Used for strict matching.
     *
     *   - `<>`: Used for strict matching.
     *
     *   - `auto`: Auto detects, it will use `IN` if comma symbol is found in
     *     the given value, `LIKE` will be used otherwise. e.g. For
     *     `author:chris,peter` the `IN` conjunction will be used, for
     *     `author:chris` the `LIKE` conjunction will be used instead.
     *
     * - inSlice: Maximum number of elements when using `IN` conjunction,
     *   defaults to 5. Used for security proposes.
     *
     * Note that wildcard will NOT works when using `IN` conjunction.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'field' => false,
        'conjunction' => 'auto',
        'inSlice' => 5,
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

        list($conjunction, $value) = $this->_prepareConjunction($token);
        $tableAlias = $this->_table->alias();
        $conditions = [
            "{$tableAlias}.{$column} {$conjunction}" => $value,
        ];

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
     * Calculates the conjunction to use and the value to use with such conjunction
     * based on the given token.
     *
     * @param \Search\Token $token Token for which calculating the conjunction
     * @return array Numeric index array, where the first key (0) is the
     *  conjunction, and the second (1) is the value.
     */
    protected function _prepareConjunction($token)
    {
        $value = $token->value();
        $conjunction = strtolower($this->config('conjunction'));
        if ($conjunction == 'auto') {
            $conjunction = strpos($value, ',') !== false ? 'in' : 'like';
        }

        if ($conjunction == 'in') {
            $value = array_slice(explode(',', $value), 0, $this->config('inSlice'));
            $conjunction = $token->negated() ? 'NOT IN' : 'IN';
        } elseif ($conjunction == 'like') {
            $value = str_replace(['*', '!'], ['%', '_'], $value);
            $conjunction = $token->negated() ? 'NOT LIKE' : 'LIKE';
        } elseif ($conjunction == '=') {
            $conjunction = $token->negated() ? '<>' : '';
        } elseif ($conjunction == '<>') {
            $conjunction = $token->negated() ? '' : '<>';
        }

        return [$conjunction, $value];
    }
}
