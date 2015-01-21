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
namespace QuickApps\Test\TestCase\View;

use Cake\TestSuite\TestCase;
use QuickApps\View\ViewModeRegistry;

/**
 * ViewModeRegistryTest class.
 */
class ViewModeRegistryTest extends TestCase {

    /**
     * tearDown().
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        ViewModeRegistry::removeViewMode('test-view-mode');
        ViewModeRegistry::removeViewMode('test-1');
        ViewModeRegistry::removeViewMode('test-2');
        ViewModeRegistry::removeViewMode('test-3');
    }

    /**
     * test that switching to an unexisting view mode throws an error.
     *
     * @return void
     * @expectedException \Cake\Network\Exception\InternalErrorException
     */
    public function testSwitchToInvalidViewModeThrow()
    {
        ViewModeRegistry::switchViewMode('unexisting-view-mode');
    }

    /**
     * test that addViewMode() method works when adding multiple VMs at once.
     *
     * @return void
     */
    public function testAddViewModeBulk()
    {
        ViewModeRegistry::addViewMode([
            'test-1' => ['name' => 'Test 1', 'description' => 'Description 1'],
            'test-2' => ['name' => 'Test 2', 'description' => 'Description 2'],
            'test-3' => ['name' => 'Test 3', 'description' => 'Description 3'],
        ]);
        $this->assertTrue(in_array('test-1', ViewModeRegistry::viewModes()));
        $this->assertTrue(in_array('test-2', ViewModeRegistry::viewModes()));
        $this->assertTrue(in_array('test-3', ViewModeRegistry::viewModes()));
    }

}
