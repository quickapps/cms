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
use QuickApps\Core\Package\PluginPackage;

/**
 * PluginPackageTest class.
 */
class PluginPackageTest extends TestCase
{

    /**
     * Fixtures.
     * 
     * @var array
     */
    public $fixtures = [
        'app.plugins',
    ];

    /**
     * test name() method.
     *
     * @return void
     */
    public function testName()
    {
        $package = new PluginPackage('some-wird_name', '', '');
        $this->assertEquals('SomeWirdName', $package->name());
    }

    /**
     * test version() method.
     *
     * @return void
     */
    public function testVersion()
    {
        $package = new PluginPackage('some-wird_name', '', '1.2.3');
        $this->assertEquals('1.2.3', $package->version());
    }
}
