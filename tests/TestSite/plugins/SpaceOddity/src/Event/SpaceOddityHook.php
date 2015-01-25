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
namespace SpaceOddity\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Error\Debugger;

/**
 * SpaceOddity Hook class.
 *
 */
class SpaceOddityHook implements EventListenerInterface {

	protected $_lyrics = 'Ground Control to Major Tom
Ground Control to Major Tom
Take your protein pills and put your helmet on
Ground Control to Major Tom (Ten, Nine, Eight, Seven, Six)
Commencing countdown, engines on (Five, Four, Three)
Check ignition and may God\'s love be with you (Two, One, Liftoff)
This is Ground Control to Major Tom
You\'ve really made the grade
And the papers want to know whose shirts you wear
Now it\'s time to leave the capsule if you dare
"This is Major Tom to Ground Control
I\'m stepping through the door
And I\'m floating in a most peculiar way
And the stars look very different today
For here
Am I sitting in a tin can
Far above the world
Planet Earth is blue
And there\'s nothing I can do
Though I\'m past one hundred thousand miles
I\'m feeling very still
And I think my spaceship knows which way to go
Tell my wife I love her very much "she knows"
Ground Control to Major Tom
Your circuit\'s dead, there\'s something wrong
Can you hear me, Major Tom?
Can you hear me, Major Tom?
Can you hear me, Major Tom?
Can you "Here Am I floating round a tin can
Far above the Moon
Planet Earth is blue
And there\'s nothing I can do.';

/**
 * Returns a list of hooks this Hook Listener is implementing. When the class is
 * registered in an event manager, each individual method will be associated with
 * the respective event.
 *
 * @return void
 */
	public function implementedEvents() {
		return [
			'Plugin.SpaceOddity.beforeInstall' => 'beforeInstall',
			'Plugin.SpaceOddity.afterInstall' => 'afterInstall',
			'Plugin.SpaceOddity.beforeUpdate' => 'beforeUpdate',
			'Plugin.SpaceOddity.afterUpdate' => 'afterUpdate',
			'Plugin.SpaceOddity.beforeEnable' => 'beforeEnable',
			'Plugin.SpaceOddity.afterEnable' => 'afterEnable',
			'Plugin.SpaceOddity.beforeDisable' => 'beforeDisable',
			'Plugin.SpaceOddity.afterDisable' => 'afterDisable',
			'Plugin.SpaceOddity.beforeUninstall' => 'beforeUninstall',
			'Plugin.SpaceOddity.afterUninstall' => 'afterUninstall',
			'View.afterRender' => 'afterRender',
		];
	}

/**
 * Triggered before view is rendered. And before view is merged into layout.
 *
 * @param \Cake\Event\Event $event
 * @return void
 */
	public function afterRender(Event $event) {
		$event->subject()->Blocks->set('title', $this->_line());
	}

/**
 * Triggered before plugin is registered on DB and before plugin's directory
 * is moved to its final destination.
 * 
 * @param \Cake\Event\Event $event
 * @return bool Returning false or stopping the event will halt the install operation
 */
	public function beforeInstall(Event $event) {
		Debugger::log('beforeInstall: ' . $this->_line());
		return true;
	}

/**
 * Triggered after plugin is registered on DB and after plugin's directory
 * moved to its final destination.
 * 
 * @param \Cake\Event\Event $event
 * @return void
 */
	public function afterInstall(Event $event) {
		Debugger::log('afterInstall: ' . $this->_line());
	}

/**
 * Triggered before plugin's new directory is moved to its final destination.
 * 
 * @param \Cake\Event\Event $event
 * @return bool Returning false or stopping the event will halt the update operation
 */
	public function beforeUpdate(Event $event) {
		Debugger::log('beforeUpdate: ' . $this->_line());
		return true;
	}

/**
 * Triggered after plugin's new directory is moved to its final destination.
 * 
 * @param \Cake\Event\Event $event
 * @return void
 */
	public function afterUpdate(Event $event) {
		Debugger::log('afterUpdate: ' . $this->_line());
	}

/**
 * Triggered before plugin is enabled.
 * 
 * @param \Cake\Event\Event $event
 * @return bool Returning false or stopping the event will halt the enabling operation
 */
	public function beforeEnable(Event $event) {
		Debugger::log('beforeEnable: ' . $this->_line());
		return true;
	}

/**
 * Triggered after plugin was enabled.
 * 
 * @param \Cake\Event\Event $event
 * @return void
 */
	public function afterEnable(Event $event) {
		Debugger::log('afterEnable: ' . $this->_line());
	}

/**
 * Triggered before plugin is disabled.
 * 
 * @param \Cake\Event\Event $event
 * @return bool Returning false or stopping the event will halt the disabling operation
 */
	public function beforeDisable(Event $event) {
		Debugger::log('beforeDisable: ' . $this->_line());
		return true;
	}

/**
 * Triggered after plugin was disabled.
 * 
 * @param \Cake\Event\Event $event
 * @return void
 */
	public function afterDisable(Event $event) {
		Debugger::log('afterDisable: ' . $this->_line());
	}

/**
 * Triggered before plugin is removed from DB and before its directory is removed.
 * 
 * @param \Cake\Event\Event $event
 * @return bool Returning false or stopping the event will halt the uninstall operation
 */
	public function beforeUninstall(Event $event) {
		Debugger::log('beforeUninstall: ' . $this->_line());
		return true;
	}

/**
 * Triggered after plugin is removed from DB and after its directory was removed.
 * 
 * @param \Cake\Event\Event $event
 * @return void
 */
	public function afterUninstall(Event $event) {
		Debugger::log('afterUninstall: ' . $this->_line());
	}

/**
 * Returns a random lyric line.
 * 
 * @return string
 */
	protected function _line() {
		$lines = explode("\n", $this->_lyrics);
		return '♪ ♫ ♭♯ ~ ' . trim($lines[array_rand($lines)]) . ' ~ ♪ ♫ ♭♯';
	}

}
