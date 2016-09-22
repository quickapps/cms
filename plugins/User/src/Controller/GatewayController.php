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
namespace User\Controller;

use Locale\Utility\LocaleToolbox;
use User\Controller\AppController;
use User\Controller\UserSignTrait;
use User\Notification\NotificationManager;

/**
 * Gateway controller.
 *
 * Provides login and logout methods.
 *
 * @property \User\Model\Table\UsersTable $Users
 * @method bool touch(\Cake\ORM\Entity $entity, string $eventName)
 * @method void fieldable()
 */
class GatewayController extends AppController
{

    use UserSignTrait;

    /**
     * Starts the password recovery process.
     *
     * @return void
     */
    public function forgot()
    {
        if (!empty($this->request->data['username'])) {
            $this->loadModel('User.Users');
            $user = $this->Users
                ->find()
                ->where(['Users.username' => $this->request->data['username']])
                ->orWhere(['Users.email' => $this->request->data['username']])
                ->first();

            if ($user) {
                $emailSent = NotificationManager::passwordRequest($user)->send();
                if ($emailSent) {
                    $this->Flash->success(__d('user', 'Further instructions have been sent to your e-mail address.'));
                } else {
                    $this->Flash->warning(__d('user', 'Instructions could not been sent to your e-mail address, please try again later.'));
                }
            } else {
                $this->Flash->danger(__d('user', 'Sorry, "{0}" is not recognized as a user name or an e-mail address.', $this->request->data['username']));
            }
        }

        $this->title(__d('user', 'Password Recovery'));
    }

    /**
     * Here is where users can request to remove their accounts.
     *
     * Only non-administrator users can be canceled this way. User may request to
     * cancel their accounts by using the form rendered by this action, an e-mail
     * will be send with a especial link which will remove the account.
     *
     * @return void Redirects to previous page
     */
    public function cancelRequest()
    {
        $user = user();

        $this->loadModel('User.Users');
        $user = $this->Users->get($user->id);
        $emailSent = NotificationManager::cancelRequest($user)->send();
        if ($emailSent) {
            $this->Flash->success(__d('user', 'Further instructions have been sent to your e-mail address.'));
        } else {
            $this->Flash->warning(__d('user', 'Instructions could not been sent to your e-mail address, please try again later.'));
        }

        $this->title(__d('user', 'Account Cancellation'));
        $this->redirect($this->referer());
    }

    /**
     * Here is where user's account is actually removed.
     *
     * @param int $userId The ID of the user whose account is being canceled
     * @param string $code Cancellation code, code is a MD5 hash of user's encrypted
     *  password + site's salt
     * @return void Redirects to previous page
     */
    public function cancel($userId, $code)
    {
        $this->loadModel('User.Users');
        $user = $this->Users
            ->find()
            ->where(['id' => $userId])
            ->contain(['Roles'])
            ->limit(1)
            ->first();

        if (in_array(ROLE_ID_ADMINISTRATOR, $user->role_ids) &&
            $this->Users->countAdministrators() === 1
        ) {
            $this->Flash->warning(__d('user', 'You are the last administrator in the system, your account cannot be canceled.'));
            $this->redirect($this->referer());
        }

        if ($user && $code == $user->cancel_code) {
            if ($this->Users->delete($user)) {
                NotificationManager::canceled($user)->send();
                $this->Flash->success(__d('user', 'Account successfully canceled'));
            } else {
                $this->Flash->danger(__d('user', 'Account could not be canceled due to an internal error, please try again later.'));
            }
        } else {
            $this->Flash->warning(__d('user', 'Not user was found, invalid cancellation URL.'));
        }

        $this->title(__d('user', 'Account Cancellation'));
        $this->redirect($this->referer());
    }

    /**
     * Registers a new user.
     *
     * @return void
     */
    public function register()
    {
        $this->loadModel('User.Users');
        $this->Users->fieldable(false);
        $user = $this->Users->newEntity();
        $registered = false;
        $languages = LocaleToolbox::languagesList();

        if ($this->request->data()) {
            $user->set('status', 0);
            $user->accessible(['id', 'token', 'status', 'last_login', 'created', 'roles'], false);
            $user = $this->Users->patchEntity($user, $this->request->data);

            if ($this->Users->save($user)) {
                NotificationManager::welcome($user)->send();
                $this->Flash->success(__d('user', 'Account successfully created, further instructions have been sent to your e-mail address.', ['key' => 'register']));
                $registered = true;
            } else {
                $this->Flash->danger(__d('user', 'Account could not be created, please check your information.'), ['key' => 'register']);
            }
        }

        $this->title(__d('user', 'Registration'));
        $this->set(compact('registered', 'user', 'languages'));
    }

