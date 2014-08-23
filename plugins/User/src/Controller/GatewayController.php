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
namespace User\Controller;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\Event;
use User\Controller\AppController;
use Locale\Utility\LocaleToolbox;

/**
 * Gateway controller.
 *
 * Provides login and logout methods.
 */
class GatewayController extends AppController {

/**
 * Mark as allowed some basic actions.
 * 
 * @param \Cake\Event\Event $event
 * @return void
 */
	public function beforeFilter(Event $event) {
		$this->Auth->allow(['login', 'logout', 'unauthorized', 'forgot']);
	}

/**
 * Renders the login form.
 *
 * @return void
 */
	public function login() {
		$this->loadModel('User.Users');
		$this->layout = 'login';

		if ($this->request->is('post')) {
			$user = $this->Auth->identify();
			if ($user) {
				$this->Auth->setUser($user);
				if ($user['id']) {
					try {
						$user = $this->Users->get($user['id']);
						if ($user) {
							$this->Users->touch($user, 'Users.login');
							$this->Users->save($user, ['validate' => false]);
						}
					} catch(\Exception $e) {}
				}
				return $this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Flash->danger(__d('user', 'Username or password is incorrect.'));
			}
		}

		$user = $this->Users->newEntity();
		$this->set(compact('user'));
	}

/**
 * Logout.
 *
 * @return void
 */
	public function logout() {
		$result = $this->Auth->logout();

		if ($result) {
			return $this->redirect($result);
		} else {
			$this->Flash->danger(__d('user', 'Something went wrong, and logout operation could not be completed.'));
			return $this->redirect($this->referer());
		}
	}

/**
 * Starts the password recovery process.
 *
 * @return void
 */
	public function forgot() {
		if (!empty($this->request->data['username'])) {
			$this->loadModel('User.Users');
			$user = $this->Users
				->find()
				->where(['Users.username' => $this->request->data['username']])
				->orWhere(['Users.email' => $this->request->data['username']])
				->first();

			if ($user) {
				$this->Flash->success(__d('user', 'Further instructions have been sent to your e-mail address.'));
			} else {
				$this->Flash->danger(__d('user', 'Sorry, "{0}" is not recognized as a user name or an e-mail address.', $this->request->data['username']));
			}
		}
	}

/**
 * Renders the "unauthorized" screen, when an user attempts to access
 * to a restricted area.
 *
 * @return void
 */
	public function unauthorized() {
		$this->loadModel('User.Users');
		if ($this->request->is('post')) {
			$user = $this->Auth->identify();
			if ($user) {
				$this->Auth->setUser($user);
				return $this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Flash->danger(__d('user', 'Username or password is incorrect'));
			}
		}
	}

/**
 * Renders user's "my profile" form.
 *
 * Here is where user can change their information.
 *
 * @return void
 */
	public function me() {
		$this->loadModel('User.Users');
		$user = $this->Users->get(user()->id, ['conditions' => ['status' => 1]]);
		$languages = LocaleToolbox::languagesList();

		if ($this->request->data) {
			$user->accessible(['id', 'username', 'roles', 'status'], false);
			$user = $this->Users->patchEntity($user, $this->request->data);
			if ($this->Users->save($user)) {
				$this->Flash->success(__d('user', 'User information successfully updated!'), ['key' => 'user_profile']);
				$this->redirect($this->referer());
			} else {
				$this->Flash->danger(__d('user', 'User information could not be saved, please check your information.'), ['key' => 'user_profile']);
			}
		}

		$this->set(compact('user', 'languages'));
	}

/**
 * Shows profile information for the given user.
 *
 * @return void
 */
	public function profile($id) {
		$this->loadModel('User.Users');
		$user = $this->Users->get($id, ['conditions' => ['status' => 1]]);
	}

}
