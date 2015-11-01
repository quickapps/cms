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
namespace CMS\Core\Package;

use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use CMS\Core\Plugin;

/**
 * Represents a QuickAppsCMS plugin or theme.
 *
 * @property string $name CamelizedName of the plugin, e.g. `Megamenu`
 * @property string $humanName Human readable name of the plugin, e.g. `Megamenu Builder`
 * @property string $package Composer package name as `vendor/name`, e.g. `quickapps/megamenu`
 * @property string $path Full path to plugin root directory, where the `src` directory can be found
 * @property bool $isTheme Whether or not this plugin is actually a theme
 * @property bool $hasHelp Whether or not this plugin provides help documentation
 * @property bool $hasSettings Whether or not this plugin provides configurable values
 * @property bool $status Whether this plugin is enabled or not
 * @property array $aspects List of aspect classes used by AOP API
 * @property array $eventListeners List of event listener classes
 * @property array $settings Array of configurable values
 * @property array $composer Composer's json file as an array
 * @property array $permissions Plugin permissions tree indexed by role
 */
class PluginPackage extends BasePackage
{

    /**
     * Plugin information.
     *
     * @var array
     */
    protected $_info = [];

    /**
     * Permissions tree for this plugin.
     *
     * @var null|array
     */
    protected $_permissions = null;

    /**
     * {@inheritDoc}
     *
     * @return string CamelizedName plugin name
     */
    public function name()
    {
        return (string)Inflector::camelize(str_replace('-', '_', parent::name()));
    }

    /**
     * Gets plugin's permissions tree.
     *
     * ### Output example:
     *
     * ```php
     * [
     *     'administrator' => [
     *         'Plugin/Controller/action',
     *         'Plugin/Controller/action2',
     *         ...
     *     ],
     *     'role-machine-name' => [
     *         'Plugin/Controller/anotherAction',
     *         'Plugin/Controller/anotherAction2',
     *     ],
     *     ...
     * ]
     * ```
     *
     * @return array Permissions index by role's machine-name
     */
    public function permissions()
    {
        if (is_array($this->_permissions)) {
            return $this->_permissions;
        }

        $out = [];
        $acosTable = TableRegistry::get('User.Acos');
        $permissions = $acosTable
            ->Permissions
            ->find()
            ->where(['Acos.plugin' => $this->name])
            ->contain(['Acos', 'Roles'])
            ->all();

        foreach ($permissions as $permission) {
            if (!isset($out[$permission->role->slug])) {
                $out[$permission->role->slug] = [];
            }
            $out[$permission->role->slug][] = implode(
                '/',
                $acosTable
                ->find('path', ['for' => $permission->aco->id])
                ->extract('alias')
                ->toArray()
            );
        }

        $this->_permissions = $out;
        return $out;
    }

    /**
     * Magic getter to access properties that exists on info().
     *
     * @param string $property Name of the property to access
     * @return mixed
     */
    public function &__get($property)
    {
        return $this->info($property);
    }

    /**
     * Gets information for this plugin.
     *
     * When `$full` is set to true some additional keys will be repent in the
     * resulting array:
     *
     * - `settings`: Plugin's settings info fetched from DB.
     * - `composer`: Composer JSON information, converted to an array.
     * - `permissions`: Permissions tree for this plugin, see `PluginPackage::permissions()`
     *
     * ### Example:
     *
     * Reading full information:
     *
     * ```php
     * $plugin->info();
     *
     * // returns an array as follow:
     * [
     *     'name' => 'User,
     *     'isTheme' => false,
     *     'hasHelp' => true,
     *     'hasSettings' => false,
     *     'eventListeners' => [ ... ],
     *     'status' => 1,
     *     'path' => '/path/to/plugin',
     *     'settings' => [ ... ], // only when $full = true
     *     'composer' => [ ... ], // only when $full = true
     *     'permissions' => [ ... ], // only when $full = true
     * ]
     * ```
     *
     * Additionally the first argument, $key, can be used to get an specific value
     * using a dot syntax path:
     *
     * ```php
     * $plugin->info('isTheme');
     * $plugin->info('settings.some_key');
     * ```
     *
     * If the given path is not found NULL will be returned
     *
     * @param string $key Optional path to read from the resulting array
     * @return mixed Plugin information as an array if no key is given, or the
     *  requested value if a valid $key was provided, or NULL if $key path is not
     *  found
     */
    public function &info($key = null)
    {
        $plugin = $this->name();
        if (empty($this->_info)) {
            $this->_info = (array)quickapps("plugins.{$plugin}");
        }

        $parts = explode('.', $key);
        $getComposer = in_array('composer', $parts) || $key === null;
        $getSettings = in_array('settings', $parts) || $key === null;
        $getPermissions = in_array('permissions', $parts) || $key === null;

        if ($getComposer && !isset($this->_info['composer'])) {
            $this->_info['composer'] = $this->composer();
        }

        if ($getSettings && !isset($this->_info['settings'])) {
            $this->_info['settings'] = (array)$this->settings();
        }

        if ($getPermissions && !isset($this->_info['permissions'])) {
            $this->_info['permissions'] = (array)$this->permissions();
        }

        if ($key === null) {
            return $this->_info;
        }

        return $this->_getKey($parts);
    }

