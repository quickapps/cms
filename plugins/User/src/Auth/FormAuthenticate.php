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
use Cake\Utility\Security;

/**
 * An authentication adapter for AuthComponent. Provides the ability to
 * authenticate using POST data and using user's **email or username**.
 *
 * It also provides "remember me" capabilities using cookies.
 */
class FormAuthenticate extends CakeFormAuthenticate
{

    /**
     * {@inheritDoc}
     */
    public function authenticate(Request $request, Response $response)
    {
        $result = parent::authenticate($request, $response);
        if (!$result) {
            // fail? try using "username" as "email"
            $this->_config['fields']['username'] = 'email';
            if (!empty($request->data['username'])) {
                $request->data['email'] = $request->data['username'];
            }
            $result = parent::authenticate($request, $response);
        }

        if ($result && !empty($request->data['remember'])) {
            $controller = $this->_registry->getController();
            if (empty($controller->Cookie)) {
                $controller->loadComponent('Cookie');
            }

            // user information array
            $user = json_encode($result);
            // used to check that user's info array is authentic
            $hash = Security::hash($user, 'sha1', true);
            $controller->Cookie->write('User.Cookie', json_encode(compact('user', 'hash')));
        }

        return $result;
    }

    /**
     * Removes "remember me" cookie.
     *
     * @param array $user User information given as an array
     * @return void
     */
    public function logout(array $user)
    {
        $controller = $this->_registry->getController();
        if (empty($controller->Cookie)) {
            $controller->loadComponent('Cookie');
        }
        $controller->Cookie->delete('User.Cookie');
    }
}
