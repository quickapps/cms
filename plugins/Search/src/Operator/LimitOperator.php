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
use Search\Operator\BaseOperator;
use Search\Parser\TokenInterface;

/**
 * Handles "limits" search operator.
 *
 * For instance:
 *
 * ```
 * limit:<number>
 * ```
 *
 * Limits the number of results.
 */
class LimitOperator extends BaseOperator
{

    /**
     * {@inheritDoc}
     */
    public function scope(Query $query, TokenInterface $token)
    {
        if ($token->negated()) {
            return $query;
        }
        $value = intval($token->value());
        if ($value > 0) {
            $query->limit($value);
        }
        return $query;
    }
}
