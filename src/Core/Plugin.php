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
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Plugin as CakePlugin;
use Cake\Error\FatalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Utility\File;
use Cake\Utility\Folder;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use QuickApps\Core\StaticCacheTrait;

/**
 * Plugin is used to load and locate plugins.
 *
 * Wrapper for `Cake\Core\Plugin`, it adds some QuickAppsCMS specifics methods.
 */
class Plugin extends CakePlugin {

	use StaticCacheTrait;

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
 * @param boolean $ignoreError Set to true to ignore error messages when a corrupt
 * plugin is found. Defaults to true
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
 * Scan plugin directories and returns plugin names and their paths within file system.
 * We consider "plugin name" as the name of the container directory.
 * 
 * Example output:
 *
 *     [
 *         'Users' => '/full/path/plugins/Users',
 *         'ThemeManager' => '/full/path/plugins/ThemeManager',
 *         ...
 *         'MySuperPlugin' => '/full/path/plugins/MySuperPlugin',
 *         'DarkGreenTheme' => '/full/path/plugins/DarkGreenTheme',
 *     ]
 *
 * If $ignoreThemes is set to true `DarkGreenTheme` will not be part of the result
 *
 * @param bool $ignoreThemes Whether include themes as well or not
 * @return array Associative array as `PluginName` => `full/path/to/PluginName`
 */
	public static function scan($ignoreThemes = false) {
		$cacheKey = 'scan';
		$cache = static::cache($cacheKey);
		if (!$cache) {
			$cache = [];
			$paths = App::path('Plugin');
			$Folder = new Folder();
			foreach ($paths as $path) {
				$Folder->cd($path);
				foreach ($Folder->read(false, true, true)[0] as $dir) {
					$name = basename($dir);
					if ($ignoreThemes && str_ends_with($name, 'Theme')) {
						continue;
					}
					$cache[$name] = $dir;
				}
			}
		}
		return $cache;
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

		if ($cache = static::cache($cacheKey)) {
			return $cache;
		}

		$info = quickapps("plugins.{$plugin}");
		if (!$info) {
			throw new FatalErrorException(__('Plugin "{0}" was not found', $plugin));
		}

		if ($full) {
			$json = static::composer($plugin);

			if (!$json) {
				throw new FatalErrorException(__('Missing or corrupt "composer.json" file for plugin "{0}"', $plugin));
			}

			$json = Hash::merge(static::$_defaultComposerJson, $json);
			$info['composer'] = $json;
			$info['settings'] = [];
			$dbInfo = TableRegistry::get('System.Plugins')
				->find()
				->select(['name', 'settings'])
				->where(['name' => $plugin])
				->first();

			if ($dbInfo) {
				$info['settings'] = (array)$dbInfo->settings;
			}
		}

		static::cache($cacheKey, $info);
		return (array)$info;
	}

/**
 * Gets composer json information for the given plugin.
 * 
 * @param string $plugin Plugin alias, e.g. `UserManager` or `user_manager`
 * @return mixed False if composer.json is missing or corrupt, or composer info
 * as an array if valid composer.json is found
 */
	public static function composer($plugin) {
		$plugin = Inflector::camelize($plugin);
		$cacheKey = "composer({$plugin})";

		if ($cache = static::cache($cacheKey)) {
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

		static::cache($cacheKey, $json);
		return $json;
	}

/**
 * Validates a composer.json file.
 *
 * Below a list of validation rules that are applied:
 *
 * - must be a valid JSON file.
 * - key `name` must be present. A follow the patter `author/package`
 * - key `version` must be present.
 * - key `type` must be present and be "quickapps-plugin" (even if it's a theme).
 * - key `name` must be present.
 * - key `description` must be present.
 * - key `extra.regions` must be present if it's a theme (its name ends with
 *   `-theme`, e.g. `quickapps/blue-sky-theme`)
 *
 * ### Usage:
 *
 *     $json = json_decode(file_gets_content('/path/to/composer.json'), true);
 *     Plugin::validateJson($json);
 *     // OR:
 *     Plugin::validateJson('/path/to/composer.json');
 * 
 * @param array|string $json JSON given as an array result of `json_decode(..., true)`,
 * or a string as path to where .json file can be found
 * @param bool $errorMessages If set to true an array of error messages
 * will be returned, if set to false boolean result will be returned; true on
 * success, false on validation failure failure. Defaults to false (boolean result)
 * @return array|bool
 */
	public static function validateJson($json, $errorMessages = false) {
		if (is_string($json) && file_exists($json) && !is_dir($json)) {
			$json = json_decode((new File($json))->read(), true);
		}

		$errors = [];
		if (!is_array($json) || empty($json)) {
			$errors[] = __('Corrupt JSON information.');
		} else {
			if (!isset($json['version'])) {
				$errors[] = __('Missing field: "{0}"', 'version');
			}

			if (!isset($json['type'])) {
				$errors[] = __('Missing field: "{0}"', 'type');
			} elseif ($json['type'] !== 'quickapps-plugin') {
				$errors[] = __('Invalid field: "{0}" ({1}). It should be: {2}', 'type', $json['type'], 'quickapps-plugin');
			}

			if (!isset($json['name'])) {
				$errors[] = __('Missing field: "{0}"', 'name');
			} elseif (!preg_match('/^(.+)\/(.+)+$/', $json['name'])) {
				$errors[] = __('Invalid field: "{0}" ({1}). It should be: {2}', 'name', $json['name'], '{author-name}/{package-name}');
			} elseif (str_ends_with(strtolower($json['name']), 'theme')) {
				if (!isset($json['extra']['regions'])) {
					$errors[] = __('Missing field: "{0}"', 'extra.regions');
				}
			}

			if (!isset($json['description'])) {
				$errors[] = __('Missing field: "{0}"', 'description');
			}
		}

		if ($errorMessages) {
			return $errors;
		}

		return empty($errors);
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

		if ($cache = static::cache($cacheKey)) {
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

		static::cache($cacheKey, $settings);
		return $settings;
	}

/**
 * Gets plugin's dependencies as an array list.
 *
 * This method returns package names that follows the pattern `author-name/package`.
 * Packages such as `ext-mbstring`, etc will be ignored (EXCEPT `php`).
 * The special package name `__QUICKAPPS__` represent QuickApps CMS's version, and
 * `__PHP__` represents server's PHP version.
 *
 * ### Example:
 *
 *     // Get plugin's composer.json and extract dependencies
 *     Plugin::dependencies('UserManager');
 *     // may returns:
 *     [
 *         'UserWork' => '1.0',
 *         'Calentar' => '1.0.*',
 *         '__QUICKAPPS__' => '>1.0', // QuickApps CMS v1.0 or higher required,
 *         '__PHP__' => '>4.3'
 *     ]
 *
 *     // Directly from composer.json information
 *     Plugin::dependencies(json_decode('/path/to/composer.json', true));
 *
 * @param array|string $plugin Plugin alias, or an array representing a 
 * "composer.json" file, that is, result of `json_decode(..., true)`
 * @return array List of plugin & version that $plugin depends on
 * @throws \Cake\Eror\FatalErrorException When $plugin is not found, or when
 * plugin's composer.json is missing or corrupt
 */
	public static function dependencies($plugin) {
		if (is_array($plugin)) {
			if (isset($plugin['require'])) {
				$info['composer']['require'] = $plugin['require'];
			} else {
				return [];
			}
		} else {
			$info = static::info($plugin, true);
		}
		$dependencies = [];
		if (!empty($info['composer']['require'])) {
			foreach ($info['composer']['require'] as $name => $version) {
				$name = pluginName($name);
				if (!$name) {
					continue;
				}
				$dependencies[$name] = $version;
			}
		}

		return $dependencies;
	}

/**
 * Check if plugin is dependent on any other plugin.
 * If yes, check if that plugin is available (installed and enabled).
 *
 * ### Usage:
 *
 *     // Check requirements for MyPlugin
 *     Plugin::checkDependency('MyPlugin');
 *     
 *     // Check requirements from composer.json
 *     Plugin::checkDependency(json_decode('/path/to/composer.json', true));
 *
 * @param string|array $plugin Plugin alias, or an array representing "composer.json"
 * @return bool True if everything is OK, false otherwise
 */
	public static function checkDependency($plugin) {
		$dependencies = static::dependencies($plugin);

		foreach ($dependencies as $plugin => $required) {
			if (in_array($plugin, ['__PHP__', '__QUICKAPPS__'])) {
				if ($plugin === '__PHP__') {
					$current = PHP_VERSION;
				} else {
					$current = quickapps('version');
				}
			} else {
				try {
					$info = static::info($plugin, true);
				} catch (FatalErrorException $e) {
					$current = false;
				}

				// installed, but disabled
				if (!$info['status']) {
					return false;
				}

				if (!empty($info['version'])) {
					$current = $info['version'];
				} else {
					$current = false;
				}
			}

			if ($current) {
				if (!static::checkIncompatibility(static::parseDependency($required), $current)) {
					return false;
				}
			} else {
				return false;
			}
		}

		return true;
	}

/**
 * Verify if there is any plugin that depends of $plugin.
 *
 * @param string $pluginName Plugin name to check
 * @return array A list of all plugin names that depends on $plugin, an empty array
 * means that no other plugins depends on $plugin, so $plugin can be safely deleted
 * or turned off.
 */
	public static function checkReverseDependency($pluginName) {
		$out = [];
		foreach (static::collection(true) as $plugin) {
			if ($plugin['name'] === $pluginName) {
				continue;
			}
			if (isset($plugin['composer']['require'])) {
				$packages = array_keys($plugin['composer']['require']);
				$packages = array_map('pluginName', $packages);
				if (in_array($pluginName, $packages)) {
					$out[] = $plugin['human_name'];
				}
			}
		}
		return $out;
	}

/**
 * Parse a dependency for comparison.
 *
 * ### Usage:
 *
 *     Plugin::parseDependency('>=7.x-4.5-beta5,3.x');
 *
 * @param string $dependency A dependency string as example above
 * @return array An associative array with three keys as below, callers should
 * pass this structure to `checkIncompatibility()`:
 * - `original`: Contains the original version string ($dependency)
 * - `versions`: Is a list of associative arrays, each containing the keys
 *   'op' and 'version'. 'op' can be one of: '=', '==', '!=', '<>', '<',
 *   '<=', '>', or '>='. 'version' is one piece like '4.5-beta3' or '5.5.11'.
 */
	public static function parseDependency($dependency) {
		$p_op = '(?P<operator>!=|==|<|<=|>|>=|<>)?';
		$p_major = '(?P<major>\d+)';
		$p_minor = '(?P<minor>(?:\d+|\*)?)';
		$p_fix = '(?P<fix>(?:\d+|\*)?)';
		$p_tail = '(?P<tail>(?:-[A-Za-z]+\d*)?)';
		$out = [
			'original' => $dependency,
			'versions' => [],
		];

		foreach (explode(',', $dependency) as $version) {
			$version = trim($version);
			if (preg_match("/^{$p_op}{$p_major}\.?{$p_minor}\.?{$p_fix}{$p_tail}/", $version, $matches)) {
				$op = empty($matches['operator']) ? '==' : $matches['operator'];
				$matches['minor'] = $matches['minor'] === '*' ? 'x' : $matches['minor'];
				$matches['fix'] = $matches['fix'] === '*' ? 'x' : $matches['fix'];
				$matches['minor'] = $matches['minor'] === '' ? 0 : $matches['minor'];
				$matches['fix'] = $matches['fix'] === '' ? 0 : $matches['fix'];

				if ($matches['fix'] === 'x') {
					if ($op === '>' || $op === '<=') {
						$matches['minor']++;
					}

					if ($op === '=' || $op === '==') {
						$out['versions'][] = [
							'op' => '<',
							'version' => $matches['major'] . '.' . ($matches['minor'] + 1)
						];
						$op = '>=';
					}

					$matches['fix'] = '';
				}

				if ($matches['minor'] === 'x') {
					if ($op === '>' || $op === '<=') {
						$matches['major']++;
					}

					if ($op === '=' || $op=== '==') {
						$out['versions'][] = [
							'op' => '<',
							'version' => ($matches['major'] + 1) . '.x'
						];
						$op = '>=';
					}
				}

				$matches['fix'] = empty($matches['fix']) ? '' : '.' . $matches['fix'];
				$v = preg_replace('/\.{1,}$/', '', $matches['major'] . '.' . $matches['minor'] . $matches['fix']);
				$out['versions'][] = [
					'op' => $op,
					'version' => $v . $matches['tail'],
				];
			}
		}

		return $out;
	}

/**
 * Check whether a version is compatible with a given dependency.
 *
 * @param array $v The parsed dependency structure from `parseDependency()`
 * @param string $current The version to check against (e.g.: 4.2)
 * @return bool True if compatible, false otherwise
 */
	public static function checkIncompatibility($v, $current) {
		if (!empty($v['versions'])) {
			foreach ($v['versions'] as $required) {
					$aIsBranch = 'dev-' === substr($current, 0, 4);
					$bIsBranch = 'dev-' === substr($required['version'], 0, 4);
					if ($aIsBranch && $bIsBranch) {
						if (!($required['op'] === '==' && $current === $$required['version'])) {
							return false;
						}
					}

				if (isset($required['op']) && !version_compare($current, $required['version'], $required['op'])) {
					return false;
				}
			}
		}
		return true;
	}

}
