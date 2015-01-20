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

/**
 * Use the DS to separate the directories in other defines
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * The full path to the directory which holds QuickApps CMS core's "src", WITHOUT a trailing DS.
 */
define('ROOT', dirname(__DIR__));

/**
 * Path to composer's vendor directory.
 *
 * There is where quickapps & cakephp must be located.
 */
define('VENDOR_INCLUDE_PATH', dirname(dirname(ROOT)) . DS);

/**
 * The actual directory name for quickapps core's "src".
 */
define('APP_DIR', 'src');

/**
 * The name of the webroot dir. Defaults to 'webroot'
 */
define('WEBROOT_DIR', 'webroot');

/**
 * Path to the quickapps application's directory.
 */
define('APP', ROOT . DS . APP_DIR . DS);

/**
 * Path to the config directory.
 */
define('CONFIG', ROOT . DS . 'config' . DS);

/**
 * File path to the webroot directory.
 */
define('WWW_ROOT', SITE_ROOT . DS . WEBROOT_DIR . DS);

/**
 * Path to the temporary files directory.
 */
define('TMP', SITE_ROOT . DS . 'tmp' . DS);

/**
 * Path to the logs directory.
 */
define('LOGS', TMP . 'logs' . DS);

/**
 * Path to the cache files directory. It can be shared between hosts in a multi-server setup.
 */
define('CACHE', TMP . 'cache' . DS);

/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 * CakePHP should always be installed with composer, so look there.
 */
define('CAKE_CORE_INCLUDE_PATH', VENDOR_INCLUDE_PATH . 'cakephp' . DS . 'cakephp');

/**
 * Path to the cake directory.
 */
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . 'src' . DS);
