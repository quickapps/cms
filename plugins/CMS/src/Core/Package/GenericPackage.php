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

/**
 * Represents an unknown package.
 *
 * Generic packages doesn't have path nor version defined by default although they
 * can be set using setters methods `setVersion()` and `setPath()`.
 */
class GenericPackage extends BasePackage
{

    /**
     * Sets a version number for this package.
     *
     * @param string $version The version to set
     */
    public function setVersion($version)
    {
        $this->_version = $version;
    }

    /**
     * Sets a package path for this package.
     *
     * @param string $path Package file system path
     */
    public function setPath($path)
    {
        $this->_path = $path;
    }
}
