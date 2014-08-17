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

/**
 * Anonymous Authenticate adapter.
 *
 * Applies authorization rules to anonymous users.
 */
class AnonymousAuthenticate extends BaseAuthenticate {

/**
 * Anonymous count as a "login failure".
 *
 * @param \Cake\Network\Request $request Unused request object
 * @param \Cake\Network\Response $response Unused response object
 * @return mixed False on login failure. An array of User data on success
 */
	public function authenticate(Request $request, Response $response) {
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
 * @param \Cake\Network\Request $request A request object.
 * @param \Cake\Network\Response $response A response object.
 * @return void
 */
	public function unauthenticated(Request $request, Response $response) {
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
	}

/**
 * Gets an ACO path for current request.
 * 
 * @param \Cake\Network\Request $request
 * @param string $path Pattern
 * @return string
 */
	public function action(Request $request, $path = '/:plugin/:prefix/:controller/:action') {
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
 * @param integer $role_id Role's ID
 * @return array Array of ACO paths
 */
	protected function _rolePermissions($role_id) {
		$Acos = TableRegistry::get('User.Acos');
		$Permissions = TableRegistry::get('User.Permissions');
		$out = [];
		$aco_ids = $Permissions
			->find()
			->select(['aco_id'])
			->where(['role_id' => $role_id])
			->all()
			->extract('aco_id');

		foreach ($aco_ids as $aco_id) {
			$path  = $Acos->find('path', ['for' => $aco_id]);

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
