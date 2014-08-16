<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 1.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace User\Auth;

use Cake\Auth\BaseAuthorize;
use Cake\Network\Request;
use Cake\Utility\Inflector;
use Cake\Cache\Cache;

/**
 * Authentication adapter for AuthComponent.
 * 
 */
class CachedAuthorize extends BaseAuthorize  {

	public function authorize($user, Request $request) {
		if ($request->is('userAdmin')) {
			return true;
		}

		$user = user();
		$path = $this->action($request);

		if (!$user->id) {
			$cacheKey = 'permissions_null';
		} else {
			$cacheKey = 'permissions_' . intval($user->id);
		}

		$permissions = Cache::read($cacheKey, 'permissions');
		if ($permissions === false) {
			$permissions = [];
			Cache::write($cacheKey, [], 'permissions');
		}

		if (!isset($permissions[$path])) {
			$this->_Controller->loadModel('User.Permissions');
			$allowed = $this->_Controller->Permissions->check($user, $path);
			$permissions[$path] = $allowed;
			Cache::write($cacheKey, $permissions, 'permissions');
		} else {
			$allowed = $permissions[$path];
		}

		return $allowed;
	}

	public function action(Request $request, $path = '/:plugin/:prefix/:controller/:action') {
		$plugin = empty($request['plugin']) ? null : Inflector::camelize($request['plugin']) . '/';
		$prefix = empty($request->params['prefix']) ? '' : Inflector::camelize($request->params['prefix']) . '/';
		$path = str_replace(
			array(':controller', ':action', ':plugin/', ':prefix/'),
			array(Inflector::camelize($request['controller']), $request['action'], $plugin, $prefix),
			$this->_config['actionPath'] . $path
		);
		$path = str_replace('//', '/', $path);
		return trim($path, '/');
	}

}
