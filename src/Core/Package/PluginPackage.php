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
namespace QuickApps\Core\Package;

use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use QuickApps\Core\Plugin;

/**
 * Represents a QuickAppsCMS plugin (including themes).
 *
 * @property string $name
 * @property string $human_name
 * @property string $package
 * @property string $path
 * @property bool $isTheme
 * @property bool $isCore
 * @property bool $hasHelp
 * @property bool $hasSettings
 * @property bool $status
 * @property array $eventListeners
 * @property array $settings
 * @property array $composer
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
     * {@inheritdoc}
     *
     * @return string CamelizedName plugin name
     */
    public function name()
    {
        return (string)Inflector::camelize(str_replace('-', '_', parent::name()));
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
     * When `$full` is set to true plugin's settings info is fetched from DB and
     * placed under the `settings` key of the resulting array. Also composer
     * information is converted to an array and placed under the `composer` key of
     * the resulting array.
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
     *     'isCore' => true,
     *     'hasHelp' => true,
     *     'hasSettings' => false,
     *     'eventListeners' => [ ... ],
     *     'status' => 1,
     *     'path' => '/path/to/plugin',
     *     'settings' => [ ... ], // only when $full = true
     *     'composer' => [ ... ], // only when $full = true
     * ]
     * ```
     *
     * Additionally the first argument, $key, can be used to get an specific vale
     * using a dot syntax path:
     *
     * ```php
     * $plugin->info('isTheme');
     *
     * $plugin->info('settings.some_key');
     * ```
     *
     * @param string $key Optional path to read from the resulting array
     * @return array Plugin information
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

        if ($getComposer && !isset($this->_info['composer'])) {
            $this->_info['composer'] = $this->composer();

            if ($this->_info['isTheme'] && !isset($this->_info['composer']['extra']['admin'])) {
                $this->_info['composer']['extra']['admin'] = false;
            }

            if ($this->_info['isTheme'] && !isset($this->_info['composer']['extra']['regions'])) {
                $this->_info['composer']['extra']['regions'] = [];
            }
        }

        if ($getSettings && !isset($this->_info['settings'])) {
            $this->_info['settings'] = (array)$this->settings();
        }

        if ($key === null) {
            return $this->_info;
        }

        return $this->_getKey($key);
    }

    /**
     * Gets value for the given key.
     *
     * @param string $key The path to read, dot-syntax allowed
     * @return mixed
     */
    protected function &_getKey($key)
    {
        $default = null;
        $parts = explode('.', $key);

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

        $this->config('settings', $settings);
        if ($key !== null) {
            $settings = isset($settings[$key]) ? $settings[$key] : null;
        }
        return $settings;
    }

    /**
     * {@inheritdoc}
     *
     * ### Example:
     *
     * ```php
     * // Installed QuickAppsCMS's plugin,
     * // returns: read from "composer.json" (or "version.*" file)
     * $this->version('some-quickapps/plugin');
     * ```
     *
     * @return string Plugin's version, for instance `1.2.x-dev`. If no version
     *  can be resolved, `dev-version` is returned by default
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

        if (isset($this->composer['version'])) {
            $this->_version = $this->composer['version'];
            return $this->_version;
        }

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
