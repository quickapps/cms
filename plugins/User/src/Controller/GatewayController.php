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

use Cake\Cache\Cache;
use Cake\Event\Event;
use Locale\Utility\LocaleToolbox;
use QuickApps\Core\Plugin;
use User\Controller\AppController;

/**
 * Gateway controller.
 *
 * Provides login and logout methods.
 */
class GatewayController extends AppController
{

    /**
     * Mark as allowed some basic actions.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['login', 'logout', 'unauthorized', 'forgot', 'activation_email', 'register']);
        $this->viewPath = 'Gateway';
    }

    /**
     * Renders the login form.
     *
     * @return void
     */
    public function login()
    {
        $this->loadModel('User.Users');
        $this->layout = 'login';

        if ($this->request->is('post')) {
            $loginBlocking = Plugin::settings('User', 'failed_login_attempts') && Plugin::settings('User', 'failed_login_attempts_block_seconds');
            $user = false;
            $continue = true;
            if ($loginBlocking) {
                Cache::config('users_login', [
                    'duration' => '+' . Plugin::settings('User', 'failed_login_attempts_block_seconds') . ' seconds',
                    'path' => TMP,
                    'engine' => 'File',
                    'prefix' => 'qa_',
                    'groups' => ['acl']
                ]);

                $cacheName = 'login_failed_' . env('REMOTE_ADDR');
                $cache = Cache::read($cacheName, 'users_login');
                $cacheStruct = [
                    'attempts' => 0,
                    'last_attempt' => 0,
                    'ip' => '',
                    'request_log' => []
                ];

                if ($cache && $cache['attempts'] >= Plugin::settings('User', 'failed_login_attempts')) {
                    $this->Flash->warning(__d('user', 'You have reached the maximum number of login attempts. Try again in {0} minutes.', Plugin::settings('User', 'failed_login_attempts_block_seconds') / 60));
                    $continue = false;
                }
            }

            if ($continue) {
                $user = $this->Auth->identify();
                if ($user) {
                    $this->Auth->setUser($user);
                    if (!empty($user['id'])) {
                        try {
                            $user = $this->Users->get($user['id']);
                            if ($user) {
                                $this->Users->touch($user, 'Users.login');
                                $this->Users->save($user, ['validate' => false]);
                            }
                        } catch (\Exception $e) {
                        }
                    }
                    return $this->redirect($this->Auth->redirectUrl());
                } else {
                    if ($loginBlocking) {
                        $cache = array_merge($cacheStruct, (array)$cache);
                        $cache['attempts'] += 1;
                        $cache['last_attempt'] = time();
                        $cache['ip'] = env('REMOTE_ADDR');
                        $cache['request_log'][] = [
                            'data' => $this->request->data,
                            'time' => time(),
                        ];
                        Cache::write($cacheName, $cache, 'users_login');
                    }
                    $this->Flash->danger(__d('user', 'Username or password is incorrect.'));
                }
            }
        }

        $user = $this->Users->newEntity();
        $this->set(compact('user'));
    }

    /**
     * Logout.
     *
     * @return void
     */
    public function logout()
    {
        $result = $this->Auth->logout();
        $this->layout = 'login';

        if ($result) {
            return $this->redirect($result);
        } else {
            $this->Flash->danger(__d('user', 'Something went wrong, and logout operation could not be completed.'));
            return $this->redirect($this->referer());
        }
    }

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
                $emailSent = $this->trigger('User.passwordRequest', $user)->result;
                if ($emailSent) {
                    $this->Flash->success(__d('user', 'Further instructions have been sent to your e-mail address.'));
                } else {
                    $this->Flash->warning(__d('user', 'Instructions could not been sent to your e-mail address, please try again later.'));
                }
            } else {
                $this->Flash->danger(__d('user', 'Sorry, "{0}" is not recognized as a user name or an e-mail address.', $this->request->data['username']));
            }
        }
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
        $emailSent = $this->trigger('User.cancelRequest', $user)->result;
        if ($emailSent) {
            $this->Flash->success(__d('user', 'Further instructions have been sent to your e-mail address.'));
        } else {
            $this->Flash->warning(__d('user', 'Instructions could not been sent to your e-mail address, please try again later.'));
        }

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
                $this->trigger('User.canceled', $user);
                $this->Flash->success(__d('user', 'Account successfully canceled'));
            } else {
                $this->Flash->danger(__d('user', 'Account could not be canceled due to an internal error, please try again later.'));
            }
        } else {
            $this->Flash->warning(__d('user', 'Not user was found, invalid cancellation URL.'));
        }

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
        $this->Users->unbindFieldable();
        $user = $this->Users->newEntity();
        $registered = false;
        $languages = LocaleToolbox::languagesList();

        if ($this->request->data) {
            $user->set('status', 0);
            $user->accessible(['id', 'token', 'status', 'last_login', 'created', 'roles'], false);
            $user = $this->Users->patchEntity($user, $this->request->data);

            if ($this->Users->save($user)) {
                $this->trigger('User.registered', $user);
                $this->Flash->success(__d('user', 'Account successfully created, further instructions have been sent to your e-mail address.', ['key' => 'register']));
                $registered = true;
            } else {
                $this->Flash->danger(__d('user', 'Account could not be created, please check your information.'), ['key' => 'register']);
            }
        }

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
                $this->trigger('User.registered', $user);
                $this->Flash->success(__d('user', 'Instructions have been sent to your e-mail address.'), ['key' => 'activation_email']);
                $sent = true;
            } else {
                $this->Flash->danger(__d('user', 'No account was found matching the given username/email.'), ['key' => 'activation_email']);
            }
        }

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
                $this->trigger('User.activated', $user);
                $activated = true;
                $this->Flash->success(__d('user', 'Account successfully activated.'), ['key' => 'activate']);
            } else {
                $this->Flash->danger(__d('user', 'Account could not be activated, please try again later.'), ['key' => 'activate']);
            }
        } else {
            $this->Flash->warning(__d('user', 'Account not found or is already active.'), ['key' => 'activate']);
        }

        $this->set(compact('activated', 'token'));
    }

    /**
     * Renders the "unauthorized" screen, when an user attempts to access
     * to a restricted area.
     *
     * @return void
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

        if ($this->request->data) {
            $user->accessible(['id', 'username', 'roles', 'status'], false);
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__d('user', 'User information successfully updated!'), ['key' => 'user_profile']);
                $this->redirect($this->referer());
            } else {
                $this->Flash->danger(__d('user', 'User information could not be saved, please check your information.'), ['key' => 'user_profile']);
            }
        }

        $this->switchViewMode('full');
        $this->set(compact('user', 'languages'));
    }

    /**
     * Shows profile information for the given user.
     *
     * @param int $id User's ID
     * @return void
     * @throws \Cake\ORM\Exception\RecordNotFoundException When user not found, or
     *  users has marked profile as private
     */
    public function profile($id)
    {
        $this->loadModel('User.Users');

        $conditions = [];
        if ($id != user()->id) {
            $conditions = ['status' => 1, 'public_profile' => true];
        }

        $user = $this->Users->get($id, ['conditions' => $conditions]);
        $this->switchViewMode('full');
        $this->set(compact('user'));
    }
}
