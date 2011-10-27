<?php
/**
 * User Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.User.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link     http://cms.quickapps.es
 */
class UserController extends UserAppController {
    public $components = array('Cookie', 'Session');
    public $uses = array('User.User', 'UsersRoles');

    # do not touch this method
    public function admin_index() {
        $this->redirect('/admin/user/list');
    }

    public function login() {
        if ($this->__login()) {
            $this->redirect($this->Auth->loginRedirect);
        }

        $this->title(__t('Log in'));
    }

    public function logout() {
        $this->__logout();
        $this->redirect($this->Auth->logout());
    }

    public function admin_login() {
        if ($this->__login()) {
            $this->redirect($this->Auth->loginRedirect);
        }

        $this->title(__t('Log in'));
    }

    public function admin_logout() {
        $this->__logout();
        $this->redirect($this->Auth->logout());
    }

    public function register() {
        if (isset($this->data['User'])) {
            if ($this->User->save($this->data)) {
                $this->flashMsg(__t('Registration complete. A welcome message has been sent to your e-mail address with instructions about how to active your account.'), 'success');
                $this->Mailer->send($this->User->id, 'welcome');
                $this->redirect($this->referer());
            } else {
                $this->flashMsg(__t('User could not be saved. Please, try again.'), 'error');
            }
        }

        $this->__setLangs();
        $this->set('fields', $this->User->fieldInstances());
        $this->title(__t('Create new account'));
        $this->setCrumb('/user/register');
        $this->setCrumb(array(__t('Create new user account')));
    }

    public function activate($id, $key) {
        $user = $this->User->find('first',
            array(
                'conditions' => array(
                    'User.id' => $id,
                    'User.key' => $key
                )
            )
        ) or $this->redirect('/');

        $save = array(
            'User' => array(
                'id' => $user['User']['id'],
                'status' => 1
            )
        );

        if ($this->User->save($save, false)) {
            $this->User->id = $user['User']['id'];
            $this->User->saveField('last_login', time());

            $user = $this->User->read(); # read again because key has changed

            unset($user['User']['password']);

            $session = $user['User'];
            $session['role_id'] = $this->UsersRoles->find('all',
                array(
                    'conditions' => array('UsersRoles.user_id' => $session['id']),
                    'fields' => array('role_id', 'user_id')
                )
            );
            $session['role_id'] = Set::extract('/UsersRoles/role_id', $session['role_id']);
            $session['role_id'][] = 2; #role: authenticated user

            $this->Auth->login($session);
            $this->redirect('/user/my_account');
        }
    }

    public function password_recovery() {
        if (isset($this->data['User'])) {
            if ($user = $this->User->findByEmail($this->data['User']['email'])) {
                $this->Mailer->send($user['User']['id'], 'password_recovery');
                $this->flashMsg(__t('Further instructions have been sent to your e-mail address.'), 'success');
            } else {
                $this->User->invalidate('email', __t('invalid email'));
                $this->flashMsg(__t('Sorry, %s is not recognized as e-mail address.', $this->data['User']['email']), 'error');
            }
        }

        $this->title(__t('Request new password'));
    }

    public function profile($username = null) {
        $user = $this->User->findByUsername($username) or $this->redirect('/');

        unset($user['User']['password'], $user['User']['key']);
        $this->title(__t('%s profile', $user['User']['username']));
        $this->set('result', $user);
    }

    function my_account() {
        $this->__setLangs();

        if (isset($this->data['User'])) {
            $data = $this->data;
            $session = $this->Session->read('Auth.User');
            $data['User']['id'] = $session['id'];
            $data['User']['username'] = $session['username'];

            if ($this->User->save($data)) {
                $this->flashMsg(__t('User has been saved'), 'success');
                $this->redirect($this->referer());
            } else {
                $this->flashMsg(__t('User could not be saved. Please, try again.'), 'error');
            }
        }

        $this->data = $this->User->findById($this->Session->read('Auth.User.id')) or $this->redirect('/');
    }

    private function __login() {
        $this->User->unbindFields();
        $cookie = $this->Cookie->read('UserLogin');

        if (isset($this->data['User'])) {
            if ($this->Auth->login()) {
                $session = $this->Auth->user();
                $session['role_id'] = $this->UsersRoles->find('all',
                    array(
                        'conditions' => array('UsersRoles.user_id' => $session['id']),
                        'fields' => array('role_id', 'user_id')
                    )
                );
                $session['role_id'] = Set::extract('/UsersRoles/role_id', $session['role_id']);
                $session['role_id'][] = 2; #role: authenticated user
                $this->User->id = $session['id'];

                if (isset($this->data['User']['remember']) && $this->data['User']['remember'] == 1) {
                    $user = $this->User->read();

                    $this->Cookie->write('UserLogin',
                        array(
                            'id' => $session['id'],
                            'password' => $user['User']['password']
                        ), true, '+999 Days'
                    );
                }

                $this->User->saveField('last_login', time());
                $this->Auth->login($session);

                return true;
            }

            $this->flashMsg(__t('Invalid username or password'), 'error');

            return false;

        } elseif (isset($cookie['id']) && !empty($cookie['id']) && isset($cookie['password']) && !empty($cookie['password'])) {
            $user = $this->User->find('first',
                array(
                    'conditions' => array(
                        'User.id' => @$cookie['id'],
                        'User.password' => @$cookie['password']
                    )
                )
            );

            if ($user) {
                $session = $user['User'];
                $session['role_id'] = $this->UsersRoles->find('all',
                    array(
                        'conditions' => array('UsersRoles.user_id' => $user['User']['id']),
                        'fields' => array('role_id', 'user_id')
                    )
                );
                $session['role_id'] = Set::extract('/UsersRoles/role_id', $session['role_id']);
                $session['role_id'][] = 2; #role: authenticated user

                $this->Auth->login($session);

                return true;
            }

            $this->Cookie->delete('UserLogin');

            return false;
        }

        return false;
    }

    private function __logout() {
        $this->Cookie->delete('UserLogin');
        $this->Session->delete('Auth');
        $this->flashMsg(__t('Log out successful.'), 'success');

        return true;
    }

    private function __setLangs() {
        $languages = array();

        foreach (Configure::read('Variable.languages') as $l) {
            $languages[$l['Language']['code']] = $l['Language']['native'];
        }

        $this->set('languages', $languages);
    }
}