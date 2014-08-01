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
namespace QuickApps\Utility;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Core\Plugin as CakePlugin;
use Cake\Error\FatalErrorException;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

/**
 * Plugin is used to load and locate plugins.
 *
 * Wrapper for Cake\Core\Plugin, it adds some QuickAppsCMS specifics methods.
 */
class Plugin extends CakePlugin {

/**
 * Default options for composer's json file.
 *
 * @var array
 */
	private static $_defaultComposerJson = [
		'name' => null,
		'description' => null,
		'version' => null,
		'type' => null,
		'keywords' => [],
		'homepage' => null,
		'time' => null,
		'license' => null,
		'authors' => [],
		'support' => [
			'email' => null,
			'issues' => null,
			'forum' => null,
			'wiki' => null,
			'irc' => null,
			'source' => null,
		],
		'require' => [],
		'require-dev' => [],
		'conflict' => [],
		'replace' => [],
		'provide' => [],
		'suggest' => [],
		'autoload' => [
			'psr-4' => [],
			'psr-0' => [],
			'classmap' => [],
			'files' => [],
		],
		'autoload-dev' =>[
			'psr-4' => [],
			'psr-0' => [],
			'classmap' => [],
			'files' => [],
		],
		'target-dir' => null,
		'minimum-stability' => null,
		'repositories' => [],
		'config' => [],
		'archive' => [],
		'prefer-stable' => true,
		'scripts' => [],
		'extra' => [],
		'bin' => [],
	];

/**
 * Gets plugins information as a collection object.
 *
 * @param boolean $extendedInfo Set to true to get extended information for each plugin
 * @return \Cake\Collection\Collection
 */
	public static function getCollection($extendedInfo = false) {
		$collection = new Collection((array)Configure::read('QuickApps.plugins'));

		if ($extendedInfo) {
			$collection = $collection->map(function ($plugin, $key) {
				return Plugin::getInfo($key);
			});
		}

		return $collection;
	}

/**
 * Gets information for a single plugin.
 *
 * @param string $plugin Plugin name. e.g. `Node`
 * @param boolean $includeJson Merge info with plugin's `composer.json` file
 * @return array Plugin information
 * @throws Cake\Error\FatalErrorException When plugin is not found
 */
	public static function getInfo($plugin, $includeJson = true) {
		$plugin = Inflector::camelize($plugin);
		$info = (array)Configure::read('QuickApps.plugins.' . $plugin);

		if (!$info) {
			throw new FatalErrorException(__('system', 'Plugin "%s" was not found', $plugin));
		}

		if ($includeJson) {
			$json = Hash::merge(static::$_defaultComposerJson, json_decode(file_get_contents($info['path'] . '/composer.json'), true));
			$info = Hash::merge($info, $json);
		}

		return (array)$info;
	}

}