    /**
     * Gets info value for the given key.
     *
     * @param string|array $key The path to read. String using a dot-syntax, or an
     *  array result of exploding by `.` symbol
     * @return mixed
     */
    protected function &_getKey($key)
    {
        $default = null;
        $parts = is_string($key) ? explode('.', $key) : $key;

        switch (count($parts)) {
            case 1:
                if (isset($this->_info[$parts[0]])) {
                    return $this->_info[$parts[0]];
                }
                return $default;
            case 2:
                if (isset($this->_info[$parts[0]][$parts[1]])) {
                    return $this->_info[$parts[0]][$parts[1]];
                }
                return $default;
            case 3:
                if (isset($this->_info[$parts[0]][$parts[1]][$parts[2]])) {
                    return $this->_info[$parts[0]][$parts[1]][$parts[2]];
                }
                return $default;
            case 4:
                if (isset($this->_info[$parts[0]][$parts[1]][$parts[2]][$parts[3]])) {
                    return $this->_info[$parts[0]][$parts[1]][$parts[2]][$parts[3]];
                }
                return $default;
            default:
                $data = $this->_info;
                foreach ($parts as $key) {
                    if (is_array($data) && isset($data[$key])) {
                        $data = $data[$key];
                    } else {
                        return $default;
                    }
                }
        }

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function composer($full = false)
    {
        $composer = parent::composer($full);
        if ($this->isTheme && !isset($composer['extra']['admin'])) {
            $composer['extra']['admin'] = false;
        }
        if ($this->isTheme && !isset($composer['extra']['regions'])) {
            $composer['extra']['regions'] = [];
        }
        return $composer;
    }

    /**
     * Gets settings from DB for this plugin. Or reads a single settings key value.
     *
     * @param string $key Which setting to read, the entire settings will be
     *  returned if no key is provided
     * @return mixed Array of settings if $key was not provided, or the requested
     *  value for the given $key (null of key does not exists)
     */
    public function settings($key = null)
    {
        $plugin = $this->name();
        if ($cache = $this->config('settings')) {
            if ($key !== null) {
                $cache = isset($cache[$key]) ? $cache[$key] : null;
            }
            return $cache;
        }

        $pluginsTable = TableRegistry::get('System.Plugins');
        $settings = [];
        $dbInfo = $pluginsTable->find()
            ->cache("{$plugin}_settings", 'plugins')
            ->select(['name', 'settings'])
            ->where(['name' => $plugin])
            ->limit(1)
            ->first();

        if ($dbInfo) {
            $settings = (array)$dbInfo->settings;
        }

        if (empty($settings)) {
            $settings = (array)$pluginsTable->trigger("Plugin.{$plugin}.settingsDefaults")->result;
        }

        $this->config('settings', $settings);
        if ($key !== null) {
            $settings = isset($settings[$key]) ? $settings[$key] : null;
        }
        return $settings;
    }

    /**
     * Gets a collection list of plugin that depends on this plugin.
     *
     * @return \Cake\Collection\Collection List of plugins
     */
    public function requiredBy()
    {
        if ($cache = $this->config('required_by')) {
            return collection($cache);
        }

        Plugin::dropCache();
        $out = [];
        $plugins = Plugin::get()->filter(function ($v, $k) {
            return $v->name() !== $this->name();
        });

        foreach ($plugins as $plugin) {
            if ($dependencies = $plugin->dependencies($plugin->name)) {
                $packages = array_map(function ($item) {
                    list(, $package) = packageSplit($item, true);
                    return strtolower($package);
                }, array_keys($dependencies));

                if (in_array(strtolower($this->name()), $packages)) {
                    $out[] = $plugin;
                }
            }
        }

        $this->config('required_by', $out);
        return collection($out);
    }

    /**
     * {@inheritDoc}
     *
     * It will look for plugin's version in the following places:
     *
     * - Plugin's "composer.json" file.
     * - Plugin's "VERSION.txt" file (or any file matching "/version?(\.\w+)/i").
     * - Composer's "installed.json" file.
     *
     * If not found `dev-master` is returned by default. If plugin is not registered
     * on QuickAppsCMS (not installed) an empty string will be returned instead.
     *
     * @return string Plugin's version, for instance `1.2.x-dev`
     */
    public function version()
    {
        if (parent::version() !== null) {
            return parent::version();
        }

        if (!Plugin::exists($this->name())) {
            $this->_version = '';
            return $this->_version;
        }

        // from composer.json
        if (!empty($this->composer['version'])) {
            $this->_version = $this->composer['version'];
            return $this->_version;
        }

        // from version.txt
        $files = glob($this->path . '/*', GLOB_NOSORT);
        foreach ($files as $file) {
            $fileName = basename(strtolower($file));
            if (preg_match('/version?(\.\w+)/i', $fileName)) {
                $versionFile = file($file);
                $version = trim(array_pop($versionFile));
                $this->_version = $version;
                return $this->_version;
            }
        }

        // from installed.json
        $installedJson = normalizePath(VENDOR_INCLUDE_PATH . "composer/installed.json");
        if (is_readable($installedJson)) {
            $json = (array)json_decode(file_get_contents($installedJson), true);
            foreach ($json as $pkg) {
                if (isset($pkg['version']) &&
                    strtolower($pkg['name']) === strtolower($this->_packageName)
                ) {
                    $this->_version = $pkg['version'];
                    return $this->_version;
                }
            }
        }

        $this->_version = 'dev-master';
        return $this->_version;
    }

    /**
     * Returns an array that can be used to describe the internal state of this
     * object.
     *
     * @return array
     */
    public function __debugInfo()
    {
        return $this->info(null);
    }
}
