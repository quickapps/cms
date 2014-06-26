<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 1.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace QuickApps\Config;

/**
 * Configure paths required to find CakePHP + general file path constants
 */
require __DIR__ . '/paths.php';

/**
 * Load QuickApps basic functionality.
 */
require __DIR__ . '/basics.php';

/**
 * Merge Composer's autoloader with Cake's autoloader.
 */
$composerLoader = require VENDOR_INCLUDE_PATH . '/autoload.php';
require CAKE . 'Core/ClassLoader.php';
$loader = new \Cake\Core\ClassLoader;
$loader->register();
$loader->addNamespace('Cake', CAKE);
$loader->addNamespace('QuickApps', APP);
foreach ($composerLoader as $name => $path) {
	$loader->addNamespace($name, $path);
}

/**
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CAKE . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Configure\Engine\PhpConfig;
use Cake\Console\ConsoleErrorHandler;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Cake\Network\Email\Email;
use Cake\Network\Request;
use Cake\Routing\DispatcherFactory;
use Cake\Utility\Inflector;

/**
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
Configure::config('default', new PhpConfig());
Configure::load('app.php', 'default', false);

/**
 * Load an environment local configuration file.
 *
 * You can use this file to provide local overrides to your
 * shared configuration.
 */
//Configure::load('app_local.php', 'default');

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
 * Register application error and exception handlers.
 */
$isCli = php_sapi_name() === 'cli';
if ($isCli === 'cli') {
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
 * Set the full base url.
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

if (!file_exists(TMP . 'snapshot.php') && file_exists(SITE_ROOT . '/Config/settings.json')) {
	snapshot();
} else {
	try {
		Configure::load('snapshot.php', 'QuickApps', false);
	} catch (Exception $e) {
		die('No snapshot found. check write permissions on tmp/ directory');
	}
}

/**
 * Load all registered plugins.
 */
foreach (App::objects('Plugin') as $plugin) {
	$EventManager = EventManager::instance();

	if (
		in_array($plugin, Configure::read('QuickApps.plugins.core')) ||
		in_array($plugin, Configure::read('QuickApps.plugins.enabled')) ||
		$plugin === Configure::read('QuickApps.variables.site_theme') ||
		$plugin === Configure::read('QuickApps.variables.admin_theme')
	) {
		Plugin::load(
			$plugin,
			[
				'namespace' => $plugin,
				'autoload' => true,
				'bootstrap' => true,
				'routes' => true,
				'ignoreMissing' => true
			]
		);

		foreach ((array)Configure::read("QuickApps.hooks.{$plugin}") as $hookListener) {
			$loader->addNamespace($hookListener['namespace'], $hookListener['path']);

			if (class_exists($hookListener['className'])) {
				$EventManager->attach(new $hookListener['className']);
			}
		}

		foreach ((array)Configure::read("QuickApps.fields.{$plugin}") as $fieldHandler) {
			$loader->addNamespace($fieldHandler['namespace'], $fieldHandler['path']);

			if (class_exists($fieldHandler['className'])) {
				$EventManager->attach(new $fieldHandler['className']);
			}
		}
	}
}

/**
 * Load site's bootstrap.php
 */
if (file_exists(SITE_ROOT . DS . 'Config' . DS .  ' bootstrap.php')) {
	include_once SITE_ROOT . DS . 'Config' . DS .  ' bootstrap.php';
}

/**
 * Connect middleware/dispatcher filters.
 */
DispatcherFactory::add('Asset');
DispatcherFactory::add('Cache');
DispatcherFactory::add('Routing');
DispatcherFactory::add('ControllerFactory');
