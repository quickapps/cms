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
 * Configure paths required to find CakePHP + general filepath
 * constants
 */
require __DIR__ . '/paths.php';

/**
 * Use composer to load the autoloader.
 */
if (!isset($classLoader)) {
    $classLoader = require_once VENDOR_INCLUDE_PATH . 'autoload.php';
}

/**
 * Load QuickApps basic functionality.
 */
require_once __DIR__ . '/basics.php';

/**
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require_once CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Core\ClassLoader;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Console\ConsoleErrorHandler;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Database\Type;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Cake\Network\Email\Email;
use Cake\Network\Request;
use Cake\Routing\DispatcherFactory;
use Cake\Utility\Security;
use QuickApps\Core\Plugin;

/**
 * Registers custom types.
 */
Type::map('serialized', 'QuickApps\Database\Type\SerializedType');

/**
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
Configure::config('default', new PhpConfig());
Configure::load('app', 'default', false);

/**
 * Load an environment local configuration file.
 *
 * You can use this file to provide local overrides to your
 * shared configuration.
 */
Configure::load('app_local', 'default');

/**
 * When debug = false the metadata cache should last
 * for a very very long time, as we don't want
 * to refresh the cache while users are doing requests
 */
if (!Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+99 years');
    Configure::write('Cache._cake_core_.duration', '+99 years');
}

/**
 * Uncomment this line and correct your server timezone to fix
 * any date & time related errors.
 */
date_default_timezone_set('UTC');

/**
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/**
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', 'en-us');

/**
 * Register application error and exception handlers.
 */
$isCli = php_sapi_name() === 'cli';
if ($isCli) {
    (new ConsoleErrorHandler(Configure::consume('Error')))->register();
} else {
    (new ErrorHandler(Configure::consume('Error')))->register();
}

/**
 * Include the CLI bootstrap overrides.
 */
if ($isCli) {
    require __DIR__ . '/bootstrap_cli.php';
}

/**
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 *
 * If you define fullBaseUrl in your config file you can remove this.
 */
if (!Configure::read('App.fullBaseUrl')) {
    $s = null;
    if (env('HTTPS')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        Configure::write('App.fullBaseUrl', 'http' . $s . '://' . $httpHost);
    }
    unset($httpHost, $s);
}

Cache::config(Configure::consume('Cache'));
ConnectionManager::config(Configure::consume('Datasources'));
Email::configTransport(Configure::consume('EmailTransport'));
Email::config(Configure::consume('Email'));
Log::config(Configure::consume('Log'));
Security::salt(Configure::consume('Security.salt'));

/**
 * Setup detectors for mobile and tablet.
 */
Request::addDetector('mobile', function($request) {
    $detector = new \Detection\MobileDetect();
    return $detector->isMobile();
});
Request::addDetector('tablet', function($request) {
    $detector = new \Detection\MobileDetect();
    return $detector->isTablet();
});

/**
 * Load some bootstrap-handy information.
 */
Configure::config('QuickApps', new PhpConfig(TMP));
if (!file_exists(TMP . 'snapshot.php')) {
    snapshot();
} else {
    try {
        Configure::load('snapshot', 'QuickApps', false);
    } catch (\Exception $e) {
        die('No snapshot found. check write permissions on tmp/ directory');
    }
}

/**
 * Load all registered plugins.
 */
$activePlugins = Plugin::collection()->match(['status' => 1])->toArray();
$EventManager = EventManager::instance();
$pluginLoader = new ClassLoader();
$pluginLoader->register();

if (!count($activePlugins)) {
    die("Ops, something went wrong. Try to clear your site's snapshot and verify write permissions on /tmp directory.");
}

foreach ($activePlugins as $pluginName => $info) {
    if (
        $info['isTheme'] &&
        !in_array($pluginName, [option('front_theme'), option('back_theme')])
    ) {
        continue;
    }

    $pluginLoader->addNamespace(
        str_replace('/', '\\', $pluginName),
        $info['path'] .  DS . 'src' . DS
    );

    $pluginLoader->addNamespace(
        str_replace('/', '\\', $pluginName) . '\Test',
        $info['path'] . DS . 'tests' . DS
    );

    Plugin::load($pluginName, [
        'autoload' => false,
        'bootstrap' => true,
        'routes' => true,
        'classBase' => 'src',
        'ignoreMissing' => true,
    ]);

    foreach ($info['eventListeners'] as $fullClassName => $eventInfo) {
        if (class_exists($fullClassName)) {
            $EventManager->attach(new $fullClassName);
        }
    }
}

/**
 * Load site's "bootstrap.php".
 */
if (file_exists(SITE_ROOT . '/config/bootstrap.php')) {
    include_once SITE_ROOT . '/config/bootstrap.php';
}

/**
 * Connect middleware/dispatcher filters.
 */
DispatcherFactory::add('Asset');
DispatcherFactory::add('Routing');
DispatcherFactory::add('ControllerFactory');
