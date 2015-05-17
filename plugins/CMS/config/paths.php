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

if (!function_exists('tryDefine')) {
    /**
     * Tries to define the given constant if not defined already.
     *
     * @param string $name Constant name
     * @param string $value Constant value
     * @return void
     */
    function tryDefine($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }
}

/**
 * Use the DS to separate the directories in other defines
 */
tryDefine('DS', DIRECTORY_SEPARATOR);

/**
 * The full path to the directory which holds "cms" directory, WITHOUT a trailing
 * DS.
 */
tryDefine('ROOT', dirname(dirname(dirname(__DIR__))));

/**
 * Path to composer's vendor directory.
 *
 * There is where quickapps & cakephp must be located.
 */
tryDefine('VENDOR_INCLUDE_PATH', dirname(dirname(ROOT)) . DS);

/**
 * The actual directory name for QuickAppsCMS core's "src".
 */
tryDefine('APP_DIR', 'src');

/**
 * The name of the webroot dir. Defaults to 'webroot'
 */
tryDefine('WEBROOT_DIR', 'webroot');

/**
 * Path to QuickAppsCMS application's directory.
 */
tryDefine('APP', QUICKAPPS_CORE . APP_DIR . DS);

/**
 * Path to the config directory.
 */
tryDefine('CONFIG', QUICKAPPS_CORE . 'config' . DS);

/**
 * File path to the webroot directory.
 */
tryDefine('WWW_ROOT', SITE_ROOT . DS . WEBROOT_DIR . DS);

/**
 * Path to the tests directory.
 */
tryDefine('TESTS', ROOT . DS . 'tests' . DS);

/**
 * Path to the temporary files directory.
 */
tryDefine('TMP', SITE_ROOT . DS . 'tmp' . DS);

/**
 * Path to the logs directory.
 */
tryDefine('LOGS', SITE_ROOT . DS . 'logs' . DS);

/**
 * Path to the cache files directory. It can be shared between hosts in a multi-server setup.
 */
tryDefine('CACHE', TMP . 'cache' . DS);

/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 * CakePHP should always be installed with composer, so look there.
 */
tryDefine('CAKE_CORE_INCLUDE_PATH', VENDOR_INCLUDE_PATH . 'cakephp' . DS . 'cakephp');

/**
 * Path to the cake directory.
 */
tryDefine('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
tryDefine('CAKE', CORE_PATH . 'src' . DS);
