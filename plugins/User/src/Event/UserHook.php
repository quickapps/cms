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
namespace User\Event;

use Cake\Event\Event;
use Cake\Event\EventListener;

/**
 * Main Hook Listener for User plugin.
 *
 */
class UserHook implements EventListener {

/**
 * Returns a list of hooks this Hook Listener is implementing. When the class
 * is registered in an event manager, each individual method will be associated
 * with the respective event.
 *
 * @return void
 */
	public function implementedEvents() {
		return [
			'User.beforeIdentify' => 'beforeIdentify',
			'User.afterIdentify' => 'afterIdentify',
			'User.beforeLogout' => 'beforeLogout',
			'User.afterLogout' => 'afterLogout'
		];
	}

/**
 * Event triggered before users is identified.
 *
 * Returning false or stopping the event will halt the identification process.
 *
 * @param \Cake\Event\Event $event
 * @return bool
 */
	public function beforeIdentify(Event $event) {
		return true;
	}

/**
 * Triggered After user's identification operation has been completed.
 *
 * This event is triggered even on identification failure, you must distinguish
 * between success or failure using the given argument.
 *
 * @param \Cake\Event\Event $event
 * @param mixed $result
 * @return bool
 */
	public function afterIdentify(Event $event, $result) {
	}

/**
 * Event triggered before user logout action.
 *
 * Returning false or stopping the event will halt the identification process.
 *
 * @param \Cake\Event\Event $event
 * @param \User\Model\Table\User $user
 * @return bool
 */
	public function beforeLogout(Event $event) {
		return true;
	}

/**
 * Event triggered after user logout action.
 *
 * Event listeners can return an alternative redirection URL, if not given
 * default URL will be used.
 *
 * @param \Cake\Event\Event $event
 * @param string|array $redirect
 * @return bool
 */
	public function afterLogout(Event $event, $redirect = '') {
	}

}