    /**
     * Users can request to re-send activation instructions to their email address.
     *
     * @return void
     */
    public function activationEmail()
    {
        $this->loadModel('User.Users');
        $sent = false;

        if (!empty($this->request->data['username'])) {
            $user = $this->Users
                ->find()
                ->where([
                    'OR' => [
                        'username' => $this->request->data['username'],
                        'email' => $this->request->data['username'],
                    ],
                    'status' => 0
                ])
                ->limit(1)
                ->first();

            if ($user) {
                NotificationManager::welcome($user)->send();
                $this->Flash->success(__d('user', 'Instructions have been sent to your e-mail address.'), ['key' => 'activation_email']);
                $sent = true;
            } else {
                $this->Flash->danger(__d('user', 'No account was found matching the given username/email.'), ['key' => 'activation_email']);
            }
        }

        $this->title(__d('user', 'Activation Request'));
        $this->set(compact('sent'));
    }

    /**
     * Activates a registered user.
     *
     * @param string $token A valid user token
     * @return void
     */
    public function activate($token = null)
    {
        $activated = false;
        if ($token === null) {
            $this->redirect('/');
        }

        $this->loadModel('User.Users');
        $user = $this->Users
            ->find()
            ->select(['id', 'name', 'token'])
            ->where(['status' => 0, 'token' => $token])
            ->limit(1)
            ->first();

        if ($user) {
            if ($this->Users->updateAll(['status' => 1], ['id' => $user->id])) {
                NotificationManager::activated($user)->send();
                $activated = true;
                $this->Flash->success(__d('user', 'Account successfully activated.'), ['key' => 'activate']);
            } else {
                $this->Flash->danger(__d('user', 'Account could not be activated, please try again later.'), ['key' => 'activate']);
            }
        } else {
            $this->Flash->warning(__d('user', 'Account not found or is already active.'), ['key' => 'activate']);
        }

        $this->title(__d('user', 'Account Activation'));
        $this->set(compact('activated', 'token'));
    }

    /**
     * Renders the "unauthorized" screen, when an user attempts to access
     * to a restricted area.
     *
     * @return \Cake\Network\Response|null
     */
    public function unauthorized()
    {
        $this->loadModel('User.Users');
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->danger(__d('user', 'Username or password is incorrect'));
            }
        }
    }

    /**
     * Renders user's "my profile" form.
     *
     * Here is where user can change their information.
     *
     * @return void
     */
    public function me()
    {
        $this->loadModel('User.Users');
        $user = $this->Users->get(user()->id, ['conditions' => ['status' => 1]]);
        $languages = LocaleToolbox::languagesList();

        if ($this->request->data()) {
            $user->accessible(['id', 'username', 'roles', 'status'], false);
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__d('user', 'User information successfully updated!'), ['key' => 'user_profile']);
                $this->redirect($this->referer());
            } else {
                $this->Flash->danger(__d('user', 'User information could not be saved, please check your information.'), ['key' => 'user_profile']);
            }
        }

        $this->title(__d('user', 'Account'));
        $this->set(compact('user', 'languages'));
        $this->viewMode('full');
    }

    /**
     * Shows profile information for the given user.
     *
     * @param int|null $id User's ID, or NULL for currently logged user
     * @return void
     * @throws \Cake\ORM\Exception\RecordNotFoundException When user not found, or
     *  users has marked profile as private
     */
    public function profile($id = null)
    {
        $this->loadModel('User.Users');
        $id = $id === null ? user()->id : $id;
        $conditions = [];

        if ($id != user()->id) {
            $conditions = ['status' => 1, 'public_profile' => true];
        }

        $user = $this->Users->get(intval($id), ['conditions' => $conditions]);

        $this->title(__d('user', 'User’s Profile'));
        $this->viewMode('full');
        $this->set(compact('user'));
    }
}
