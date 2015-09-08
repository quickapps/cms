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
 * Proxy file, includes site's settings (database info, etc).
 */
if (is_readable(ROOT . '/config/settings.php')) {
    return require ROOT . '/config/settings.php';
} elseif (is_readable(ROOT . '/config/settings.php.tmp')) {
    return require ROOT . '/config/settings.php.tmp';
} else {
    return [];
}
