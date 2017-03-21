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

/**
 * Every query scoper must implement this interface.
 */
interface QueryScopeInterface
{

    /**
     * Scope constructor.
     *
     * @param \Cake\ORM\Table $table The table this behavior is attached to
     */
    public function __construct(Table $table);

    /**
     * Look for virtual columns in query's SQL statements.
     *
     * @param \Cake\ORM\Query $query The query to scope
     * @param string|null $bundle Consider attributes only for a specific bundle
     * @return \Cake\ORM\Query The modified query object
     */
    public function scope(Query $query, $bundle = null);
}
