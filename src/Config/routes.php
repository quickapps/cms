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

use QuickApps\Utility\Plugin;
use Cake\Core\Configure;
use Cake\Routing\Router;

/**
 * Admin prefix for backend access.
 */
Configure::write('Routing.prefixes', ['admin']);

/**
 * Redirect everything to installer plugin if it's a new QuickApps CMS package.
 */
if (!file_exists(SITE_ROOT . '/Config/settings.json')) {
	Router::redirect('/', '/installer/setup', ['status' => 302]);
	Router::redirect(
		'/:anything_but_installer',
		['plugin' => 'installer', 'controller' => 'startup'],
		['anything_but_installer' => '(?!installer).*', 'status' => 302]
	);
}

/**
 * Load site's routes.
 */
if (file_exists(SITE_ROOT . '/Config/routes.php')) {
	include_once SITE_ROOT . '/Config/routes.php';
}
Router::connect('/', array('controller' => 'Pages', 'action' => 'display', 'home'));
/**
 * Load all plugin routes.
 */
Plugin::routes();

/**
 * Load the CakePHP default routes.
 */
require CAKE . 'Config/routes.php';

/**
 * Try to detect language from URL.
 *
 * We accept either format:
 *
 * 1. http://example.com/eng/my/url When `url_locale_prefix` is TRUE
 * 2. http://example.com/my/url?locale=eng When `url_locale_prefix` is FALSE
 *
 */
if (
	Configure::read('QuickApps.variables.url_locale_prefix') &&
	!empty(Router::getRequest()->params['locale'])
) {
	Configure::write('Config.language', Router::getRequest()->params['locale']);
} elseif (
	!empty(Router::getRequest()->query['locale']) &&
	empty(Router::getRequest()->params['locale'])
) {
	Configure::write('Config.language', Router::getRequest()->query['locale']);
	Router::addUrlFilter(
		function ($params, $request) {
			if (isset($request->query['locale']) && !isset($params['locale'])) {
				$params['locale'] = $request->query['locale'];
			}

			return $params;
		}
	);
}

/**
 * Set language prefix (if enabled) on every route.
 *
 * This will create a locale-prefixed version of all registered
 * routes at this point.
 */
if (Configure::read('QuickApps.variables.url_locale_prefix')) {
	$langs = implode('|', Configure::read('QuickApps.active_languages'));
	$routes_count = Router::$_routes->count();

	for ($i = 0; $i < $routes_count; $i++) {
		$route = clone Router::$_routes->get($i);
		$route->options['locale'] = "{$langs}";
		$route->template = "/:locale{$route->template}";

		Router::$_routes->add($route);
	}

	for ($i = 0; $i < intval($routes_count / 2); $i++) {
		Router::promote(null);
	}

	Router::addUrlFilter(
		function ($params, $request) {
			if (isset($request->params['locale']) && !isset($params['locale'])) {
				$params['locale'] = $request->params['locale'];
			}

			return $params;
		}
	);

	unset($langs, $i, $routes_count);
}
