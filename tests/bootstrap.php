<?php
/**
 * Test runner bootstrap.
 *
 * Add additional configuration/setup your application needs when running
 * unit tests in this file.
 */

define('SITE_ROOT', __DIR__ . '/TestSite');
define('QA_CORE', dirname(__DIR__));
require  QA_CORE . '/config/paths.php';
require VENDOR_INCLUDE_PATH . 'autoload.php';
require QA_CORE . '/config/basics.php';
require CORE_PATH . 'config' . DS . 'bootstrap.php';

use Cake\Cache\Cache;
use Cake\Configure\Engine\PhpConfig;
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
use Cake\Utility\Folder;
use QuickApps\Core\Plugin;

Configure::config('default', new PhpConfig());
Configure::load('app.php', 'default', false);
Configure::load('app_local.php', 'default');

if (!Configure::read('debug')) {
	Configure::write('Cache._cake_model_.duration', '+99 years');
	Configure::write('Cache._cake_core_.duration', '+99 years');
}

date_default_timezone_set('UTC');
mb_internal_encoding(Configure::read('App.encoding'));
ini_set('intl.default_locale', 'en-us');
(new ErrorHandler(Configure::consume('Error')))->register();

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

Request::addDetector('mobile', function($request) {
	$detector = new \Detection\MobileDetect();
	return $detector->isMobile();
});

Request::addDetector('tablet', function($request) {
	$detector = new \Detection\MobileDetect();
	return $detector->isTablet();
});

$EventManager = EventManager::instance();
foreach (Plugin::scan() as $plugin => $path) {
	Plugin::load($plugin, [
		'autoload' => true,
		'bootstrap' => true,
		'routes' => true,
		'ignoreMissing' => true,
	]);

	$eventsPath = Plugin::classPath($plugin) . 'Event/';
	$Folder = new Folder($eventsPath);
	list($folders, $files) = $Folder->read();

	foreach ($files as $listener) {
		$listener = str_replace('.php', '', $listener);
		$className = "{$plugin}\\Event\\{$listener}";
		if (class_exists($className)) {
			$EventManager->attach(new $className);
		}
	}
}

DispatcherFactory::add('Asset');
DispatcherFactory::add('Routing');
DispatcherFactory::add('ControllerFactory');
