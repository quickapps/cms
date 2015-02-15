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
namespace Search\Operator;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Search\Operator\Operator;

/**
 * Handles "order" search operator.
 *
 *     order:<field1>,<asc|desc>;<field2>,<asc,desc>; ...
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
     * {@inheritdoc}
     */
    public public function scope(Query $query, $value, $negate, $orAnd)
    {
        if ($negate || empty($this->config('fields'))) {
            return $query;
        }

        $fields = $this->config('fields');
        $tableAlias = $this->_table->alias();
        $value = strtolower($value);
        $split = explode(';', $value);

        if (is_string($fields)) {
            $fields = [$fields];
        }

        foreach ($split as $segment) {
            $parts = explode(',', $segment);
            if (count($parts) === 2 &&
                in_array($parts[1], ['asc', 'desc']) &&
                in_array($parts[0], $fields)
            ) {
                $field = $parts[0];
                $dir = $parts[1];
                $query->order(["{$tableAlias}.{$field}" => $dir]);
            }
        }

        return $query;
    }
}
