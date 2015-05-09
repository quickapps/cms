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
namespace User\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Validation\Validator;
use User\Model\Entity\User;

/**
 * Main Hook Listener for User plugin.
 *
 */
class UserHook implements EventListenerInterface
{

    /**
     * Returns a list of hooks this Hook Listener is implementing. When the class
     * is registered in an event manager, each individual method will be associated
     * with the respective event.
     *
     * @return void
     */
    public function implementedEvents()
    {
        return [
            'Plugin.User.settingsValidate' => 'settingsValidate',
            'Plugin.User.settingsDefaults' => 'settingsDefaults',
        ];
    }

    /**
     * Validates plugin's settings.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param array $data Data to be validated
     * @param \Cake\Validation\Validator $validator The validator object
     * @return void
     */
    public function settingsValidate(Event $event, $data, Validator $validator)
    {
        if (isset($data['password_min_length'])) {
            $validator
                ->add('password_min_length', 'validNumber', [
                    'rule' => ['naturalNumber', false], // false: exclude zero
                    'message' => __d('user', 'Invalid password min-length.')
                ]);
        }

        $validator
            ->requirePresence('message_welcome_subject')
            ->notEmpty('message_welcome_subject', __d('user', 'This field cannot be empty.'))

            ->requirePresence('message_welcome_body')
            ->notEmpty('message_welcome_body', __d('user', 'This field cannot be empty.'))

            ->requirePresence('message_password_recovery_subject')
            ->notEmpty('message_password_recovery_body', __d('user', 'This field cannot be empty.'))

            ->requirePresence('message_cancel_request_subject')
            ->notEmpty('message_cancel_request_body', __d('user', 'This field cannot be empty.'));

        if ($data['message_activation']) {
            $validator
                ->requirePresence('message_activation_subject')
                ->notEmpty('message_activation_body', __d('user', 'This field cannot be empty.'));
        }

        if ($data['message_blocked']) {
            $validator
                ->requirePresence('message_blocked_subject')
                ->notEmpty('message_blocked_body', __d('user', 'This field cannot be empty.'));
        }

        if ($data['message_canceled']) {
            $validator
                ->requirePresence('message_canceled_subject')
                ->notEmpty('message_canceled_body', __d('user', 'This field cannot be empty.'));
        }
    }

    /**
     * Provides defaults values for settings keys.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @return array
     */
    public function settingsDefaults(Event $event)
    {
        return [
            'password_min_length' => 6,
            'password_uppercase' => 0,
            'password_lowercase' => 0,
            'password_number' => 0,
            'password_non_alphanumeric' => 0,
        ];
    }
}
