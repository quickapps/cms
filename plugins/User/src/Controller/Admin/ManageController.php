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

use Locale\Utility\LocaleToolbox;
use User\Controller\AppController;
use User\Notification\NotificationManager;

/**
 * User manager controller.
 *
 * Provides full CRUD for users.
 */
class ManageController extends AppController
{

    /**
     * An array containing the names of helpers controllers uses.
     *
     * @var array
     */
    public $helpers = ['Paginator'];

    /**
     * Shows a list of all registered users.
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('User.Users');
        $users = $this->Users->find()->contain(['Roles']);

        if (!empty($this->request->query['filter'])) {
            $this->Users->search($this->request->query['filter'], $users);
        }

        $this->title(__d('user', 'Users List'));
        $this->set('users', $this->paginate($users));
        $this->Breadcrumb->push('/admin/user/manage');
    }

    /**
     * Adds a new user.
     *
     * @return void
     */
    public function add()
    {
        $this->loadModel('User.Users');
        $user = $this->Users->newEntity();
        $user = $this->Users->attachFields($user);
        $languages = LocaleToolbox::languagesList();
        $roles = $this->Users->Roles->find('list', [
            'conditions' => [
                'id NOT IN' => [ROLE_ID_AUTHENTICATED, ROLE_ID_ANONYMOUS]
            ]
        ]);

        if ($this->request->data()) {
            $user->accessible('id', false);
            $data = $this->request->data;

            if (isset($data['welcome_message'])) {
                $sendWelcomeMessage = (bool)$data['welcome_message'];
                unset($data['welcome_message']);
            } else {
                $sendWelcomeMessage = false;
            }

            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                if ($sendWelcomeMessage) {
                    NotificationManager::welcome($user)->send();
                }

                $this->Flash->success(__d('user', 'User successfully registered!'));
                $this->redirect(['plugin' => 'User', 'controller' => 'manage', 'action' => 'edit', $user->id]);
            } else {
                $this->Flash->danger(__d('user', 'User could not be registered, please check your information.'));
            }
        }

        $this->title(__d('user', 'Register New User'));
        $this->set(compact('user', 'roles', 'languages'));
        $this->Breadcrumb->push('/admin/user/manage');
    }

    /**
     * Edits the given user's information.
     *
     * @param int $id User's ID
     * @return void
     */
    public function edit($id)
    {
        $this->loadModel('User.Users');
        $user = $this->Users->get($id, ['contain' => ['Roles']]);
        $languages = LocaleToolbox::languagesList();
        $roles = $this->Users->Roles->find('list', [
            'conditions' => [
                'id NOT IN' => [ROLE_ID_AUTHENTICATED, ROLE_ID_ANONYMOUS]
            ]
        ]);

        if ($this->request->data()) {
            $user->accessible(['id', 'username'], false);
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__d('user', 'User information successfully updated!'));
                $this->redirect($this->referer());
            } else {
                $this->Flash->danger(__d('user', 'User information could not be saved, please check your information.'));
            }
        }

        $this->title(__d('user', 'Editing User'));
        $this->set(compact('user', 'roles', 'languages'));
        $this->Breadcrumb->push('/admin/user/manage');
    }

    /**
     * Blocks the given user account.
     *
     * After account is blocked token is regenerated, so user cannot login using
     * a known token.
     *
     * @param int $id User's ID
     * @return void Redirects to previous page
     */
    public function block($id)
    {
        $this->loadModel('User.Users');
        $user = $this->Users->get($id, [
            'fields' => ['id', 'name', 'email'],
            'contain' => ['Roles'],
        ]);

        if (!in_array(ROLE_ID_ADMINISTRATOR, $user->role_ids)) {
            if ($this->Users->updateAll(['status' => 0], ['id' => $user->id])) {
                $this->Flash->success(__d('user', 'User {0} was successfully blocked!', $user->name));
                $user->updateToken();
                NotificationManager::blocked($user)->send();
            } else {
                $this->Flash->danger(__d('user', 'User could not be blocked, please try again.'));
            }
        } else {
            $this->Flash->warning(__d('user', 'Administrator users cannot be blocked.'));
        }

        $this->title(__d('user', 'Block User Account'));
        $this->redirect($this->referer());
    }

    /**
     * Activates the given user account.
     *
     * @param int $id User's ID
     * @return void Redirects to previous page
     */
    public function activate($id)
    {
        $this->loadModel('User.Users');
        $user = $this->Users->get($id, ['fields' => ['id', 'name', 'email']]);

        if ($this->Users->updateAll(['status' => 1], ['id' => $user->id])) {
            NotificationManager::activated($user)->send();
            $this->Flash->success(__d('user', 'User {0} was successfully activated!', $user->name));
        } else {
            $this->Flash->danger(__d('user', 'User could not be activated, please try again.'));
        }

        $this->title(__d('user', 'Unblock User Account'));
        $this->redirect($this->referer());
    }

    /**
     * Sends password recovery instructions to the given user.
     *
     * @param int $id User's ID
     * @return void Redirects to previous page
     */
    public function passwordInstructions($id)
    {
        $this->loadModel('User.Users');
        $user = $this->Users->get($id, ['fields' => ['id', 'name', 'email']]);

        if ($user) {
            NotificationManager::passwordRequest($user)->send();
            $this->Flash->success(__d('user', 'Instructions we successfully sent to {0}', $user->name));
        } else {
            $this->Flash->danger(__d('user', 'User was not found.'));
        }

        $this->title(__d('user', 'Recovery Instructions'));
        $this->redirect($this->referer());
    }

    /**
     * Removes the given user.
     *
     * @param int $id User's ID
     * @return void Redirects to previous page
     */
    public function delete($id)
    {
        $this->loadModel('User.Users');
        $user = $this->Users->get($id, ['contain' => ['Roles']]);

        if (in_array(ROLE_ID_ADMINISTRATOR, $user->role_ids) &&
            $this->Users->countAdministrators() === 1
        ) {
            $this->Flash->danger(__d('user', 'You cannot remove this user as it is the last administrator available.'));
        } else {
            if ($this->Users->delete($user)) {
                NotificationManager::canceled($user)->send();
                $this->Flash->success(__d('user', 'User successfully removed!'));
                $this->redirect($this->referer());
            } else {
                $this->Flash->danger(__d('user', 'User could not be removed.'));
            }
        }

        $this->title(__d('user', 'Remove User Account'));
        $this->redirect($this->referer());
    }
}
