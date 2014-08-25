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
use Cake\Utility\Secutity;
use User\Utility\NotificationManager;
use User\Model\Entity\User;

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
			'User.afterLogout' => 'afterLogout',

			'User.onRegister' => 'onRegister',
			'User.onActivate' => 'onActivate',
			'User.onBlock' => 'onBlock',
			'User.onCancel' => 'onCancel',
			'User.onPasswordRecovery' => 'onPasswordRecovery',

			'Plugin.User.settingsValidate' => 'settingsBeforeValidate',
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
 * @param mixed $result Result of AuthComponent::identify(), false if user could
 *  not be identified, or an array of user's info if was successfully identified
 * @return bool
 */
	public function afterIdentify(Event $event, $result) {
	}

/**
 * Event triggered before user logout action.
 *
 * Returning false or stopping the event will halt the logout process.
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
 * @param string|array $redirect Default redirection URL that will be used
 * @return bool
 */
	public function afterLogout(Event $event, $redirect = '') {
	}

/**
 * Event triggered when new users are registered on DB.
 *
 * @param \Cake\Event\Event $event
 * @param \User\Model\Entity\User $user
 * @return bool
 */
	public function onRegister(Event $event, User $user) {
		return (new NotificationManager($user))->welcome();
	}

/**
 * Event triggered when an user is activated (status = 1).
 *
 * @param \Cake\Event\Event $event
 * @param \User\Model\Entity\User $user
 * @return bool
 */
	public function onActivate(Event $event, User $user) {
		return (new NotificationManager($user))->activated();
	}

/**
 * Event triggered when user has been blocked (status = 0).
 *
 * @param \Cake\Event\Event $event
 * @param \User\Model\Entity\User $user
 * @return bool
 */
	public function onBlock(Event $event, User $user) {
		return (new NotificationManager($user))->blocked();
	}

/**
 * Event triggered when user requests to cancel his/her account.
 *
 * @param \Cake\Event\Event $event
 * @param \User\Model\Entity\User $user
 * @return bool
 */
	public function onCancel(Event $event, User $user) {
		return (new NotificationManager($user))->canceled();
	}

/**
 * Event triggered when user requests a new password
 *
 * @param \Cake\Event\Event $event
 * @param \User\Model\Entity\User $user
 * @return bool
 */
	public function onPasswordRecovery(Event $event, User $user) {
		return (new NotificationManager($user))->passwordRecovery();
	}

/**
 * Provides defaults values for settings keys.
 *
 * @param \Cake\Event\Event $event
 * @param \Cake\ORM\Entity $plugin
 * @param \ArrayObject $options
 * @param \Cake\Validation\Validator $validator
 * @return void
 */
	public function settingsBeforeValidate(Event $event, $settings, $validator) {
		$validator
			->validatePresence('message_welcome_subject')
			->notEmpty('message_welcome_subject', __d('user', 'This field cannot be empty.'))

			->validatePresence('message_welcome_body')
			->notEmpty('message_welcome_body', __d('user', 'This field cannot be empty.'))

			->validatePresence('message_password_recovery_subject')
			->notEmpty('message_password_recovery_body', __d('user', 'This field cannot be empty.'))

			->validatePresence('message_cancel_request_subject')
			->notEmpty('message_cancel_request_body', __d('user', 'This field cannot be empty.'));

		if ($settings->message_activation) {
			$validator
				->validatePresence('message_activation_subject')
				->notEmpty('message_activation_body', __d('user', 'This field cannot be empty.'));
		}

		if ($settings->message_blocked) {
			$validator
				->validatePresence('message_blocked_subject')
				->notEmpty('message_blocked_body', __d('user', 'This field cannot be empty.'));
		}

		if ($settings->message_canceled) {
			$validator
				->validatePresence('message_canceled_subject')
				->notEmpty('message_canceled_body', __d('user', 'This field cannot be empty.'));
		}
	}

}
