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
 * Handles "order by" operators.
 *
 * For instance:
 *
 * ```
 * order:<field1>,<asc|desc>;<field2>,<asc,desc>; ...
 * ```
 *
 * Orders the resulting entities.
 */
class OrderOperator extends Operator
{

    /**
     * Default configuration for this operator.
     *
     * ### Options:
     *
     * - fields: List of table columns which results can be sorted by. It can be
     *   either a string for indicate a single column, or an array of column
     *   names.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'fields' => [],
    ];

    /**
     * {@inheritDoc}
     */
    public function scope(Query $query, Token $token)
    {
        if ($token->negated() || empty($this->config('fields'))) {
            return $query;
        }

        $tableAlias = $this->_table->alias();
        $fields = $this->config('fields');
        $value = strtolower($token->value());

        if (is_string($fields)) {
            $fields = [$fields];
        }

        foreach (explode(';', $value) as $segment) {
            $parts = explode(',', $segment);
            if (in_array($parts[0], $fields)) {
                $dir = empty($parts[1]) || !in_array($parts[1], ['asc', 'desc']) ? 'asc' : $parts[1];
                $query->order(["{$tableAlias}.{$parts[0]}" => $dir]);
            }
        }

        return $query;
    }
}
