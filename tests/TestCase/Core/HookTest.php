<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace QuickApps\Test\TestCase\Core;

use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\TestSuite\TestCase;
use QuickApps\Core\Hook;

/**
 * HookTest class.
 */
class HookTest extends TestCase {

	protected $_eventManager = null;

	public function setUp() {
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
 * test Hook::didHook() method.
 *
 * @return void
 */
	public function testDidHook() {
		$this->assertTrue(Hook::didHook('unexisting') === 0);

		Hook::hook('Test.hook');
		$this->assertTrue(Hook::didHook('Test.hook') === 1);
	}

/**
 * test Hook::hook() method.
 *
 * @return void
 */
	public function testHook() {
		$return = Hook::hook('Test.hook');

		$this->assertTrue($return instanceof Event);
		$this->assertEquals($return->result, 'event response');
	}

/**
 * test Hook::alter() method.
 *
 * @return void
 */
	public function testAlter() {
		$var1 = 'dummy1';
		$var2 = 'dummy2';
		Hook::alter('Test.alter', $var1, $var2);
		$this->assertEquals($var1, 'dummy1 altered');
		$this->assertEquals($var2, 'dummy2 altered');
	}

}
