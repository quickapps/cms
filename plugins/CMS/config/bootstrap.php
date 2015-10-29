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

/**
 * Include bootstraping logic only if CMS plugin has been installed as part of
 * QuickAppsCMS.
 */
if (defined('QUICKAPPS_CORE')) {
    require_once __DIR__ . '/bootstrap_site.php';
} else {
    require_once __DIR__ . '/functions.php';
}
