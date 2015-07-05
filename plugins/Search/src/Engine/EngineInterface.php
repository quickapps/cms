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
namespace Search\Engine;

use Cake\Datasource\EntityInterface;
use Cake\ORM\Query;
use Cake\ORM\Table;

/**
 * Every search engine must satisfy this interface.
 */
interface EngineInterface
{

    /**
     * Constructor
     *
     * @param \Cake\ORM\Table $table The table this engine is handling
     * @param array $config Configuration parameter for this engine
     */
    public function __construct(Table $table, array $config = []);

    /**
     * Indexes the given entity.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to be indexed
     * @return bool Success
     */
    public function index(EntityInterface $entity);

    /**
     * Gets the index information of the given entity.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity for which retrieve
     *  its index
     * @return mixed Depending on the engine
     */
    public function get(EntityInterface $entity);

    /**
     * Removes index for the given entity.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity which index will
     *  be deleted
     * @return bool Success
     */
    public function delete(EntityInterface $entity);

    /**
     * Scopes the given query object.
     *
     * @param mixed $criteria A search-criteria compatible with this particular
     *  search engine.
     * @param \Cake\ORM\Query $query The query to be scope
     * @return \Cake\ORM\Query Scoped query
     */
    public function search($criteria, Query $query);
}
