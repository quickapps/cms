<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace User\Controller\Admin;

use User\Controller\AppController;

/**
 * User manager controller.
 *
 * Provides full CRUD for users.
 */
class ManageController extends AppController {

/**
 * An array containing the names of helpers controllers uses.
 *
 * @var array
 */
	public $helpers = [
		'Paginator' => [
			'className' => 'QuickApps\View\Helper\PaginatorHelper',
			'templates' => 'System.paginator-templates.php',
		],
	];

/**
 * Shows a list of all the nodes.
 *
 * @return void
 */
	public function index() {
		$this->loadModel('User.Users');
		$users = $this->Users->find()->contain(['Roles']);
		$this->set('users', $this->paginate($users));

		$this->Breadcrumb->push('/admin/user/manage');
	}

/**
 * Adds a new user.
 * 
 * @return void
 */
	public function add() {
		$this->loadModel('User.Users');
		$user = $this->Users->newEntity();
		$user = $this->Users->attachEntityFields($user);
		$roles = $this->Users->Roles->find('list', [
			'conditions' => [
				'id NOT IN' => [ROLE_ID_AUTHENTICATED, ROLE_ID_ANONYMOUS]
			]
		]);

		if ($this->request->data) {
			$user->accessible('id', false);
			$user = $this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				$this->Flash->success(__d('user', 'User successfully registered!'));
				$this->redirect(['plugin' => 'User', 'controller' => 'manage', 'action' => 'edit', $user->id]);
			} else {
				$this->Flash->danger(__d('user', 'User could not be registered, please check your information.'));
			}
		}

		$this->set(compact('user', 'roles'));
		$this->Breadcrumb->push('/admin/user/manage');
	}

/**
 * Edits the given user's information.
 * 
 * @param integer $id
 * @return void
 */
	public function edit($id) {
		$this->loadModel('User.Users');
		$user = $this->Users->get($id, ['contain' => ['Roles']]);
		$roles = $this->Users->Roles->find('list', [
			'conditions' => [
				'id NOT IN' => [ROLE_ID_AUTHENTICATED, ROLE_ID_ANONYMOUS]
			]
		]);

		if ($this->request->data) {
			$user->accessible(['id', 'username'], false);
			$user = $this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				$this->Flash->success(__d('user', 'User information successfully updated!'));
				$this->redirect($this->referer());
			} else {
				$this->Flash->danger(__d('user', 'User information could not be saved, please check your information.'));
			}
		}

		$this->set(compact('user', 'roles'));
		$this->Breadcrumb->push('/admin/user/manage');
	}

/**
 * Removes the given user.
 * 
 * @param integer $id
 * @return void Redirects to previous page
 */
	public function delete($id) {
		$this->loadModel('User.Users');
		$user = $this->Users->get($id, ['contain' => ['Roles']]);
		$administrators = $this->Users
			->find()
			->matching('Roles', function ($q) {
				return $q->where(['Roles.id' => ROLE_ID_ADMINISTRATOR]);
			})
			->count();

		if (
			in_array(ROLE_ID_ADMINISTRATOR, $user->role_ids) &&
			$administrators === 1
		) {
			$this->Flash->danger(__d('user', 'You cannot remove this user as it is the only administrator available.'));
		} else {
			if ($this->Users->delete($user)) {
				$this->Flash->success(__d('user', 'User successfully removed!'));
				$this->redirect($this->referer());
			} else {
				$this->Flash->danger(__d('user', 'User could not be removed.'));
			}
		}

		$this->redirect($this->referer());
	}

}
