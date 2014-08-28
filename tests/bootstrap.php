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

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Utility\Folder;
use Cake\Utility\Inflector;
use \DirectoryIterator;

/**
 * Overwrites core's snapshot() function and emulates its real behavior.
 * 
 * @return void
 */
function snapshot() {
	$snapshot = [
		'version' => '2.0',
		'node_types' => ['article', 'page'],
		'plugins' => [],
		'options' => [
			'back_theme' => 'BackendTheme',
			'default_language' => 'en-us',
			'front_theme' => 'FrontendTheme',
			'site_description' => 'Open Source CMS built on CakePHP 3.0',
			'site_email' => 'demo@email.com',
			'site_maintenance' => '0',
			'site_maintenance_ip' => '192.168.0.1',
			'site_maintenance_message' => 'We sincerely apologize for the inconvenience.<br/>Our site is currently undergoing scheduled maintenance and upgrades, but will return shortly.<br/>Thanks you for your patience.',
			'site_nodes_home' => '5',
			'site_slogan' => 'Open Source CMS built on CakePHP 3.0',
			'site_title' => 'My QuickApps CMS Site',
			'url_locale_prefix' => '1',
		],
		'languages' => [
			'en-us' => [
				'name' => 'English',
				'code' => 'en-us',
				'iso' => 'en',
				'country' => 'US',
				'direction' => 'ltr',
				'icon' => 'us.gif',
			]
		]
	];

	foreach (App::path('Plugin') as $pluginsPath) {
		if (!is_dir($pluginsPath)) {
			continue;
		}
		$dir = new DirectoryIterator($pluginsPath);
		foreach ($dir as $path) {
			if ($path->isDir() && !$path->isDot()) {
				$name = $path->getBaseName();
				$pluginPath = normalizePath($pluginsPath . $name);
				$humanName = Inflector::humanize(Inflector::underscore($name));
				$package = 'quickapps-cms/' . str_replace('_', '-', Inflector::underscore($name));
				$isTheme = (bool)preg_match('/Theme$/', $name);
				$isCore = strpos($pluginPath, 'cms' . DS . 'plugins') !== false;
				$eventsPath = "{$pluginPath}/src/Event/";
				$status = true;
				$eventListeners = [];

				if (is_dir($eventsPath)) {
					$Folder = new Folder($eventsPath);
					foreach ($Folder->read(false, false, true)[1] as $classFile) {
						$className = basename(preg_replace('/\.php$/', '', $classFile));
						$namespace = "{$name}\Event\\";
						$eventListeners[$namespace . $className] = [
							'namespace' => $namespace,
							'path' => dirname($classFile),
						];
					}
				}

				$snapshot['plugins'][$name] = [
					'name' => $name,
					'human_name' => $humanName,
					'package' => $package,
					'isTheme' => $isTheme,
					'isCore' => $isCore,
					'hasHelp' => file_exists($pluginPath . '/src/Template/Element/Help/help.ctp'),
					'hasSettings' => file_exists($pluginPath . '/src/Template/Element/settings.ctp'),
					'eventListeners' => $eventListeners,
					'status' => $status,
					'path' => $pluginPath,
				];
			}
		}
	}

	Configure::write('QuickApps', $snapshot);
	Configure::dump('snapshot.php', 'QuickApps', ['QuickApps']);
}

require QA_CORE . '/config/bootstrap.php';
