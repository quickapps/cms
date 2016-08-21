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

/**
 * Represents a third party package installed using Composer.
 *
 */
class ThirdPartyPackage extends BasePackage
{

    /**
     * {@inheritDoc}
     *
     * Gets package's version from composer's "installed.json". By default CakePHP
     * and QuickAppCMS package versions are handled by their internal version
     * getters:
     *
     * - \Cake\Core\Configure\version() for CakePHP
     * - quickapps('version') for QuickAppsCMS
     *
     * @return string Package's version, for instance `1.2.x-dev`
     */
    public function version()
    {
        if (parent::version() !== null) {
            return parent::version();
        }

        $packages = $this->_packages();
        $this->_version = isset($packages[$this->_packageName]) ? $packages[$this->_packageName] : '';

        return $this->_version;
    }

    /**
     * Gets a list of all packages installed using composer.
     *
     * @return array
     */
    protected function _packages()
    {
        $installed = static::cache('_composerPackages');
        if (is_array($installed)) {
            return $installed;
        }

        $jsonPath = VENDOR_INCLUDE_PATH . 'composer/installed.json';
        $installed = [];

        if (is_readable($jsonPath)) {
            $json = (array)json_decode(file_get_contents($jsonPath), true);
            foreach ($json as $pkg) {
                $installed[$pkg['name']] = [
                    'name' => $pkg['name'],
                    'version' => $pkg['version'],
                    'version_normalized' => $pkg['version_normalized'],
                ];
            }
        }

        return static::cache('_composerPackages', $installed);
    }
}
