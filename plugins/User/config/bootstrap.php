<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
use Cake\Cache\Cache;
use Cake\I18n\I18n;
use Cake\Network\Session;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use User\Model\Entity\UserSession;

/**
 * These are hard-coded values for user roles and must match values stored in
 * "roles" DB table.
 */
if (!defined('ROLE_ID_ADMINISTRATOR')) {
    /**
     * ID for "administrator" role, must match the ID stored in DB. You should
     * never change this value on production site.
     */
    define('ROLE_ID_ADMINISTRATOR', 1);
}

if (!defined('ROLE_ID_AUTHENTICATED')) {
    /**
     * ID for "authenticated" role, must match the ID stored in DB. You should
     * never change this value on production site.
     */
    define('ROLE_ID_AUTHENTICATED', 2);
}

if (!defined('ROLE_ID_ANONYMOUS')) {
    /**
     * ID for "anonymous" role, must match the ID stored in DB. You should
     * never change this value on production site.
     */
    define('ROLE_ID_ANONYMOUS', 3);
}

/**
 * Used by CachedAuthorize.
 */
Cache::config('permissions', [
    'className' => 'File',
    'prefix' => 'qa_',
    'path' => TMP,
    'duration' => '+1 hour',
    'groups' => ['acl']
]);

/**
 * Retrieves current user's information (logged in or not) as an entity object.
 *
 * **Usage:**
 *
 *     $user = user();
 *     echo user()->name;
 *     // prints "Anonymous" if not logged in
 *
 * @return \User\Model\Entity\UserSession
 */
function user()
{
    $request = Router::getRequest();
    if ($request && $request->is('userLoggedIn')) {
        $properties = Router::getRequest()->session()->read('Auth.User');
        if (!empty($properties['roles'])) {
            foreach ($properties['roles'] as &$role) {
                unset($role['_joinData']);
                $role = new Entity($role);
            }
        } else {
            $properties['roles'] = [];
        }
        $properties['roles'][] = TableRegistry::get('Roles')->get(ROLE_ID_AUTHENTICATED);
    } else {
        $properties = [
            'id' => null,
            'name' => __d('user', 'Anonymous'),
            'username' => __d('user', 'anonymous'),
            'email' => __d('user', '(no email)'),
            'locale' => I18n::defaultLocale(),
            'roles' => [TableRegistry::get('Roles')->get(ROLE_ID_ANONYMOUS)],
        ];
    }

    static $user = null;
    if ($user === null) {
        $user = new UserSession($properties);
    }
    return $user;
}
