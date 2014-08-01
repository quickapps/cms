<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Node\Config;

use QuickApps\Utility\ViewModeRegistry;

ViewModeRegistry::registerViewMode([
	'default' => [
		'name' => __d('node', 'Default'),
		'description' => __d('node', 'Default is used as a generic view mode if no other view mode has been defined for your content.'),
	],
	'teaser' => [
		'name' => __d('node', 'Teaser'),
		'description' => __d('node', 'Teaser is a really short format that is typically used in main the main page, such as "last news", etc.'),
	],
	'search-result' => [
		'name' => __d('node', 'Search Result'),
		'description' => __d('node', 'Search Result is a short format that is typically used in lists of multiple content items such as search results.'),
	],
	'rss' => [
		'name' => __d('node', 'RSS'),
		'description' => __d('node', 'RSS is similar to "Search Result" but intended to be used when rendering content as part of a RSS feed list.'),
	],
	'full' => [
		'name' => __d('node', 'Full'),
		'description' => __d('node', 'Full content is typically used when the content is displayed on its own page.'),
	],
]);
