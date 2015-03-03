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
 * Generic handler for any search operator.
 *
 * For instance:
 *
 * ```
 * operator_name:operator_value
 * ```
 */
class GenericOperator extends Operator
{

    /**
     * Default configuration for this operator.
     *
     * ### Options:
     *
     * - field: Name of the table column which should be scoped.
     *
     * - conjunction: Defaults to `auto`, accepted values are:
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
     * Note that wildcard will not works when using `IN` conjunction.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'field' => false,
        'conjunction' => 'auto',
        'inSlice' => 5,
    ];

    /**
     * {@inheritdoc}
     */
    public function scope(Query $query, Token $token)
    {
        $field = $this->config('field');
        $value = $token->value();

        if (!$field || empty($value)) {
            return $query;
        }

        $conjunction = $this->_conjunction($value);
        $tableAlias = $this->_table->alias();
        $conditions = ["{$tableAlias}.{$field} {$conjunction}" => $value];

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
     * Calculates the proper conjunction to used based on the given value, and
     * alters the given value to match this conjunction.
     *
     * @param string &$value Value to use when calculating the conjunction, it will
     *  be altered if needed in order to match the resulting conjunction
     * @return string
     */
    protected function _conjunction(&$value)
    {
        $conjunction = strtolower($this->config('conjunction'));
        if ($conjunction == 'auto') {
            $conjunction = strpos($value, ',') ? 'in' : 'like';
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

        return $conjunction;
    }
}
