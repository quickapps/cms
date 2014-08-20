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
 * Gateway manager controller.
 *
 * Provides login and logout methods for frontend.
 */
class GatewayController extends AppController {

/**
 * Mark as allowed basic actions.
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
		session_start();
		$this->loadModel('User.Users');
		$this->layout = 'login';

		if ($this->request->is('post')) {
			$user = $this->Auth->identify();
			if ($user) {
				$this->Auth->setUser($user);
				return $this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Flash->danger(__d('user', 'Username or password is incorrect'));
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
		return $this->redirect($this->Auth->logout());
	}

/**
 * Starts the password recovery process.
 *
 * @return void
 */
	public function forgot() {
	}

/**
 * Renders user's profile form.
 *
 * @return void
 */
	public function profile() {
		$this->loadModel('User.Users');
		$user = $this->Users->get(user()->id);
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

}
