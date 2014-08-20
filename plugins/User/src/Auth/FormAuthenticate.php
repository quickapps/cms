<?php
/**
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace User\Auth;

use Cake\Auth\FormAuthenticate as CakeFormAuthenticate;
use Cake\Network\Request;
use Cake\Network\Response;

/**
 * An authentication adapter for AuthComponent. Provides the ability to authenticate using POST
 * data and using user's **email or username**.
 *
 */
class FormAuthenticate extends CakeFormAuthenticate {

/**
 * {@inheritdoc}
 *
 * @param \Cake\Network\Request $request The request that contains login information.
 * @param \Cake\Network\Response $response Unused response object.
 * @return mixed False on login failure. An array of User data on success.
 */
	public function authenticate(Request $request, Response $response) {
		$result = parent::authenticate($request, $response);
		if (!$result) {
			// fail? try using "username" as "email"
			$this->_config['fields']['username'] = 'email';
			if (!empty($request->data['username'])) {
				$request->data['email'] = $request->data['username'];
			}
			$result = parent::authenticate($request, $response);
		}

		return $result;
	}

}
