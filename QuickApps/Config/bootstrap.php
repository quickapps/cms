<?php
/**
 * This file is loaded automatically by the webroot/index.php file after core.php
 *
 * PHP 5
 *
 * @author Christopher Castro <chris@quickapps.es>
 * @package QuickApps.Config.bootstrap
 * @link http://www.quickappscms.org
 */

Cache::config('default', array('engine' => 'File'));
define('THEMES', ROOT  . DS . 'Themes' . DS . 'Themed' . DS);
App::uses('Spyc', 'vendors');
App::uses('Folder', 'Utility');
App::uses('QuickApps', 'System.Lib');
App::uses('HookCollection', 'System.Lib');

$__plugin_paths = Cache::read('plugin_paths');

if (!$__plugin_paths) {
	$folder = new Folder;
	$folder->path = THEMES;

	// site themes
	$__themes = $folder->read();
	$__themes = $__themes[0];

	foreach ($__themes as $__tname) {
		$__plugin_paths[] = THEMES . $__tname . DS . 'app' . DS;
	}

	// core themes
	$folder->path = APP . 'View' . DS . 'Themed' . DS;
	$__themes = $folder->read();
	$__themes = $__themes[0];

	foreach ($__themes as $__tname) {
		$__plugin_paths[] =  APP . 'View' . DS . 'Themed' . DS . $__tname . DS . 'app' . DS;
	}

	$__plugin_paths[] = ROOT . DS . 'Modules' . DS;

	App::build(array('plugins' => $__plugin_paths));

	$plugins = App::objects('plugins', null, false);

	foreach ($plugins as $plugin) {
		CakePlugin::load($plugin, array('bootstrap' => true, 'routes' => true));

		$__ppath = CakePlugin::path($plugin);
		$__ppath = str_replace(DS . $plugin . DS, DS . $plugin . DS, $__ppath);

		if (file_exists($__ppath . 'Fields' . DS)) {
			$__plugin_paths[] = $__ppath . 'Fields' . DS;
		}
	}

	Cache::write('plugin_paths', $__plugin_paths);
	unset($__themes, $__tname, $folder);
}

App::build(
	array(
		'locales' => ROOT . DS . 'Locale' . DS,
		'views' => ROOT  . DS . 'Themes' . DS,
		'plugins' => $__plugin_paths,
		'Model/Behavior' => ROOT . DS . 'Hooks' . DS . 'Behavior' . DS,
		'View/Helper' => ROOT . DS . 'Hooks' . DS . 'Helper' . DS,
		'Controller/Component' => ROOT . DS . 'Hooks' . DS . 'Component' . DS
	)
);

if (file_exists(ROOT . DS . 'SiteApp' . DS)) {
	App::build(
		array(
			'Model' => ROOT . DS . 'SiteApp' . DS . 'Model' . DS,
			'Model/Behavior' => ROOT . DS . 'SiteApp' . DS . 'Model' . DS . 'Behavior' . DS,
			'Model/Datasource' => ROOT . DS . 'SiteApp' . DS . 'Model' . DS . 'Datasource' . DS,
			'Model/Datasource/Database' => ROOT . DS . 'SiteApp' . DS . 'Model' . DS . 'Datasource' . DS . 'Database',
			'Model/Datasource/Session' => ROOT . DS . 'SiteApp' . DS . 'Model' . DS . 'Datasource' . DS . 'Session',
			'Controller' => ROOT . DS . 'SiteApp' . DS . 'Controller' . DS,
			'Controller/Component' => ROOT . DS . 'SiteApp' . DS . 'Controller' . DS . 'Component' . DS,
			'Controller/Component/Auth' => ROOT . DS . 'SiteApp' . DS . 'Controller' . DS . 'Component' . DS . 'Auth' . DS,
			'Controller/Component/Auth' => ROOT . DS . 'SiteApp' . DS . 'Controller' . DS . 'Component' . DS . 'Acl' . DS,
			'View' => ROOT . DS . 'SiteApp' . DS . 'View' . DS,
			'View/Helper' => ROOT . DS . 'SiteApp' . DS . 'View' . DS . 'Helper' . DS,
			'Lib' => ROOT . DS . 'SiteApp' . DS . 'Lib' . DS,
			'Locale' => ROOT . DS . 'SiteApp' . DS . 'Locale' . DS,
			'Vendor' => ROOT . DS . 'SiteApp' . DS . 'Vendor' . DS,
			'Plugin' => ROOT . DS . 'SiteApp' . DS . 'Plugin' . DS
		),
	App::APPEND);
}

$plugins = App::objects('plugins', null, false);

if ($load_order = Cache::read('modules_load_order')) {
	$load_order = array_intersect($load_order, $plugins);
	$tail = array_diff($plugins, $load_order);
	$plugins = array_merge($load_order, $tail);
}

foreach ($plugins as $plugin) {
	if (!CakePlugin::loaded($plugin)) {
		CakePlugin::load($plugin, array('bootstrap' => true, 'routes' => true));
	}
}

$__coreModulesCache = Cache::read('core_modules');
$__coreThemesCache = Cache::read('core_themes');

if (!$__coreModulesCache || !$__coreThemesCache) {
	$plugins = App::objects('plugins', null, false);
	$__coreThemes = $__coreModules = array();

	foreach ($plugins as $plugin) {
		$__ppath = CakePlugin::path($plugin);

		if (strpos($__ppath, DS . 'Fields' . DS) !== false) {
			continue;
		}

		if (!$__coreModulesCache && QuickApps::is('module.core', $plugin)) {
			$__coreModules[] = $plugin;
		}

		if (!$__coreThemesCache && QuickApps::is('theme.core', $plugin)) {
			$__coreThemes[] = preg_replace('/^Theme/', '', $plugin);
		}
	}

	if (!$__coreModulesCache) {
		$__coreModulesCache = $__coreModules;
		Cache::write('core_modules', $__coreModules);
	}

	if (!$__coreThemesCache) {
		$__coreThemesCache = $__coreThemes;
		Cache::write('core_themes', $__coreThemes);
	}

	unset($__coreModules, $__coreThemes);
}

Configure::write('coreModules', $__coreModulesCache);
Configure::write('coreThemes', $__coreThemesCache);

unset($__plugin_paths, $plugins, $plugin, $__ppath, $__coreModulesCache, $__coreThemesCache);

/**
 * Translation function.
 *
 * @param string $singular String to translate
 * @return string The translated string
 * @see QuickApps::__t()
 */
function __t($singular, $args = null) {
	if (!is_array($args) && !is_null($args)) {
		$args = array_slice(func_get_args(), 1);
	}

	return QuickApps::__t($singular, $args);
}

/**
 * You can attach event listeners to the request lifecyle as Dispatcher Filter . By Default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 * 		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'FileLog',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'FileLog',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

include_once ROOT . DS . 'Config' . DS . 'bootstrap.php';