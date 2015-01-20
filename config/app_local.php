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

/**
 * Includes application's settings (database info, etc).
 */
if (file_exists(SITE_ROOT . '/config/settings.php')) {
	require SITE_ROOT . '/config/settings.php';
} elseif (file_exists(SITE_ROOT . '/config/settings.php.tmp')) {
	require SITE_ROOT . '/config/settings.php.tmp';
} else {
	$config = [];
}
