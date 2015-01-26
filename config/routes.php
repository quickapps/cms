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
namespace QuickApps\Config;

use Cake\I18n\I18n;
use Cake\Routing\Router;
use QuickApps\Core\Plugin;

if (!file_exists(SITE_ROOT . '/config/settings.php')) {
/**
 * Redirect everything to installer plugin if we are on a new QuickApps CMS package.
 */
    Router::redirect(
        '/:anything_but_installer',
        ['plugin' => 'Installer', 'controller' => 'startup'],
        ['anything_but_installer' => '(?!installer).*', 'status' => 302]
    );

    Router::plugin('Installer', function($routes) {
        $routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'Cake\Routing\Route\DashedRoute']);
        $routes->connect('/:controller/:action/*', [], ['routeClass' => 'Cake\Routing\Route\DashedRoute']);
    });
} else {

/**
 * Generate basic routes.
 */
    $localePrefix = option('url_locale_prefix');
    $locales = array_keys(quickapps('languages'));
    $localesPattern = '(' . implode('|', array_map('preg_quote', $locales)) . ')';

    Router::prefix('admin', function($routes) {
        foreach (Plugin::loaded() as $plugin) {
            $routes->plugin($plugin, function($routes) {
                $routes->connect('', ['controller' => 'manage', 'action' => 'index'], ['routeClass' => 'Cake\Routing\Route\DashedRoute']);
                $routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'Cake\Routing\Route\DashedRoute']);
                $routes->connect('/:controller/:action/*', [], ['routeClass' => 'Cake\Routing\Route\DashedRoute']);
            });
        }
    });

    foreach (Plugin::loaded() as $plugin) {
        Router::plugin($plugin, function($routes) {
            $routes->connect('', ['controller' => 'main', 'action' => 'index'], ['routeClass' => 'Cake\Routing\Route\DashedRoute']);
            $routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'Cake\Routing\Route\DashedRoute']);
            $routes->connect('/:controller/:action/*', [], ['routeClass' => 'Cake\Routing\Route\DashedRoute']);
        });
    }

/**
 * Load plugin routes.
 */
    Plugin::routes();

/**
 * Load site's routes.
 */
    if (file_exists(SITE_ROOT . '/config/routes.php')) {
        include_once SITE_ROOT . '/config/routes.php';
    }

/**
 * Set language prefix (if enabled) on every route.
 */
    if ($localePrefix) {
        foreach (Router::routes() as $router) {
            $options = $router->options;
            $options['routeClass'] = empty($options['routeClass']) ? 'Cake\Routing\Route\DashedRoute' : $options['routeClass'];
            if (!empty($options['_name'])) {
                $options['locale'] = $localesPattern;
                $template = str_replace('//', '/', "/:locale/{$router->template}");
                Router::connect($template, $router->defaults, $options);
            } else {
                foreach ($locales as $code) {
                    Router::connect("/{$code}{$router->template}", $router->defaults, $options);
                }
            }
        }

        Router::addUrlFilter(
            function ($params, $request) use ($localesPattern) {
                if (!empty($params['_name'])) {
                    $params['locale'] = I18n::defaultLocale();
                } elseif (
                    empty($params['_base']) ||
                    !preg_match("/\/{$localesPattern}\//", $params['_base'])
                ) {
                    $params['_base'] = $request->base . '/' . I18n::defaultLocale() . '/';
                }
                return $params;
            }
        );
    }
}
