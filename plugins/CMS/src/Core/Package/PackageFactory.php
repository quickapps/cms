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

use CMS\Core\Package\BasePackage;
use CMS\Core\Package\GenericPackage;
use CMS\Core\Package\LibraryPackage;
use CMS\Core\Package\PluginPackage;
use CMS\Core\Package\ThirdPartyPackage;
use CMS\Core\Plugin;

/**
 * Used to create package objects.
 *
 * In QuickAppsCMS plugins and themes are internally handled as "packages",
 * there exists a few packages types, for example third-party libraries which are
 * installed through composer's install command. Each package type is represented
 * by its own class, QuickAppsCMS comes with a few of this classes: `PluginPackage`,
 * `LibraryPackage`, `ThirdPartyPackage` and `GenericPackage`. These classes
 * provides a set of useful methods for interacting with QuickAppsCMS.
 *
 * This class automatically tries to determinate the best package type based on
 * its name using what we call "detectors" methods. This class comes with a few
 * built-in detector methods which are described below, however more detectors can
 * be registered (or overwrite existing ones) using the `addDetector()` method. A
 * "detector" is a simple callable function which based on a given package name it
 * should return an object representing that package if the given package name
 * matches the type of package the detector represents. For example:
 *
 *
 * ### Registering detectors:
 *
 * ```php
 * PackageFactory::addDetector('myVendorPlugin', function ($packageName) {
 *     list($vendor, $package) = packageSplit($packageName);
 *     if ($vendor == 'my-vendor-plugin') {
 *         return new MyVendorPackage($package, "/path/to/{$package}/")
 *     }
 * });
 * ```
 *
 * In this example we are using our own `MyVendorPackage` class for representing
 * packages created by `my-vendor-plugin`.
 *
 * ### Built-in detectors:
 *
 * - plugin: For packages representing QuickAppsCMS plugins.
 *
 * - library: For packages representing PHP extension libraries or PHP itself, for
 *   example: `ext-intl`, `php`, `ext-zlib`, etc
 *
 * - thirdParty: For packages representing third-party libraries installed using
 *   composer, for example: `nesbot/carbon`, `robmorgan/phinx`, etc.
 *
 * ### Detection order:
 *
 * Detectors methods are invoked in the order they were registered, if one detector
 * fails to detect a package the next registered detector will be used, and so on.
 * By default `GenricPackage` will be used if all detectors fails to detect the
 * given package name.
 */
class PackageFactory
{

    /**
     * List of detectors methods indexed as `name` => `callable`.
     *
     * @var array
     */
    protected static $_detectors = [];

    /**
     * Indicates if default detectors were initialized.
     *
     * @var bool
     */
    protected static $_initialized = false;

    /**
     * Given a full package name, returns an instance of an object representing
     * that package.
     *
     * If no matching package is found, `GenericPackage` will be used by default.
     *
     * @param string $package Full package name. e.g. `vendor-name/package-name`
     * @return \CMS\Core\Package\BasePackage
     */
    public static function create($package)
    {
        static::_init();
        foreach (static::$_detectors as $name => $callable) {
            $result = $callable($package);
            if ($result instanceof BasePackage) {
                return $result;
            }
        }

        return new GenericPackage($package, '', '');
    }

    /**
     * Initializes this class.
     *
     * @return void
     */
    protected static function _init()
    {
        if (static::$_initialized) {
            return;
        }

        static::$_detectors['plugin'] = function ($package) {
            return static::_getPlugin($package);
        };

        static::$_detectors['library'] = function ($package) {
            return static::_getLibrary($package);
        };

        static::$_detectors['thirdParty'] = function ($package) {
            return static::_getThirdParty($package);
        };

        static::$_initialized = true;
    }

    /**
     * Registers a new package detection method.
     *
     * Callable function should return an object package extending
     * `QuickApp\Core\Package\BasePackage` class on success.
     *
     * @param string $name The name for this detector
     * @param callable $method The callable method
     * @return void
     */
    public static function addDetector($name, callable $method)
    {
        static::$_detectors[$name] = $method;
    }

    /**
     * Gets a list of all registered detectors.
     *
     * @return array
     */
    public static function detectors()
    {
        return static::$_detectors;
    }

    /**
     * Tries to get a QuickAppsCMS plugin.
     *
     * @param string $package Full package name
     * @return bool|\CMS\Core\Package\PluginPackage
     */
    protected static function _getPlugin($package)
    {
        list(, $plugin) = packageSplit($package, true);
        if (Plugin::exists($plugin)) {
            return new PluginPackage(
                quickapps("plugins.{$plugin}.name"),
                quickapps("plugins.{$plugin}.path")
            );
        }

        return false;
    }

    /**
     * Tries to get package that represents a PHP library.
     *
     * @param string $package Full package name
     * @return bool|\CMS\Core\Package\LibraryPackage
     */
    protected static function _getLibrary($package)
    {
        if (strpos($package, '/') === false) {
            return new LibraryPackage($package, null);
        }

        return false;
    }

    /**
     * Tries to get package that represents a third party library.
     *
     * - Package must exists on `VENDOR_PATH/vendor-name/package-name/`.
     * - Its composer.json file must exists as well.
     * - Package must be registered on Composer's "installed.json" file.
     *
     * @param string $package Full package name
     * @return bool|\CMS\Core\Package\ThirdPartyPackage
     */
    protected static function _getThirdParty($package)
    {
        list($vendor, $packageName) = packageSplit($package);
        $packageJson = normalizePath(VENDOR_INCLUDE_PATH . "/{$vendor}/{$packageName}/composer.json");

        if (is_readable($packageJson)) {
            $installedJson = normalizePath(VENDOR_INCLUDE_PATH . "composer/installed.json");
            if (is_readable($installedJson)) {
                $json = (array)json_decode(file_get_contents($installedJson), true);
                foreach ($json as $pkg) {
                    if (strtolower($pkg['name']) === strtolower($package)) {
                        return new ThirdPartyPackage($package, dirname($packageJson), $pkg['version']);
                    }
                }
            }
        }

        return false;
    }
}
