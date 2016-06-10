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
 * Load site's "bootstrap.php".
 */
if (is_readable(ROOT . '/config/bootstrap.php')) {
    include ROOT . '/config/bootstrap.php';
}

/**
 * Users can use another loader by defining the `$classLoader` global variable in
 * site's `bootstrap.php`. If not provided, composer's will be used by default.
 */
if (!isset($classLoader)) {
    $classLoader = require VENDOR_INCLUDE_PATH . 'autoload.php';
}

/**
 * Load QuickApps basic functionality.
 */
require_once __DIR__ . '/functions.php';

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
use Cake\Console\ConsoleErrorHandler;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Database\Type;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Log\Log;
use Cake\Network\Email\Email;
use Cake\Network\Request;
use Cake\Routing\DispatcherFactory;
use Cake\Utility\Security;
use Go\Aop\Features;
use CMS\Aspect\AppAspect;
use CMS\Core\Plugin;
use CMS\Event\EventDispatcher;

/**
 * Configure default event dispatcher to use global event manager.
 */
EventDispatcher::instance()->eventManager(\Cake\Event\EventManager::instance());

/**
 * Registers custom types.
 */
Type::map('serialized', 'CMS\Database\Type\SerializedType');

/**
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
Configure::config('default', new PhpConfig(__DIR__ . DS));


/**
 * Load an environment local configuration file.
 *
 * You can use this file to provide local overrides to your
 * shared configuration.
 */
Configure::load('app', 'default', false);
Configure::load('app_site', 'default');

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
ini_set('intl.default_locale', 'en_US');

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
Request::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();
    return $detector->isMobile();
});
Request::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();
    return $detector->isTablet();
});

/**
 * Load some bootstrap-handy information.
 */
Configure::config('QuickApps', new PhpConfig(TMP));
if (!is_readable(TMP . 'snapshot.php')) {
    snapshot();
} else {
    try {
        Configure::load('snapshot', 'QuickApps', false);
    } catch (\Exception $ex) {
        die('No snapshot found. check write permissions on tmp/ directory');
    }
}

/**
 * Load all registered plugins.
 */
$pluginsPath = [];
plugin()
    ->each(function ($plugin) use (&$pluginsPath, $classLoader) {
        if (strtoupper($plugin->name) === 'CMS') {
            return;
        }

        $filter = $plugin->status;
        if ($plugin->isTheme) {
            $filter = $filter && in_array($plugin->name, [option('front_theme'), option('back_theme')]);
        }

        if (!$filter) {
            return;
        }

        if (!in_array("{$plugin->name}\\", array_keys($classLoader->getPrefixesPsr4()))) {
            $classLoader->addPsr4("{$plugin->name}\\", normalizePath("{$plugin->path}/src/"), true);
        }

        if (!in_array("{$plugin->name}\\Test\\", array_keys($classLoader->getPrefixesPsr4()))) {
            $classLoader->addPsr4("{$plugin->name}\\Test\\", normalizePath("{$plugin->path}/tests/"), true);
        }

        $info = [
            'autoload' => false,
            'bootstrap' => true,
            'routes' => true,
            'path' => normalizePath("{$plugin->path}/"),
            'classBase' => 'src',
            'ignoreMissing' => true,
        ];

        Plugin::load($plugin->name, $info);

        foreach ($plugin->eventListeners as $fullClassName) {
            if (class_exists($fullClassName)) {
                if (str_ends_with($fullClassName, 'Shortcode')) {
                    EventDispatcher::instance('Shortcode')->eventManager()->on(new $fullClassName);
                } else {
                    EventDispatcher::instance()
                        ->eventManager()
                        ->on(new $fullClassName);
                }
            }
        }

        $pluginsPath[] = $info['path'];
    });

if (empty($pluginsPath)) {
    die("Ops, something went wrong. Try to clear your site's snapshot and verify write permissions on /tmp directory.");
}

/**
 * Initialize Aspects
 */
AppAspect::getInstance()->init([
    'debug' => Configure::read('debug'),
    'cacheDir' => TMP . 'aop',
    'includePaths' => array_unique(array_merge($pluginsPath, [
        ROOT . DS . 'plugins',
        QUICKAPPS_CORE,
        CAKE,
    ])),
    'excludePaths' => [TMP . 'aop'],
    'features' => \Go\Aop\Features::INTERCEPT_FUNCTIONS,
]);

/**
 * Connect middleware/dispatcher filters.
 */
DispatcherFactory::add('Asset');
if (!is_readable(ROOT . '/config/settings.php')) {
    DispatcherFactory::add('Routing');
} else {
    DispatcherFactory::add('Language');
}
DispatcherFactory::add('ControllerFactory');
