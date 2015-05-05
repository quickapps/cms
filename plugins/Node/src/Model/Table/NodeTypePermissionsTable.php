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
namespace Node\Model\Table;

use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Table;
use \ArrayObject;

/**
 * Represents "node_type_permissions" database table.
 *
 */
class NodeTypePermissionsTable extends Table
{

    /**
     * {@inheritDoc}
     */
    protected $_table = 'node_type_permissions';

    /**
     * {@inheritDoc}
     */
    public function initialize(array $config)
    {
        $this->belongsTo('Node.NodeTypes');
        $this->belongsTo('User.Roles');
    }
}
