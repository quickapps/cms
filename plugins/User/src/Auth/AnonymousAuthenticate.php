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

use Cake\Auth\BaseAuthenticate;
use Cake\Cache\Cache;
use Cake\Network\Request;
use Cake\Network\Response;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\Utility\Security;

/**
 * Anonymous Authenticate adapter.
 *
 * Applies authorization rules to anonymous users. Also, it will try login
 * using the following methods:
 *
 * - Cookie: If user has a valid "remember me" cookie it be used to log in.
 * - Token: If a valid token is given in current URL (as GET argument) user
 *   will be automatically logged in.
 *
 * NOTE: Cookies are automatically created by FormAuthenticate.
 */
class AnonymousAuthenticate extends BaseAuthenticate
{

    /**
     * Anonymous count as a "login failure".
     *
     * @param \Cake\Network\Request $request Unused request object
     * @param \Cake\Network\Response $response Unused response object
     * @return mixed False on login failure. An array of User data on success
     */
    public function authenticate(Request $request, Response $response)
    {
        return false;
    }

    /**
     * Handle unauthenticated access attempt. In implementation valid return values
     * can be:
     *
     * - Null - No action taken, AuthComponent should return appropriate response.
     * - Cake\Network\Response - A response object, which will cause AuthComponent to
     *   simply return that response.
     *
     * @param \Cake\Network\Request $request A request object
     * @param \Cake\Network\Response $response A response object
     * @return mixed
     */
    public function unauthenticated(Request $request, Response $response)
    {
        if ($this->_cookieLogin($request)) {
            return true;
        }

        if ($this->_tokenLogin($request)) {
            return true;
        }
    }

    /**
     * Tries to login user if he/she has a cookie.
     *
     * @param \Cake\Network\Request $request A request object
     * @return bool True if user was logged in using cookie, false otherwise
     */
    protected function _cookieLogin(Request $request)
    {
        $controller = $this->_registry->getController();
        if (empty($controller->Cookie)) {
            $controller->loadComponent('Cookie');
        }

        $cookie = $controller->Cookie->read('User.Cookie');
        if ($cookie) {
            $cookie = json_decode($cookie, true);
            if (isset($cookie['user']) &&
                isset($cookie['hash']) &&
                $cookie['hash'] == Security::hash($cookie['user'], 'sha1', true)
            ) {
                $cookie['user'] = json_decode($cookie['user'], true);
                $user = $this->_findUser($cookie['user']['username']);
                if ($user) {
                    if (isset($user['password'])) {
                        unset($user['password']);
                    }
                    $controller->Auth->setUser($user);
                    return true;
                }
            }
        }

        $cacheKey = 'permissions_anonymous';
        $permissions = Cache::read($cacheKey, 'permissions');
        $action = $this->action($request);

        if ($permissions === false) {
            $permissions = $this->_rolePermissions(ROLE_ID_ANONYMOUS);
            Cache::write($cacheKey, $permissions, 'permissions');
        }

        if (isset($permissions[$action])) {
            return true;
        }

        return false;
    }

    /**
     * Tries to login user using token.
     *
     * Token must be passed as a GET parameter named `token`, tokens looks as follow:
     *
     *     // <integer>-<md5-hash>
     *     1-5df9f63916ebf8528697b629022993e8
     *
     * Tokens are consumables, the same token cannot be used twice to log in.
     *
     * @param \Cake\Network\Request $request A request object
     * @return bool True if user was logged in using token, false otherwise
     */
    protected function _tokenLogin(Request $request)
    {
        if (!empty($request->query['token']) &&
            strpos($request->query['token'], '-') !== false
        ) {
            $token = $request->query['token'];
            $Users = TableRegistry::get('User.Users');
            $exists = $Users
                ->find()
                ->select(['id', 'username'])
                ->where(['token' => $token])
                ->limit(1)
                ->first();

            if ($exists) {
                $user = $this->_findUser($exists->username);
                if ($user) {
                    $controller = $this->_registry->getController();
                    if (isset($user['password'])) {
                        unset($user['password']);
                    }
                    $controller->Auth->setUser($user);
                    $Users->updateToken($exists);
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Gets an ACO path for current request.
     *
     * @param \Cake\Network\Request $request Request object
     * @param string $path Pattern
     * @return string
     */
    public function action(Request $request, $path = '/:plugin/:prefix/:controller/:action')
    {
        $plugin = empty($request['plugin']) ? null : Inflector::camelize($request['plugin']) . '/';
        $prefix = empty($request->params['prefix']) ? '' : Inflector::camelize($request->params['prefix']) . '/';
        $path = str_replace(
            array(':controller', ':action', ':plugin/', ':prefix/'),
            array(Inflector::camelize($request['controller']), $request['action'], $plugin, $prefix),
            $path
        );
        $path = str_replace('//', '/', $path);
        return trim($path, '/');
    }

    /**
     * Gets all permissions available for the given role.
     *
     * Example Output:
     *
     *     [
     *         'User/Admin/Gateway/login' => true,
     *         'User/Admin/Gateway/logout' => true,
     *         ...
     *     ]
     *
     * Resulting array is always `key` => **true**, as role have access
     * to every ACO in the array "true" is the only possible value.
     *
     * @param int $roleId Role's ID
     * @return array Array of ACO paths which role has permissions to
     */
    protected function _rolePermissions($roleId)
    {
        $Acos = TableRegistry::get('User.Acos');
        $Permissions = TableRegistry::get('User.Permissions');
        $out = [];
        $acoIds = $Permissions
            ->find()
            ->select(['aco_id'])
            ->where(['role_id' => $roleId])
            ->all()
            ->extract('aco_id');

        foreach ($acoIds as $acoId) {
            $path = $Acos->find('path', ['for' => $acoId]);

            if (!$path) {
                continue;
            }

            $path = implode('/', $path->extract('alias')->toArray());
            if ($path) {
                $out[$path] = true;
            }
        }

        return $out;
    }
}
