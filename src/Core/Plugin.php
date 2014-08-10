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
use QuickApps\Utility\CacheTrait;

/**
 * Plugin is used to load and locate plugins.
 *
 * Wrapper for `Cake\Core\Plugin`, it adds some QuickAppsCMS specifics methods.
 */
class Plugin extends CakePlugin {

	use CacheTrait;

/**
 * Default options for composer's json file.
 *
 * @var array
 */
	protected static $_defaultComposerJson = [
		'name' => null,
		'description' => null,
		'version' => 'dev',
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
 * Gets all plugins information as a collection object.
 *
 * When $ignoreError is set to true and a corrupt plugin is found, it will
 * be removed from the resulting collection.
 *
 * @param boolean $extendedInfo Set to true to get extended information for each plugin
 * @param boolean $ignoreError Set to true to ignore error messages when a corrupt plugin is found. Defaults to true
 * @return \Cake\Collection\Collection
 */
	public static function collection($extendedInfo = false, $ignoreError = true) {
		$collection = new Collection(quickapps('plugins'));

		if ($extendedInfo) {
			$collection = $collection->map(function ($info, $key) use($ignoreError) {
				try {
					$out = Plugin::info($key, true);
				} catch (FatalErrorException $e) {
					if (!$ignoreError) {
						throw $e;
					} else {
						return false;
					}
				}

				return $out;
			});

			$collection = $collection->filter(function($value, $key) {
				return $value !== false;
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
		$cacheKey = "info({$plugin},{$full})";

		if ($cache = static::_cache($cacheKey)) {
			return $cache;
		}

		$info = quickapps("plugins.{$plugin}");
		if (!$info) {
			throw new FatalErrorException(__('Plugin "%s" was not found', $plugin));
		}

		if ($full) {
			$json = static::composer($plugin);

			if (!$json) {
				throw new FatalErrorException(__('Missing or corrupt "composer.json" file for plugin "%s"', $plugin));
			}

			$json = Hash::merge(static::$_defaultComposerJson, $json);
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

		static::_cache($cacheKey, $info);
		return (array)$info;
	}

/**
 * Gets composer json information for the given plugin.
 * 
 * @param string $plugin Plugin alias, e.g. `UserManager` or `user_manager`
 * @return mixed False if composer.json is missing or corrupt, or composer info as an array if valid composer.json is found
 */
	public static function composer($plugin) {
		$plugin = Inflector::camelize($plugin);
		$cacheKey = "composer({$plugin})";

		if ($cache = static::_cache($cacheKey)) {
			return $cache;
		}

		$info = static::info($plugin, false);

		if (!file_exists($info['path'] . '/composer.json')) {
			return false;
		}

		$json = json_decode(file_get_contents($info['path'] . '/composer.json'), true);

		if (!static::validateJson($json)) {
			return false;
		}

		static::_cache($cacheKey, $json);
		return $json;
	}

/**
 * Validates a composer.json file.
 *
 * Below a list of validation rules that are applied:
 *
 * - must be a valid JSON file.
 * - key `version` must be present.
 * - key `type` must be present and be "quickapps-plugin".
 * - key `name` must be present.
 * - key `description` must be present.
 * - key `extra.regions` must be present if it's a theme (its name ends with `-theme`, e.g. `quickapps/blue-sky-theme`)
 *
 * ### Usage:
 *
 *     $json = json_decode(file_gets_content('/path/to/composer.json'), true);
 *     Plugin::validateJson($json);
 *     // OR:
 *     Plugin::validateJson('/path/to/composer.json');
 * 
 * @param array|string $json JSON given as an array result of `json_decode(..., true)`, or a string as path to where .json file can be found
 * @return boolean
 */
	public static function validateJson($json) {
		if (is_string($json)) {
			$json = json_decode($json, true);
		}

		if (!is_array($json) || empty($json)) {
			return false;
		}

		$ok =
			isset($json['version']) &&
			isset($json['type']) &&
			$json['type'] === 'quickapps-plugin' &&
			isset($json['name']) &&
			isset($json['description']);

		if (isset($json['name']) && str_ends_with(strtolower($json['name']), 'theme')) {
			$ok = $ok && isset($json['extra']['regions']);
			if (!isset($json['extra']['admin'])) {
				$json['extra']['admin'] = false;
			}
		}

		return $ok;
	}

/**
 * Gets settings from DB for given plugin.
 * 
 * @param string $plugin Plugin alias, e.g. `UserManager` or `user_manager`
 * @return array
 */
	public static function settings($plugin) {
		$plugin = Inflector::camelize($plugin);
		$cacheKey = "settings({$plugin})";

		if ($cache = static::_cache($cacheKey)) {
			return $cache;
		}

		$settings = [];
		$PluginsTable = TableRegistry::get('Plugins');
		$PluginsTable->schema(['settings' => 'serialized']);
		$dbInfo = $PluginsTable
			->find()
			->select(['settings'])
			->where(['name' => $plugin])
			->first();

		if ($dbInfo) {
			$settings = (array)$dbInfo->settings;
		}

		static::_cache($cacheKey, $settings);
		return $settings;
	}

/**
 * Gets plugin's dependencies.
 *
 * ### Example:
 *
 *     Plugin::dependencies('UserManager');
 *     // may returns:
 *     [
 *         'UserWork' => '1.0',
 *         'Calentar' => '1.0.*',
 *     ]
 *
 * @param string $plugin Plugin alias, e.g. `UserManager` or `user_manager`
 * @return array List of plugin & version that $plugin depends on
 * @throws \Cake\Eror\FatalErrorException When $plugin is not found, or when composer.json is missing/corrupt
 */
	public static function dependencies($plugin) {
		$info = static::info($plugin, true);debug($info);
		$dependencies = [];
		if (!empty($info['composer']['require'])) {
			foreach ($info['composer']['require'] as $name => $version) {
				if ($name == 'quickapps/cms') {
					continue;
				}
				$parts = explode('/', $name);
				$dependencies[Inflector::camelize(end($parts))] = $version;
			}
		}

		return $dependencies;
	}

/**
 * Check if plugin is dependent on any other plugin.
 * If yes, check if that plugin is available (installed and enabled).
 *
 * @param string $plugin plugin alias
 * @return boolean True if everything is OK, false otherwise
 */
	public static function checkDependency($plugin) {
		$dependencies = static::dependencies($plugin);
		foreach ($dependencies as $p => $v) {
			try {
				$info = static::info($p, true);
			} catch (FatalErrorException $e) {
				return false;
			}

			// TODO: Plugin::checkDependency(), do some version compare
		}

		return true;
	}

}
