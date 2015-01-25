<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    1.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace User\Auth;

use Cake\Auth\BaseAuthorize;
use Cake\Cache\Cache;
use Cake\Network\Request;
use Cake\ORM\Entity;
use Cake\Utility\Inflector;

/**
 * Authorization adapter for AuthComponent.
 *
 * This adapter provides "Controller-action" based authorization with
 * cache capabilities. Only authenticated users are validated using this
 * technique, all anonymous users (unauthenticated users) are validated
 * using QuickApps CMS's `AnonymousAuthenticate`
 */
class CachedAuthorize extends BaseAuthorize
{

    /**
     * Authorizes current logged in user, if user belongs to the "administrator"
     * he/she is automatically authorized.
     *
     * @param array $user Active user data
     * @param \Cake\Network\Request $request Request instance
     * @return bool True if user is can access this request
     */
    public function authorize($user, Request $request)
    {
        if ($request->is('userAdmin')) {
            return true;
        }

        $user = user();
        $path = $this->requestPath($request);
        $cacheKey = 'permissions_' . intval($user->id);
        $permissions = Cache::read($cacheKey, 'permissions');

        if ($permissions === false) {
            $permissions = [];
            Cache::write($cacheKey, [], 'permissions');
        }

        if (!isset($permissions[$path])) {
            $allowed = $user->can($path);
            $permissions[$path] = $allowed;
            Cache::write($cacheKey, $permissions, 'permissions');
        } else {
            $allowed = $permissions[$path];
        }

        return $allowed;
    }

    /**
     * Gets an ACO path for current request.
     *
     * @param \Cake\Network\Request $request Request instance
     * @param string $path Pattern
     * @return string
     */
    public function requestPath(Request $request, $path = '/:plugin/:prefix/:controller/:action')
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
}
