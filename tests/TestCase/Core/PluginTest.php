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
class PluginTest extends TestCase {

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
     * test info() method.
     *
     * @return void
     */
    public function testInfo()
    {
        $node = Plugin::info('Node');
        $this->assertTrue(!empty($node['name']) && !empty($node['path']));
    }

    /**
     * test that info() method throws when getting an invalid plugin.
     *
     * @return void
     * @expectedException \Cake\Error\FatalErrorException
     */
    public function testInfoThrow()
    {
        Plugin::info('UnexistingPluginName');
    }

    /**
     * test composer() method.
     *
     * @return void
     */
    public function testComposer()
    {
        $composer = Plugin::composer('InvalidPlugin');
        $valid = Plugin::composer('Node');

        $this->assertEquals(false, $composer);
        $this->assertTrue(is_array($valid));
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
     * test dependencies() method.
     *
     * @return void
     */
    public function testDependencies()
    {
        $result = Plugin::dependencies('NeedsSpaceOddity');
        $expected = ['__QUICKAPPS__' => '2.0.*-dev', 'SpaceOddity' => '*'];

        $this->assertEquals($expected, $result);
    }

    /**
     * test checkDependency() method.
     *
     * @return void
     */
    public function testCheckDependency()
    {
        $expected = true;
        $result = Plugin::checkDependency('NeedsSpaceOddity');

        $this->assertEquals($expected, $result);
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

    /**
     * test parseDependency() method.
     *
     * @return void
     */
    public function testParseDependency()
    {
        $expected1 = [['op' => '<', 'version' => '2.x'], ['op' => '>=', 'version' => '1.x']];
        $result1 = Plugin::parseDependency('1.*');

        $expected2 = [['op' => '<', 'version' => '1.2'], ['op' => '>=', 'version' => '1.1']];
        $result2 = Plugin::parseDependency('1.1.*');

        $expected3 = [['op' => '>', 'version' => '1.0']];
        $result3 = Plugin::parseDependency('>1.0');

        $expected4 = [['op' => '>=', 'version' => '1.x']];
        $result4 = Plugin::parseDependency('>=1.*');

        $expected5 = [];
        $result5 = Plugin::parseDependency('*');

        $expected6 = [['op' => '>=', 'version' => '7.0'], ['op' => '<', 'version' => '7.6']];
        $result6 = Plugin::parseDependency('>=7.x,<7.6');

        $expected7 = $expected6;
        $result7 = Plugin::parseDependency('>=7.x,<7.6.*');

        $this->assertEquals($expected1, $result1['versions']);
        $this->assertEquals($expected2, $result2['versions']);
        $this->assertEquals($expected3, $result3['versions']);
        $this->assertEquals($expected4, $result4['versions']);
        $this->assertEquals($expected5, $result5['versions']);
        $this->assertEquals($expected6, $result6['versions']);
        $this->assertEquals($expected7, $result7['versions']);
    }

}
