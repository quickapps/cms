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
namespace User\Controller\Admin;

use Field\Controller\FieldUIControllerTrait;
use User\Controller\AppController;

/**
 * Controller for Field UI Manager.
 *
 * Allows to attach, detach and configure Fields to `Users`.
 */
class FieldsController extends AppController
{

    use FieldUIControllerTrait {
        beforeRender as protected _beforeRender;
    }

    /**
     * Name of the table managed by Field UI API.
     *
     * @var string
     */
    protected $_manageTable = 'users';

    /**
     * Before every action of this controller.
     *
     * We sets appropriate breadcrumbs based on current action being requested.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @return void
     */
    public function beforeRender(\Cake\Event\Event $event)
    {
        $this->_beforeRender($event);
        $this->Breadcrumb
            ->push('/admin/user/manage')
            ->push(__d('node', "User's Fields"), '#');
    }
}
