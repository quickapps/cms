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
namespace QuickApps\Test\TestCase\Core;

use Cake\Filesystem\Folder;
use Cake\TestSuite\TestCase;
use QuickApps\Core\Plugin;

/**
 * PluginTest class.
 */
class PluginTest extends TestCase
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
     * test scan() method.
     *
     * @return void
     */
    public function testScan()
    {
        $plugins = array_keys(Plugin::scan());
        $noThemes = array_keys(Plugin::scan(true));

        $this->assertTrue(in_array('InvalidPlugin', $plugins));
        $this->assertTrue(in_array('NeedsSpaceOddity', $plugins));
        $this->assertTrue(in_array('SpaceOddity', $plugins));
        $this->assertTrue(!in_array('BackendTheme', $noThemes));
        $this->assertTrue(!in_array('FrontendTheme', $noThemes));
    }

    /**
     * test get() method.
     *
     * @return void
     */
    public function testGet()
    {
        $plugin = Plugin::get('Node');
        $this->assertTrue(!empty($plugin));
    }

    /**
     * test that get() method throws when getting an invalid plugin.
     *
     * @return void
     * @expectedException \Cake\Error\FatalErrorException
     */
    public function testGetThrow()
    {
        Plugin::get('UnexistingPluginName');
    }

    /**
     * test validateJson() method.
     *
     * @return void
     */
    public function testValidateJson()
    {
        $invalid = Plugin::validateJson(['version' => '1.0']);
        $valid = Plugin::validateJson([
            'version' => '1.0',
            'type' => 'quickapps-plugin',
            'name' => 'author/package',
            'description' => 'dummy desc',
        ]);

        $this->assertEquals(true, $valid);
        $this->assertEquals(false, $invalid);
    }

    /**
     * test checkReverseDependency() method.
     *
     * @return void
     */
    public function testCheckReverseDependency()
    {
        $result1 = Plugin::checkReverseDependency('SpaceOddity');
        $result2 = Plugin::checkReverseDependency('NeedsSpaceOddity');

        $this->assertTrue(!empty($result1));
        $this->assertTrue(empty($result2));
    }
}
