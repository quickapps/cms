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
 * Gateway manager controller.
 *
 * Provides login and logout methods for backend.
 */
class GatewayController extends AppController {

/**
 * Renders the login form.
 *
 * @return void
 */
	public function login() {
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
