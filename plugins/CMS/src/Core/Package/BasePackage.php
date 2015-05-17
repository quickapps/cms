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

use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\Utility\Hash;
use CMS\Core\Package\Composer\JsonSchema;
use CMS\Core\Package\Rule\Constraint;
use CMS\Core\StaticCacheTrait;

/**
 * Represents a composer package or QuickAppsCMS plugin.
 *
 * Package types should extend this class.
 */
class BasePackage
{

    use InstanceConfigTrait;
    use StaticCacheTrait;

    /**
     * Default config.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * Full package name. e.g. `vendor/package`
     *
     * @var string
     */
    protected $_packageName = '';

    /**
     * Package vendor's name.
     *
     * @var string
     */
    protected $_vendor = '';

    /**
     * Package's name, or plugin's name.
     *
     * @var string
     */
    protected $_name = '';

    /**
     * Full path to package's root directory.
     *
     * @var string
     */
    protected $_path = '';

    /**
     * Package's version number.
     *
     * @var string|null
     */
    protected $_version = null;

    /**
     * Constructor.
     *
     * @param string $package Package name as string. e.g. `vendor-name/package-name`
     * @param string $path Full path to package's root directory
     * @param string $version Package version number
     */
    public function __construct($package, $path, $version = null)
    {
        $this->_packageName = $package;
        list($this->_vendor, $this->_name) = packageSplit($this->_packageName);
        $this->_version = $version;
        $this->_path = $path;

        if (strtolower($this->_packageName) === 'cakephp/cakephp') {
            $this->_version = Configure::version();
        } elseif (strtolower($this->_packageName) === 'quickapps/cms') {
            $this->_version = quickapps('version');
        }
    }

    /**
     * Returns package's name, that if whatever comes after the `/` symbol.
     *
     * @return string If $this represents a QuickAppsCMS's plugin, then its
     *  CamelizedName will be returned
     */
    public function name()
    {
        return $this->_name;
    }

    /**
     * Gets package's vendor name, that is whatever before the `/` symbol.
     *
     * @return string If $this represents a QuickAppsCMS's plugin, then an empty
     *  string will be returned
     */
    public function vendor()
    {
        return $this->_vendor;
    }

    /**
     * Gets version number of this package.
     *
     * @return string Package's version, for instance `1.2.x-dev`
     */
    public function version()
    {
        return $this->_version;
    }

    /**
     * Returns full path to package's root directory.
     *
     * @return string
     */
    public function path()
    {
        return $this->_path;
    }

    /**
     * Check whether this package's version matches the given $constraint.
     *
     * Other package types might overwrite this method to provide their own
     * matching logic.
     *
     * @param string $constraint A string representing a dependency constraint,
     *  for instance, `>7.0 || 1.2` or `~1.2`
     * @return bool
     * @see https://getcomposer.org/doc/01-basic-usage.md#package-versions
     */
    public function versionMatch($constraint)
    {
        return Constraint::match($this->version(), $constraint);
    }

    /**
     * Gets composer json information for this package.
     *
     * @param bool $full Whether to get full composer schema or not. Defaults to
     *  false, only defined keys in JSON file will be fetched
     * @return array Package's "composer.json" file as an array, an empty array if
     *  corrupt or not found
     */
    public function composer($full = false)
    {
        $cacheKey = "composer({$this->_packageName}, {$full})";
        if ($cache = static::cache($cacheKey)) {
            return $cache;
        }

        $jsonPath = normalizePath($this->path() . '/composer.json');
        if (!is_readable($jsonPath)) {
            return [];
        }

        $json = json_decode(file_get_contents($jsonPath), true);
        if (empty($json)) {
            return [];
        }

        if ($full) {
            $json = Hash::merge(JsonSchema::$schema, $json);
        }

        return static::cache($cacheKey, $json);
    }

    /**
     * Gets package's dependencies as an array list.
     *
     * ### Example:
     *
     * ```php
     * $this->dependencies();
     *
     * // may returns: [
     * //    'some-vendor/user-work' => '1.0',
     * //    'another-vendor/calendar' => '1.0.*',
     * //    'quickapps/cms' => '>=1.0',
     * //    'php' => '>4.3',
     * //    'cakephp/cakephp' => '3.*',
     * // ]
     * ```
     *
     * @return array List of packages and versions this package depends on
     */
    public function dependencies()
    {
        $composer = $this->composer();
        if (!empty($composer['require'])) {
            return $composer['require'];
        }
        return [];
    }

    /**
     * String representation of this rule.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_packageName;
    }
}
