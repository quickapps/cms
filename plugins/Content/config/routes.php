<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */

use Cake\Routing\Router;
use CMS\View\ViewModeRegistry;

/**
 * Register default view-modes
 */
ViewModeRegistry::add([
    'default' => [
        'name' => __d('content', 'Default'),
        'description' => __d('content', 'Default is used as a generic view mode if no other view mode has been defined for your content.'),
    ],
    'teaser' => [
        'name' => __d('content', 'Teaser'),
        'description' => __d('content', 'Teaser is a really short format that is typically used in main the main page, such as "last news", etc.'),
    ],
    'search-result' => [
        'name' => __d('content', 'Search Result'),
        'description' => __d('content', 'Search Result is a short format that is typically used in lists of multiple content items such as search results.'),
    ],
    'rss' => [
        'name' => __d('content', 'RSS'),
        'description' => __d('content', 'RSS is similar to "Search Result" but intended to be used when rendering content as part of a RSS feed list.'),
    ],
    'full' => [
        'name' => __d('content', 'Full'),
        'description' => __d('content', 'Full content is typically used when the content is displayed on its own page.'),
    ],
]);

if (is_array(quickapps('content_types'))) {
    Router::connect('/:content_type_slug/:content_slug' . CONTENT_EXTENSION, [
        'plugin' => 'Content',
        'controller' => 'Serve',
        'action' => 'details'
    ], [
        'content_type_slug' => implode('|', array_map('preg_quote', quickapps('content_types'))),
        'content_slug' => '.+',
        'pass' => ['content_type_slug', 'content_slug'],
        '_name' => 'content_details',
        'routeClass' => 'Cake\Routing\Route\InflectedRoute',
    ]);
}

Router::connect('/find/:criteria', [
    'plugin' => 'Content',
    'controller' => 'Serve',
    'action' => 'search',
], [
    'pass' => ['criteria'],
    '_name' => 'content_search',
    'routeClass' => 'Cake\Routing\Route\InflectedRoute',
]);

Router::connect('/rss/:criteria', [
    'plugin' => 'Content',
    'controller' => 'Serve',
    'action' => 'rss',
], [
    'pass' => ['criteria'],
    '_name' => 'content_search_rss',
    'routeClass' => 'Cake\Routing\Route\InflectedRoute'
]);

Router::connect('/', [
    'plugin' => 'Content',
    'controller' => 'Serve',
    'action' => 'home'
], [
    '_name' => 'home',
    'routeClass' => 'Cake\Routing\Route\InflectedRoute'
]);
