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

use Cake\Core\InstanceConfigTrait;
use Cake\Datasource\EntityInterface;
use Cake\Error\FatalErrorException;
use Cake\ORM\Query;
use Cake\ORM\Table;

/**
 * Every search engine must satisfy this interface.
 */
abstract class BaseEngine
{

    use InstanceConfigTrait;

    /**
     * The table being managed by this engine instance.
     *
     * @var \Cake\ORM\Table
     */
    protected $_table;

    /**
     * Default configuration array for this engine.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * Constructor
     *
     * @param \Cake\ORM\Table $table The table this engine is handling
     * @param array $config Configuration parameter for this engine
     */
    public function __construct(Table $table, array $config = [])
    {
        $this->_table = $table;
        $this->config($config);
    }

    /**
     * Indexes the given entity.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to be indexed
     * @return bool Success
     */
    public function index(EntityInterface $entity)
    {
        return false;
    }

    /**
     * Gets the index information of the given entity.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity for which retrieve
     *  its index
     * @return mixed Depending on the engine
     */
    public function get(EntityInterface $entity)
    {
        throw new FatalErrorException(__d('search', 'Method "get()" has not been overridden.'));
    }

    /**
     * Removes index for the given entity.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity which index will
     *  be deleted
     * @return bool Success
     */
    public function delete(EntityInterface $entity)
    {
        return false;
    }

    /**
     * Scopes the given query object.
     *
     * @param mixed $criteria A search-criteria compatible with this particular
     *  search engine.
     * @param \Cake\ORM\Query $query The query to be scope
     * @param array $options Any additional option the engine might accept
     * @return \Cake\ORM\Query Scoped query
     */
    public function search($criteria, Query $query, array $options = [])
    {
        return $query;
    }

    /**
     * Capture any invalid method invocation.
     *
     * @param string $method name of the method to be invoked
     * @param array $args List of arguments passed to the function
     * @return void
     */
    public function __call($method, $args)
    {
    }
}
