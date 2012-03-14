<?php
/**
 * Index
 *
 * The Front Controller for handling every request
 *
 * @package       webroot
 */
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT', dirname(dirname(__FILE__)));
    define('QA_PATH', ROOT . DS . 'QuickApps');

/**
 * Editing below this line should NOT be necessary.
 * Change at your own risk.
 *
 */
    define('APP_DIR', dirname(QA_PATH));
    define('CAKE_CORE_INCLUDE_PATH', dirname(dirname(QA_PATH)));
    define('TMP', ROOT . DS . 'tmp' . DS);
    define('APP', QA_PATH . DS);

    if (!defined('WEBROOT_DIR')) {
        define('WEBROOT_DIR', basename(dirname(__FILE__)));
    }

    if (!defined('WWW_ROOT')) {
        define('WWW_ROOT', dirname(__FILE__) . DS);
    }

    if (!defined('CORE_PATH')) {
        define('APP_PATH', QA_PATH . DS);
        define('CORE_PATH', APP_PATH);
    }

    if (!include(CORE_PATH . 'Cake' . DS . 'bootstrap.php')) {
        trigger_error("CakePHP core could not be found.  Check the value of CAKE_CORE_INCLUDE_PATH in webroot/index.php.  It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
    }

    if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] == '/favicon.ico') {
        return;
    }

    App::uses('Dispatcher', 'Routing');

    $Dispatcher = new Dispatcher();
    $Dispatcher->dispatch(new CakeRequest(), new CakeResponse(array('charset' => Configure::read('App.encoding'))));