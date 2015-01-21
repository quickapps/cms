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

use Cake\Event\EventManager;
use Cake\TestSuite\TestCase;
use QuickApps\Event\HooktagManager;

/**
 * HooktagTest class.
 */
class HooktagManagerTest extends TestCase {

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
	public function setUp() {
		parent::setUp();

		$this->_eventManager = EventManager::instance();
		if (!$this->_eventManager->listeners('Hooktag.dummy')) {
			$this->_eventManager->attach(function ($event, $atts, $content, $code) {
				return '@@DUMMY@@';
			}, 'Hooktag.dummy');

			$this->_eventManager->attach(function ($event, $atts, $content, $code) {
				return $atts['at'];
			}, 'Hooktag.dummy_atts');

			$this->_eventManager->attach(function ($event, $atts, $content, $code) {
				return $content;
			}, 'Hooktag.enclosed');
		}
	}

/**
 * test hooktags() method.
 *
 * @return void
 */
	public function testHooktags() {
		$this->assertEquals('some text @@DUMMY@@', HooktagManager::hooktags('some text [dummy /]'));
		$this->assertEquals('hello world', HooktagManager::hooktags('hello [dummy_atts at=world/]'));
		$this->assertEquals('hello world!', HooktagManager::hooktags('hello [enclosed]world![/enclosed]'));
	}

/**
 * test stripHooktags() method.
 *
 * @return void
 */
	public function testStripHooktags() {
		$this->assertEquals('some text ', HooktagManager::stripHooktags('some text [dummy /]'));
		$this->assertEquals('hello ', HooktagManager::stripHooktags('hello [dummy_atts at=world/]'));
	}

}
