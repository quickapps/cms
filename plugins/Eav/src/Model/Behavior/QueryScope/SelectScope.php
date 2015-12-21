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
use Eav\Model\Behavior\EavToolbox;
use Eav\Model\Behavior\QueryScope\QueryScopeInterface;

/**
 * Used by EAV Behavior to scope SELECT statements.
 */
class SelectScope implements QueryScopeInterface
{

    /**
     * The table being managed.
     *
     * @var \Cake\ORM\Table
     */
    protected $_table = null;

    /**
     * Instance of toolbox.
     *
     * @var \Eav\Model\Behavior\EavToolbox
     */
    protected $_toolbox = null;

    /**
     * {@inheritDoc}
     */
    public function __construct(Table $table)
    {
        $this->_table = $table;
        $this->_toolbox = new EavToolbox($table);
    }

    /**
     * {@inheritDoc}
     *
     * If "SELECT *" is performed
     * this behavior will fetch all virtual columns its values.
     *
     * Column aliasing are fully supported, allowing to create alias for virtual
     * columns. For instance:
     *
     * ```php
     * $article = $this->Articles->find()
     *     ->select(['aliased_virtual' => 'some_eav_column', 'body'])
     *     ->where(['Articles.id' => $id])
     *     ->limit(1)
     *     ->first();
     *
     * echo $article->get('aliased_virtual');
     * ```
     */
    public function scope(Query $query, $bundle = null)
    {
        $this->getVirtualColumns($query, $bundle);
        return $query;
    }

    /**
     * Gets a list of all virtual columns present in given $query's SELECT clause.
     *
     * This method will alter the given Query object removing any virtual column
     * present in its SELECT clause in order to avoid incorrect SQL statements.
     * Selected virtual columns should be fetched after query is executed using
     * mapReduce or similar.
     *
     * @param \Cake\ORM\Query $query The query object to be scoped
     * @param string|null $bundle Consider attributes only for a specific bundle
     * @return array List of virtual columns names
     */
    public function getVirtualColumns(Query $query, $bundle = null)
    {
        static $selectedVirtual = [];
        $cacheKey = spl_object_hash($query) . $bundle;
        if (isset($selectedVirtual[$cacheKey])) {
            return $selectedVirtual[$cacheKey];
        }

        $selectClause = (array)$query->clause('select');
        if (empty($selectClause)) {
            $selectedVirtual[$cacheKey] = array_keys($this->_toolbox->attributes($bundle));
            return $selectedVirtual[$cacheKey];
        }

        $selectedVirtual[$cacheKey] = [];
        $virtualColumns = array_keys($this->_toolbox->attributes($bundle));
        foreach ($selectClause as $index => $column) {
            list($table, $column) = pluginSplit($column);
            if ((empty($table) || $table == $this->_table->alias()) &&
                in_array($column, $virtualColumns)
            ) {
                $selectedVirtual[$cacheKey][$index] = $column;
                unset($selectClause[$index]);
            }
        }

        if (empty($selectClause) && !empty($selectedVirtual[$cacheKey])) {
            $selectClause[] = $this->_table->primaryKey();
        }

        $query->select($selectClause, true);
        return $selectedVirtual[$cacheKey];
    }
}
