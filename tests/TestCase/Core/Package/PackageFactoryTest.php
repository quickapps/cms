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
namespace QuickApps\Test\TestCase\Core\Package;

use Cake\TestSuite\TestCase;
use QuickApps\Core\Package\GenericPackage;
use QuickApps\Core\Package\LibraryPackage;
use QuickApps\Core\Package\PackageFactory;
use QuickApps\Core\Package\PluginPackage;
use QuickApps\Core\Package\ThirdPartyPackage;

/**
 * PackageFactory class.
 */
class PackageFactoryTest extends TestCase
{

    /**
     * test create() method.
     *
     * @return void
     */
    public function testCreatePlugin()
    {
        $package = PackageFactory::create('Node');
        $this->assertTrue($package instanceof PluginPackage);
    }

    /**
     * test create() method.
     *
     * @return void
     */
    public function testCreateLibrary()
    {
        $package = PackageFactory::create('php');
        $this->assertTrue($package instanceof LibraryPackage);
    }

    /**
     * test create() method.
     *
     * @return void
     */
    public function testCreateThirdParty()
    {
        $package = PackageFactory::create('cakephp/cakephp');
        $this->assertTrue($package instanceof ThirdPartyPackage);
    }

    /**
     * test create() method.
     *
     * @return void
     */
    public function testCreateGeneric()
    {
        $package = PackageFactory::create('dummy-vendor/dummy-package');

        $this->assertTrue($package instanceof GenericPackage);
    }
}
