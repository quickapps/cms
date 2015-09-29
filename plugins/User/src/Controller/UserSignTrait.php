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

/**
 * Provides "Sign In" & "Sign Out" controller actions.
 */
trait UserSignTrait
{

    /**
     * Mark as allowed some basic actions.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        $this->Auth->allow(['login', 'logout', 'unauthorized', 'forgot', 'activationEmail', 'register']);
        $this->viewBuilder()->templatePath('Gateway');
    }

    /**
     * Renders the login form.
     *
     * @return \Cake\Network\Response|null
     */
    public function login()
    {
        $this->loadModel('User.Users');
        $this->viewBuilder()->layout('login');

        if ($this->request->is('post')) {
            $loginBlocking =
                plugin('User')->settings('failed_login_attempts') &&
                plugin('User')->settings('failed_login_attempts_block_seconds');
            $continue = true;

            if ($loginBlocking) {
                Cache::config('users_login', [
                    'duration' => '+' . plugin('User')->settings('failed_login_attempts_block_seconds') . ' seconds',
                    'path' => CACHE,
                    'engine' => 'File',
                    'prefix' => 'qa_',
                    'groups' => ['acl']
                ]);

                $cacheName = 'login_failed_' . env('REMOTE_ADDR');
                $cache = Cache::read($cacheName, 'users_login');

                if ($cache && $cache['attempts'] >= plugin('User')->settings('failed_login_attempts')) {
                    $blockTime = (int)plugin('User')->settings('failed_login_attempts_block_seconds');
                    $this->Flash->warning(__d('user', 'You have reached the maximum number of login attempts. Try again in {0} minutes.', $blockTime / 60));
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
                                $this->Users->save($user);
                            }
                        } catch (\Exception $e) {
                            // invalid user
                        }
                    }
                    return $this->redirect($this->Auth->redirectUrl());
                } else {
                    if ($loginBlocking && isset($cache) && isset($cacheName)) {
                        $cacheStruct = [
                            'attempts' => 0,
                            'last_attempt' => 0,
                            'ip' => '',
                            'request_log' => []
                        ];
                        $cache = array_merge($cacheStruct, $cache);
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

        $this->title(__d('user', 'Login'));
        $this->set(compact('user'));
    }

    /**
     * Logout.
     *
     * @return \Cake\Network\Response|null
     */
    public function logout()
    {
        $result = $this->Auth->logout();
        $this->viewBuilder()->layout('login');
        $this->title(__d('user', 'Logout'));

        if ($result) {
            return $this->redirect($result);
        } else {
            $this->Flash->danger(__d('user', 'Something went wrong, and logout operation could not be completed.'));
            return $this->redirect($this->referer());
        }
    }
}
