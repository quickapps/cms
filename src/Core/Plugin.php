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
namespace QuickApps\Core;

use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Plugin as CakePlugin;
use Cake\Error\FatalErrorException;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Composer\Package\LinkConstraint\VersionConstraint;
use Composer\Package\Version\VersionParser;
use QuickApps\Core\StaticCacheTrait;

/**
 * Plugin is used to load and locate plugins.
 *
 * Wrapper for `Cake\Core\Plugin`, it adds some QuickAppsCMS specifics methods.
 */
class Plugin extends CakePlugin
{

    use StaticCacheTrait;

    /**
     * Default options for composer's json file.
     *
     * @var array
     * @see https://getcomposer.org/doc/04-schema.md
     */
    protected static $_defaultComposerSchema = [
        'name' => null,
        'description' => '---',
        'version' => 'dev-master',
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
        'autoload-dev' => [
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
     * @param bool $extendedInfo Set to true to get extended information for each
     *  plugin, extended information includes "composer.json" and plugin's DB settings
     * @param bool $ignoreError Set to true to ignore error messages when a
     *  corrupt plugin is found. Defaults to true
     * @return \Cake\Collection\Collection
     * @throws \Cake\Error\FatalErrorException When a corrupt plugin is found and
     *  $ignoreError is set to false
     */
    public static function collection($extendedInfo = false, $ignoreError = true)
    {
        $collection = collection(quickapps('plugins'));

        if ($extendedInfo) {
            $collection = $collection->map(function ($info, $key) use ($ignoreError) {
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

            $collection = $collection->filter(function ($value, $key) {
                return $value !== false;
            });
        }

        return $collection;
    }

    /**
     * Scan plugin directories and returns plugin names and their paths within file
     * system. We consider "plugin name" as the name of the container directory.
     *
     * Example output:
     *
     * ```php
     * [
     *     'Users' => '/full/path/plugins/Users',
     *     'ThemeManager' => '/full/path/plugins/ThemeManager',
     *     ...
     *     'MySuperPlugin' => '/full/path/plugins/MySuperPlugin',
     *     'DarkGreenTheme' => '/full/path/plugins/DarkGreenTheme',
     * ]
     * ```
     *
     * If $ignoreThemes is set to true `DarkGreenTheme` will not be part of the result
     *
     * @param bool $ignoreThemes Whether include themes as well or not
     * @return array Associative array as `PluginName` => `full/path/to/PluginName`
     */
    public static function scan($ignoreThemes = false)
    {
        $cacheKey = "scan_{$ignoreThemes}";
        $cache = static::cache($cacheKey);
        if (!$cache) {
            $cache = [];
            $paths = App::path('Plugin');
            $Folder = new Folder();
            $Folder->sort = true;
            foreach ($paths as $path) {
                $Folder->cd($path);
                foreach ($Folder->read(true, true, true)[0] as $dir) {
                    $name = basename($dir);
                    if ($ignoreThemes && str_ends_with($name, 'Theme')) {
                        continue;
                    }
                    $cache[$name] = normalizePath($dir);
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
     * ```php
     * $pluginInfo = Plugin::info('User', true);
     * // out: [
     * //   'name' => 'User,
     * //   'isTheme' => false,
     * //   'isCore' => true,
     * //   'hasHelp' => true,
     * //   'hasSettings' => false,
     * //   'events' => [ ... ],
     * //   'status' => 1,
     * //   'path' => '/path/to/plugin',
     * //   'composer' => [ ... ], // only when $full = true
     * //   'settings' => [ ... ], // only when $full = true
     * // ]
     * ```
     *
     * @param string $plugin Plugin name. e.g. `Node`
     * @param bool $full Merge info with plugin's `composer.json` file and
     *  settings stored in DB
     * @return array Plugin information
     * @throws \Cake\Error\FatalErrorException When plugin is not found, or when
     *  JSON file is not found
     */
    public static function info($plugin, $full = false)
    {
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

            $json = Hash::merge(static::$_defaultComposerSchema, $json);
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
     * Gets version number of the given plugin.
     *
     * @param string $plugin Plugin name
     * @return string Plugin's version
     */
    public static function version($plugin)
    {
        $composer = static::composer($plugin);
        return $composer['version'];
    }

    /**
     * Gets composer json information for the given plugin.
     *
     * @param string $plugin Plugin alias, e.g. `UserManager` or `user_manager`
     * @return mixed False if composer.json is missing or corrupt, or composer info
     *  as an array if valid composer.json is found
     */
    public static function composer($plugin)
    {
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

        // try to guess package version from VERSION.txt or similar
        if (!isset($json['version'])) {
            $files = glob($info['path'] . '/*', GLOB_NOSORT);
            $version = null;

            foreach ($files as $file) {
                if (in_array(basename(strtolower($file)), ['version.txt', 'version'])) {
                    $versionFile = file($file);
                    $version = trim(array_pop($versionFile));
                    break;
                }
            }

            if ($version !== null) {
                $json['version'] = $version;
            }
        }

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
     * - key `name` must be present and follow the pattern `author/package`
     * - key `type` must be present and be "quickapps-plugin" or "cakephp-plugin" (even if it's a theme).
     * - key `extra.regions` must be present if it's a theme (its name ends with
     *   `-theme`, e.g. `quickapps/blue-sky-theme`)
     *
     * ### Usage:
     *
     * ```php
     * $json = json_decode(file_gets_content('/path/to/composer.json'), true);
     * Plugin::validateJson($json);
     *
     * // OR:
     *
     * Plugin::validateJson('/path/to/composer.json');
     * ```
     *
     * @param array|string $json JSON given as an array result of
     *  `json_decode(..., true)`, or a string as path to where .json file can be found
     * @param bool $errorMessages If set to true an array of error messages
     *  will be returned, if set to false boolean result will be returned; true on
     *  success, false on validation failure failure. Defaults to false (boolean result)
     * @return array|bool
     */
    public static function validateJson($json, $errorMessages = false)
    {
        if (is_string($json) && file_exists($json) && !is_dir($json)) {
            $json = json_decode((new File($json))->read(), true);
        }

        $errors = [];
        if (!is_array($json) || empty($json)) {
            $errors[] = __('Corrupt JSON information.');
        } else {
            if (!isset($json['type'])) {
                $errors[] = __('Missing field: "{0}"', 'type');
            } elseif (!in_array($json['type'], ['quickapps-plugin', 'cakephp-plugin'])) {
                $errors[] = __('Invalid field: "{0}" ({1}). It should be: {2}', 'type', $json['type'], 'quickapps-plugin or cakephp-plugin');
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
        }

        if ($errorMessages) {
            return $errors;
        }

        return empty($errors);
    }

    /**
     * Gets settings from DB for given plugin. Or reads a single settings key value.
     *
     * @param string $plugin Plugin alias, e.g. `UserManager` or `user_manager`
     * @param string $key Which setting to read, the entire settings will be
     *  returned if no key is provided
     * @return mixed Array of settings if $key was not provided, or the requested
     *  value for the given $key (null of key does not exists)
     */
    public static function settings($plugin, $key = null)
    {
        $plugin = Inflector::camelize($plugin);
        $cacheKey = "settings({$plugin})";

        if ($cache = static::cache($cacheKey)) {
            if ($key !== null) {
                $cache = isset($cache[$key]) ? $cache[$key] : null;
            }
            return $cache;
        }

        $settings = [];
        if (!TableRegistry::exists('SnapshotNodeTypes')) {
            $PluginsTable = TableRegistry::get('Plugins');
            $PluginsTable->schema(['settings' => 'serialized']);
        } else {
            $PluginsTable = TableRegistry::get('Plugins');
        }

        $dbInfo = $PluginsTable
            ->find()
            ->select(['settings'])
            ->where(['name' => $plugin])
            ->limit(1)
            ->first();

        if ($dbInfo) {
            $settings = (array)$dbInfo->settings;
        }

        static::cache($cacheKey, $settings);

        if ($key !== null) {
            $settings = isset($settings[$key]) ? $settings[$key] : null;
        }

        return $settings;
    }

    /**
     * Gets plugin's dependencies as an array list.
     *
     * This method returns package names that follows the pattern `author-name/package`.
     * Packages such as `ext-mbstring`, etc will be ignored (EXCEPT `php`).
     * There are a few special package names that may be present:
     *
     * - `__QUICKAPPS__` represent QuickApps CMS's version.
     * - `__PHP__` represents server's PHP version.
     * - `__CAKEPHP__` represents cakephp's version.
     *
     * ### Example:
     *
     * ```php
     * // Get plugin's composer.json and extract dependencies
     * Plugin::dependencies('UserManager');
     *
     * // may returns: [
     * //    'UserWork' => '1.0',
     * //    'Calentar' => '1.0.*',
     * //    '__QUICKAPPS__' => '>=1.0', // QuickApps CMS v1.0 or higher required,
     * //    '__PHP__' => '>4.3'
     * // ]
     *
     * // Directly from composer.json information
     * Plugin::dependencies(json_decode('/path/to/composer.json', true));
     * ```
     *
     * @param array|string $plugin Plugin alias, or an array representing a
     *  "composer.json" file, that is, result of `json_decode(..., true)`
     * @return array List of plugin & version that $plugin depends on
     * @throws \Cake\Eror\FatalErrorException When $plugin is not found, or when
     *  plugin's composer.json is missing or corrupt
     */
    public static function dependencies($plugin)
    {
        $info = [];
        $dependencies = [];

        if (is_array($plugin)) {
            if (isset($plugin['require'])) {
                $info['composer']['require'] = $plugin['require'];
            } else {
                return [];
            }
        } else {
            $composer = static::composer($plugin);
        }

        if (!empty($composer['require'])) {
            foreach ($composer['require'] as $name => $version) {
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
     * Check if plugin is dependent on any other plugin. If it does, check if that
     * plugin is available (installed and enabled).
     *
     * ### Usage:
     *
     * ```php
     * // Check requirements for MyPlugin
     * Plugin::checkDependency('MyPlugin');
     *
     * // Check requirements from composer.json
     * Plugin::checkDependency(json_decode('/path/to/composer.json', true));
     * ```
     *
     * @param string|array $plugin Plugin alias, or an array representing "composer.json"
     * @return bool True if everything is OK, false otherwise
     */
    public static function checkDependency($plugin)
    {
        foreach (static::dependencies($plugin) as $plugin => $required) {
            if (in_array($plugin, ['__PHP__', '__QUICKAPPS__', '__CAKEPHP__'])) {
                if ($plugin === '__PHP__') {
                    $version = PHP_VERSION;
                } elseif ($plugin === '__CAKEPHP__') {
                    $version = Configure::version();
                } else {
                    $version = quickapps('version');
                }
            } else {
                try {
                    $basicInfo = (array)static::info($plugin);
                    $composerInfo = (array)static::composer($plugin);
                } catch (FatalErrorException $e) {
                    return false;
                }

                // installed, but disabled
                if (!$basicInfo['status']) {
                    return false;
                }

                $version = static::version($plugin);
            }

            if (!static::checkCompatibility($version, $required)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Verify if there is any plugin that depends of $pluginName.
     *
     * @param string $pluginName Plugin name to check
     * @return array A list of all plugin names that depends on $plugin, an empty
     *  array means that no other plugins depends on $plugin, so $plugin can be
     *  safely deleted or turned off.
     */
    public static function checkReverseDependency($pluginName)
    {
        $out = [];
        $plugins = static::collection(true)->match(['status' => 1]);
        foreach ($plugins as $plugin) {
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
     * Check whether a version is compatible with a given dependency.
     *
     * @param string $version The version to check against (e.g. 4.2.6)
     * @param string $constraints A string representing a dependency constraints,
     *  for instance, `>7.0 || 1.2`
     * @return bool True if compatible, false otherwise
     */
    public static function checkCompatibility($version, $constraints = null)
    {
        $parser = new VersionParser();
        $modifierRegex = '[\-\@]dev(\#\w+)?';
        $constraints = preg_replace('{' . $modifierRegex . '$}i', '', $constraints);
        $version = $parser->normalize($version);
        $version = preg_replace('{' . $modifierRegex . '$}i', '', $version);

        if (empty($constraints) || $version == $constraints) {
            return true;
        }

        try {
            $pkgConstraint = new VersionConstraint('==', $version);
            $constraintObjects = $parser->parseConstraints($constraints);
            return $constraintObjects->matches($pkgConstraint);
        } catch (\Exception $ex) {
            return false;
        }
    }
}
