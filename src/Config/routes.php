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

use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use QuickApps\Utility\Plugin;

if (!file_exists(SITE_ROOT . '/Config/settings.php')) {
/**
 * Redirect everything to installer plugin if we are on a new QuickApps CMS package.
 */
	Router::redirect(
		'/:anything_but_installer',
		['plugin' => 'Installer', 'controller' => 'startup'],
		['anything_but_installer' => '(?!installer).*', 'status' => 302]
	);

	Router::plugin('Installer', function($routes) {
		$routes->connect('/:controller', ['action' => 'index']);
		$routes->connect('/:controller/:action/*', []);
	});
} else {

/**
 * Generate basic routes.
 */
	$localePrefix = Configure::read('QuickApps.variables.url_locale_prefix');
	$locales = array_keys(Configure::read('QuickApps.languages'));
	$localesPattern = '[' . implode('|', array_map('preg_quote', $locales)) . ']';

	Router::prefix('admin', function($routes) {
		foreach (Plugin::loaded() as $plugin) {
			$routes->plugin($plugin, function($routes) {
				$routes->connect('', ['controller' => 'manage', 'action' => 'index']);
				$routes->connect('/:controller', ['action' => 'index']);
				$routes->connect('/:controller/:action/*', []);
			});
		}
	});

	foreach (Plugin::loaded() as $plugin) {
		Router::plugin($plugin, function($routes) {
			$routes->connect('', ['controller' => 'main', 'action' => 'index']);
			$routes->connect('/:controller', ['action' => 'index']);
			$routes->connect('/:controller/:action/*', []);
		});
	}

/**
 * Load plugin routes.
 */
	Plugin::routes();

/**
 * Load site's routes.
 */
	if (file_exists(SITE_ROOT . '/Config/routes.php')) {
		include_once SITE_ROOT . '/Config/routes.php';
	}

/**
 * Set language prefix (if enabled) on every route.
 */
	if ($localePrefix) {
		foreach (Router::routes() as $router) {
			foreach ($locales as $code) {
				Router::connect("/{$code}{$router->template}", $router->defaults, $router->options);
			}
		}

		Router::addUrlFilter(
			function ($params, $request) use ($localesPattern) {
				if (
					empty($params['_name']) &&
					(empty($params['_base']) || !preg_match("/\/{$localesPattern}\//", $params['_base']))
				) {
					$params['_base'] = $request->base . '/' . Configure::read('Config.language') . '/';
				}
				return $params;
			}
		);
	}

}
