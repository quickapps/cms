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
namespace User\Config;

use Cake\Cache\Cache;

/**
 * These are hard-coded values for user roles and must match values stored in
 * "roles" DB table.
 */
if (!defined('ROLE_ID_ADMINISTRATOR')) {
	define('ROLE_ID_ADMINISTRATOR', 1);
}

if (!defined('ROLE_ID_AUTHENTICATED')) {
	define('ROLE_ID_AUTHENTICATED', 2);
}

if (!defined('ROLE_ID_ANONYMOUS')) {
	define('ROLE_ID_ANONYMOUS', 3);
}

/**
 * Used by CachedAutorize
 * 
 */
Cache::config('permissions', [
	'duration' => '+1 hour',
	'path' => TMP,
	'engine' => 'File',
	'prefix' => 'qa_',
	'groups' => ['acl']
]);
