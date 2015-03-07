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

use QuickApps\Core\Package\GenericPackage;
use QuickApps\Core\Package\LibraryPackage;
use QuickApps\Core\Package\PluginPackage;
use QuickApps\Core\Package\ThirdPartyPackage;
use QuickApps\Core\Plugin;

/**
 * Used to create package objects instances.
 *
 */
class PackageFactory
{

    /**
     * Given a full package name, returns an instance of an object representing
     * that package.
     *
     * @param string $package Full package name. e.g. `vendor-name/package-name`
     * @return \QuickApps\Core\Package\BasePackage
     */
    public static function create($package)
    {
        if ($plugin = static::_getPlugin($package)) {
            return $plugin;
        }

        if ($lib = static::_getLibrary($package)) {
            return $lib;
        }

        if ($third = static::_getThirdParty($package)) {
            return $third;
        }

        return new GenericPackage($package, '', '');
    }

    /**
     * Tries to get a QuickAppsCMS plugin.
     *
     * @param string $package Full package name
     * @return bool|\QuickApps\Core\Package\PluginPackage
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
     * @return bool|\QuickApps\Core\Package\LibraryPackage
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
     * @return bool|\QuickApps\Core\Package\ThirdPartyPackage
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
