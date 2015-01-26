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

use User\Controller\AppController;

/**
 * Roles manager controller.
 *
 * Provides full CRUD for roles.
 */
class RolesController extends AppController
{

    /**
     * Shows a list of all available roles.
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('User.Acos');
        $roles = $this->Acos->Roles->find()->all();

        $this->set(compact('roles'));
        $this->Breadcrumb
            ->push('/admin/user/manage')
            ->push(__d('user', 'Roles'), ['plugin' => 'User', 'controller' => 'roles', 'action' => 'index']);
    }

    /**
     * Add a new role.
     *
     * @return void
     */
    public function add()
    {
        $this->Breadcrumb
            ->push('/admin/user/manage')
            ->push(__d('user', 'Roles'), ['plugin' => 'User', 'controller' => 'roles', 'action' => 'index'])
            ->push(__d('user', 'Add new role'), '');
    }

    /**
     * Edits the given role.
     *
     * @param int $id User's ID
     * @return void
     */
    public function edit($id)
    {
        $this->Breadcrumb
            ->push('/admin/user/manage')
            ->push(__d('user', 'Roles'), ['plugin' => 'User', 'controller' => 'roles', 'action' => 'index'])
            ->push(__d('user', 'Edit role'), '');
    }

    /**
     * Removes the given role.
     *
     * @param int $id User's ID
     * @return void Redirects to previous page
     */
    public function delete($id)
    {
        $this->loadModel('User.Roles');
        $role = $this->Roles->get($id);

        if (!in_array($role->id, [ROLE_ID_ADMINISTRATOR, ROLE_ID_AUTHENTICATED, ROLE_ID_ANONYMOUS])) {
            if ($this->Roles->delete($role)) {
                $this->Flash->success(__d('user', 'Role was successfully removed!'));
            } else {
                $this->Flash->danger(__d('user', 'Role could not be removed'));
            }
        } else {
            $this->Flash->danger(__d('user', 'This role cannot be deleted!'));
        }

        $this->redirect($this->referer());
    }
}
