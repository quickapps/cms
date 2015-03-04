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
        $expected = ['__QUICKAPPS__' => '2.0.*', 'SpaceOddity' => '1.*'];
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
     * test checkIncompatibility() method.
     *
     * @return void
     */
    public function testCheckIncompatibility()
    {
        $tests = [
            ['provided' => '1.0', 'constraints' => '1.*', 'expected' => true],
            ['provided' => '3.0', 'constraints' => '~1.2', 'expected' => false],
            ['provided' => '1.5', 'constraints' => '>=1.2 <2.0', 'expected' => true],
            ['provided' => '2.0', 'constraints' => '>=1.0 <1.1', 'expected' => false],
            ['provided' => '1.1.8', 'constraints' => '1.1.*', 'expected' => true],
            ['provided' => '1.1.8', 'constraints' => '>1.0', 'expected' => true],
            ['provided' => '1.0', 'constraints' => '>1.0', 'expected' => false],
            ['provided' => '2.0', 'constraints' => '>1.0', 'expected' => true],
            ['provided' => '2.0.1', 'constraints' => '>1.0', 'expected' => true],
            ['provided' => '1.6', 'constraints' => '1.0 - 2.0', 'expected' => true],
            ['provided' => '1.6', 'constraints' => '>=1.0.0 <2.1', 'expected' => true],
            ['provided' => '3.0', 'constraints' => '>=1.0.0 <2.1', 'expected' => false],
            ['provided' => 'dev-master', 'constraints' => '*', 'expected' => true],
            ['provided' => '1.2.2', 'constraints' => '*', 'expected' => true],
            ['provided' => '8.5', 'constraints' => '>=7.0 <8.6.6', 'expected' => true],
            ['provided' => '7.6.66', 'constraints' => '>=7.0 <8.6.6', 'expected' => true],
            ['provided' => '5.0', 'constraints' => '>=7.0 <8.6.6', 'expected' => false],
            ['provided' => '5', 'constraints' => '>=7.0 <8.6.6', 'expected' => false],
            ['provided' => '9.0', 'constraints' => '>=7.0 <8.6.6', 'expected' => false],
            ['provided' => '9', 'constraints' => '>=7.0 <8.6.6', 'expected' => false],
            ['provided' => '2.6.8', 'constraints' => '2.6.8', 'expected' => true],
            ['provided' => '2.6.8', 'constraints' => '2.6.8 || 2.6.5', 'expected' => true],
            ['provided' => '2.6.5', 'constraints' => '2.6.8 || 2.6.5', 'expected' => true],
            ['provided' => '2.6.6', 'constraints' => '2.6.8 || 2.6.5', 'expected' => false],
            ['provided' => '2.6.7', 'constraints' => '2.6.8 || 2.6.5', 'expected' => false],
            ['provided' => '1.9', 'constraints' => '^1.2.3', 'expected' => true],
            ['provided' => '1.0', 'constraints' => '>=1.2.3 <2.0', 'expected' => false],
        ];

        foreach ($tests as $test) {
            $current = Plugin::checkIncompatibility($test['provided'], $test['constraints']) ? 'true' : 'false';
            $expected = $test['expected'] ? 'true' : 'false';
            $this->assertEquals(
                "{$test['provided']} @ {$test['constraints']} -> {$expected}",
                "{$test['provided']} @ {$test['constraints']} -> {$current}"
            );
        }
    }
}
