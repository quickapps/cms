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
namespace QuickApps\Core;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Core\Plugin as CakePlugin;
use Cake\Error\FatalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

/**
 * Plugin is used to load and locate plugins.
 *
 * Wrapper for `Cake\Core\Plugin`, it adds some QuickAppsCMS specifics methods.
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
	public static function collection($extendedInfo = false) {
		$collection = new Collection((array)Configure::read('QuickApps.plugins'));

		if ($extendedInfo) {
			$collection = $collection->map(function ($info, $key) {
				return Plugin::info($key, true);
			});
		}

		return $collection;
	}

/**
 * Gets information for a single plugin.
 *
 * When `$full` is set to true composer info is merged into the `composer` key,
 * and DB settings under `settings` key.
 *
 * ### Example:
 *
 *     $pluginInfo = Plugin::info('User', true);
 *     // out:
 *     [
 *         'name' => 'User,
 *         'isTheme' => false,
 *         'isCore' => true,
 *         'hasHelp' => true,
 *         'hasSettings' => false,
 *         'events' => [ ... ],
 *         'status' => 1,
 *         'path' => '/path/to/plugin',
 *         'composer' => [ ... ], // only when $full = true
 *         'settings' => [ ... ], // only when $full = true
 *     ]
 *
 * @param string $plugin Plugin name. e.g. `Node`
 * @param boolean $full Merge info with plugin's `composer.json` file and settings stored in DB
 * @return array Plugin information
 * @throws Cake\Error\FatalErrorException When plugin is not found, or when JSON file is not found
 */
	public static function info($plugin, $full = false) {
		$plugin = Inflector::camelize($plugin);
		$info = (array)Configure::read('QuickApps.plugins.' . $plugin);

		if (!$info) {
			throw new FatalErrorException(__('system', 'Plugin "%s" was not found', $plugin));
		}

		if ($full) {
			if (!file_exists($info['path'] . '/composer.json')) {
				throw new FatalErrorException(__('system', 'Missing composer.json for plugin "%s"', $plugin));
			}
			$json = Hash::merge(static::$_defaultComposerJson, json_decode(file_get_contents($info['path'] . '/composer.json'), true));
			$info['composer'] = $json;
			$info['settings'] = [];
			$dbInfo = TableRegistry::get('System.Plugins')
				->find()
				->select(['Plugins.settings'])
				->where(['name' => $plugin])
				->first();

			if ($dbInfo) {
				$info['settings'] = (array)$dbInfo->settings;
			}
		}

		return (array)$info;
	}

}
