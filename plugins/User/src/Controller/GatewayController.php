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

/**
 * Gateway manager controller.
 *
 * Provides login and logout methods for frontend.
 */
class GatewayController extends AppController {

	public function beforeFilter (Event $event) {
		$this->Auth->allow(['login', 'logout', 'unauthorized']);
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
				return $this->redirect($this->Auth->redirectUrl());
			} else {
				$this->Flash->danger(__d('user', 'Username or password is incorrect'));
			}
		}
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
