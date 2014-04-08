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
namespace Node\Config;

use Cake\Core\Configure;
use Cake\Routing\Router;

$node_types = implode('|', (array)Configure::read('QuickApps.node_types'));

if (!empty($node_types)) {
	Router::connect(
		'/:node_type_slug/:node_slug.html',
		[
			'plugin' => 'node',
			'controller' => 'serve',
			'action' => 'details'
		],
		[
			'node_type_slug' => $node_types,
			'node_slug' => '[a-z0-9\-]+',
			'pass' => ['node_type_slug', 'node_slug']
		]
	);
}

Router::connect(
	'/find/:criteria/*',
	[
		'plugin' => 'node',
		'controller' => 'serve',
		'action' => 'search'
	],
	[
		'pass' => ['criteria']
	]
);

Router::connect(
	'/rss/:criteria/*',
	[
		'plugin' => 'node',
		'controller' => 'serve',
		'action' => 'search'
	],
	[
		'pass' => ['criteria']
	]
);

Router::connect(
	'/',
	[
		'plugin' => 'node',
		'controller' => 'serve',
		'action' => 'frontpage'
	]
);

unset($node_types);
