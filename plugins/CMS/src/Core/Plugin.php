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
namespace CMS\Core;

use Cake\Core\App;
use Cake\Core\Plugin as CakePlugin;
use Cake\Error\FatalErrorException;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use CMS\Core\Package\PackageFactory;
use CMS\Core\Package\PluginPackage;
use CMS\Core\StaticCacheTrait;

/**
 * Plugin is used to load and locate plugins.
 *
 * Wrapper for `Cake\Core\Plugin`, it adds some QuickAppsCMS specifics methods.
 */
class Plugin extends CakePlugin
{

    use StaticCacheTrait;

    /**
     * Get the given plugin as an object, or a collection of objects if not
     * specified.
     *
     * @param string $plugin Plugin name to get, or null to get a collection of
     *  all plugin objects
     * @return \CMS\Core\Package\PluginPackage|\Cake\Collection\Collection
     * @throws \Cake\Error\FatalErrorException When requested plugin was not found
     */
    public static function get($plugin = null)
    {
        $cacheKey = "get({$plugin})";
        $cache = static::cache($cacheKey);

        if ($cache !== null) {
            return $cache;
        }

        if ($plugin === null) {
            $collection = [];
            foreach ((array)quickapps('plugins') as $plugin) {
                $plugin = PackageFactory::create($plugin['name']);
                if ($plugin instanceof PluginPackage) {
                    $collection[] = $plugin;
                }
            }
            return static::cache($cacheKey, collection($collection));
        }

        $package = PackageFactory::create($plugin);
        if ($package instanceof PluginPackage) {
            return static::cache($cacheKey, $package);
        }

        throw new FatalErrorException(__d('cms', 'Plugin "{0}" was not found', $plugin));
    }

    /**
     * Scan plugin directories and returns plugin names and their paths within file
     * system. We consider "plugin name" as the name of the container directory.
     *
     * Example output:
     *
     * ```php
     * [
     *     'Users' => '/full/path/plugins/Users/',
     *     'ThemeManager' => '/full/path/plugins/ThemeManager/',
     *     ...
     *     'MySuperPlugin' => '/full/path/plugins/MySuperPlugin/',
     *     'DarkGreenTheme' => '/full/path/plugins/DarkGreenTheme/',
     * ]
     * ```
     *
     * If $ignoreThemes is set to true `DarkGreenTheme` will not be part of the
     * result.
     *
     * NOTE: All paths includes trailing slash.
     *
     * @param bool $ignoreThemes Whether include themes as well or not
     * @return array Associative array as `PluginName` => `/full/path/to/PluginName`
     */
    public static function scan($ignoreThemes = false)
    {
        $cacheKey = "scan({$ignoreThemes})";
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
                    $cache[$name] = normalizePath("{$dir}/");
                }
            }

            // look for Cake plugins installed using Composer
            if (file_exists(VENDOR_INCLUDE_PATH . 'cakephp-plugins.php')) {
                $cakePlugins = (array)include VENDOR_INCLUDE_PATH . 'cakephp-plugins.php';
                if (!empty($cakePlugins['plugins'])) {
                    $cache = Hash::merge($cakePlugins['plugins'], $cache);
                }
            }

            // filter, remove hidden folders and others
            foreach ($cache as $name => $path) {
                if (strpos($name, '.') === 0) {
                    unset($cache[$name]);
                } elseif ($name == 'CMS') {
                    unset($cache[$name]);
                } elseif ($ignoreThemes && str_ends_with($name, 'Theme')) {
                    unset($cache[$name]);
                }
            }

            $cache = static::cache($cacheKey, $cache);
        }

        return $cache;
    }

    /**
     * Checks whether a plugins is installed on the system regardless of its status.
     *
     * @param string $plugin Plugin to check
     * @return bool True if exists, false otherwise
     */
    public static function exists($plugin)
    {
        $check = quickapps("plugins.{$plugin}");
        return !empty($check);
    }

    /**
     * Validates a composer.json file.
     *
     * Below a list of validation rules that are applied:
     *
     * - must be a valid JSON file.
     * - key `name` must be present and follow the pattern `author/package`
     * - key `type` must be present.
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
     *  success, false on validation failure. Defaults to false (boolean result)
     * @return array|bool
     */
    public static function validateJson($json, $errorMessages = false)
    {
        if (is_string($json) && is_readable($json) && !is_dir($json)) {
            $json = json_decode((new File($json))->read(), true);
        }

        $errors = [];
        if (!is_array($json) || empty($json)) {
            $errors[] = __d('cms', 'Corrupt JSON information.');
        } else {
            if (!isset($json['type'])) {
                $errors[] = __d('cms', 'Missing field: "{0}"', 'type');
            }

            if (!isset($json['name'])) {
                $errors[] = __d('cms', 'Missing field: "{0}"', 'name');
            } elseif (!preg_match('/^(.+)\/(.+)+$/', $json['name'])) {
                $errors[] = __d('cms', 'Invalid field: "{0}" ({1}). It should be: {2}', 'name', $json['name'], '{author-name}/{package-name}');
            } elseif (str_ends_with(strtolower($json['name']), 'theme')) {
                if (!isset($json['extra']['regions'])) {
                    $errors[] = __d('cms', 'Missing field: "{0}"', 'extra.regions');
                }
            }
        }

        if ($errorMessages) {
            return $errors;
        }

        return empty($errors);
    }

    /**
     * Checks if there is any active plugin that depends of $plugin.
     *
     * @param string|CMS\Package\PluginPackage $plugin Plugin name, package
     *  name (as `vendor/package`) or plugin package object result of
     *  `static::get()`
     * @return array A list of all plugin names that depends on $plugin, an empty
     *  array means that no other plugins depends on $pluginName, so $plugin can be
     *  safely deleted or turned off.
     * @throws \Cake\Error\FatalErrorException When requested plugin was not found
     * @see \CMS\Core\Plugin::get()
     */
    public static function checkReverseDependency($plugin)
    {
        if (!($plugin instanceof PluginPackage)) {
            list(, $pluginName) = packageSplit($plugin, true);
            $plugin = static::get($pluginName);
        }

        return $plugin->requiredBy()->toArray();
    }
}
