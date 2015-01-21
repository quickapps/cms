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
namespace QuickApps\Test\TestCase\Event;

use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\TestSuite\TestCase;
use QuickApps\Event\HookManager;

/**
 * HookTest class.
 */
class HookManagerTest extends TestCase {

	/**
	 * EventManager instance.
	 * 
	 * @var \Cake\Event\EventManager
	 */
	protected $_eventManager = null;

	/**
	 * setUp().
	 *
	 * @return void
	 */
	public function setUp()
	{
		parent::setUp();

		$this->_eventManager = EventManager::instance();
		if (!$this->_eventManager->listeners('Test.hook')) {
			$this->_eventManager->attach(function ($event) {
				return 'event response';
			}, 'Test.hook');

			$this->_eventManager->attach(function ($event, &$arg1, &$arg2) {
				$arg1 .= ' altered';
				$arg2 .= ' altered';
			}, 'Alter.Test.alter');
		}
	}

	/**
	 * test triggered() method.
	 *
	 * @return void
	 */
	public function testTriggered()
	{
		$this->assertTrue(HookManager::triggered('unexisting') === 0);

		HookManager::trigger('Test.hook');
		$this->assertTrue(HookManager::triggered('Test.hook') === 1);
	}

	/**
	 * test trigger() method.
	 *
	 * @return void
	 */
	public function testTrigger()
	{
		$return = HookManager::trigger('Test.hook');

		$this->assertTrue($return instanceof Event);
		$this->assertEquals($return->result, 'event response');
	}

	/**
	 * test alter() method.
	 *
	 * @return void
	 */
	public function testAlter()
	{
		$var1 = 'dummy1';
		$var2 = 'dummy2';
		HookManager::alter('Test.alter', $var1, $var2);
		$this->assertEquals($var1, 'dummy1 altered');
		$this->assertEquals($var2, 'dummy2 altered');
	}

}
