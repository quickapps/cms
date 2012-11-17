<?php
/**
 * User Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.User.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class UserController extends UserAppController {
	public $components = array('Cookie', 'Session');
	public $uses = array('User.User', 'UsersRoles');

	public function admin_index() {
		$this->redirect('/admin/user/list');
	}

	public function login() {
		if ($this->__login()) {
			$this->hook('login_successful');
			$this->redirect($this->Auth->loginRedirect);
		} else {
			$this->hook('login_failed');
		}

		$this->title(__t('Login'));
	}

	public function logout() {
		$this->__logout();
		$this->redirect($this->Auth->logout());
	}

	public function admin_login() {
		if ($this->__login()) {
			$this->redirect($this->Auth->loginRedirect);
		}

		$this->title(__t('Login'));
	}

	public function admin_logout() {
		$this->__logout();
		$this->redirect($this->Auth->logout());
	}

	public function register() {
		if (isset($this->data['User'])) {
			if ($this->User->save($this->data)) {
				if ($this->Mailer->send($this->User->id, 'welcome')) {
					$this->flashMsg(__t('Registration complete. A welcome message has been sent to your e-mail address with instructions on how to active your account.'), 'success');
				} else {
					$this->flashMsg(implode('<br />', $this->Mailer->errors), 'error');
				}

				$this->redirect($this->referer());
			} else {
				$this->flashMsg(__t('User could not be saved. Please, try again.'), 'error');
			}
		}

		$this->__setLangs();
		$this->set('fields', $this->User->fieldInstances());
		$this->title(__t('Create new account'));
		$this->setCrumb(
			'/user/register',
			array(__t('Create new user account'))
		);
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
			$notify = $this->Variable->findByName('user_mail_activation_notify');
			$this->User->id = $user['User']['id'];
			$this->User->saveField('last_login', time());

			// read again because key has changed
			$user = $this->User->read();

			unset($user['User']['password']);

			$session = $user['User'];
			$session['role_id'] = $this->UsersRoles->find('all',
				array(
					'conditions' => array('UsersRoles.user_id' => $session['id']),
					'fields' => array('role_id', 'user_id')
				)
			);
			$session['role_id'] = Hash::extract($session['role_id'], '{n}.UsersRoles.role_id');
			$session['role_id'][] = 2; // authenticated user

			$this->Auth->login($session);

			if ($notify) {
				$this->Mailer->send($id, 'activation');
			}

			$this->redirect('/user/my_account');
		}
	}

	public function password_recovery() {
		if (isset($this->data['User'])) {
			if ($user = $this->User->findByEmail($this->data['User']['email'])) {
				$this->User->saveField('key', String::uuid());

				if ($this->Mailer->send($user['User']['id'], 'password_recovery')) {
					$this->flashMsg(__t('Further instructions have been sent to your e-mail address.'), 'success');
				} else {
					$this->flashMsg(implode('<br />', $this->Mailer->errors), 'error');
				}
			} else {
				$this->User->invalidate('email', __t('invalid email'));
				$this->flashMsg(__t('Sorry, %s is not recognized as e-mail address.', $this->data['User']['email']), 'error');
			}

			$this->redirect($this->referer());
		}

		$this->title(__t('Request new password'));
	}

	public function profile($username = null) {
		$user = $this->User->findByUsername($username) or $this->redirect('/');
		$this->data = $user;
		$this->Layout['display'] = 'user_profile';

		unset($user['User']['password'], $user['User']['key']);
		$this->title(__t('%s profile', $user['User']['username']));
	}

	function my_account() {
		if (isset($this->data['User'])) {
			$data = $this->data;
			$session = $this->Session->read('Auth.User');
			$data['User']['id'] = $session['id'];
			$data['User']['username'] = $session['username'];
			unset($data['Role']);

			if ($this->User->save($data)) {
				$this->flashMsg(__t('User has been saved'), 'success');
				$this->redirect($this->referer());
			} else {
				$this->flashMsg(__t('User could not be saved. Please, try again.'), 'error');
			}
		}

		$this->__setLangs();

		$this->data = $this->User->findById($this->Session->read('Auth.User.id')) or $this->redirect('/');
	}

	private function __login() {
		if (!isset($this->data['User'])) {
			return false;
		}

		$return = false;
		$loginBlocking = Configure::read('Variable.user_login_attempts') && Configure::read('Variable.user_login_attempts_time');

		if ($loginBlocking) {
			Cache::config('users_login',
				array(
					'engine' => 'File',
					'duration' => '+' . Configure::read('Variable.user_login_attempts_time') . ' seconds',
					'path' => CACHE
				)
			);

			$attemptStruct = array(
				'attempts' => 0,
				'last_attempt' => 0,
				'ip' => '',
				'login_stack' => array()
			);
			$cacheName = 'login_failed_' . env('REMOTE_ADDR');
			$cache = Cache::read($cacheName, 'users_login');

			if ($cache && $cache['attempts'] >= Configure::read('Variable.user_login_attempts')) {
				$this->flashMsg(__t('You have reached the maximum number of login attempts. Try again in %d minutes.', Configure::read('Variable.user_login_attempts_time') / 60));

				return false;
			}
		}

		$this->User->unbindFieldable();

		if (isset($this->data['User'])) {
			$data = $this->data;
			$this->hook('before_login', $data);

			$this->data = $data;

			if ($this->Auth->login()) {
				$session = $this->Auth->user();
				$session['role_id'] = $this->UsersRoles->find('all',
					array(
						'conditions' => array('UsersRoles.user_id' => $session['id']),
						'fields' => array('role_id', 'user_id')
					)
				);
				$session['role_id'] = Hash::extract($session['role_id'], '{n}.UsersRoles.role_id');
				$session['role_id'][] = 2; // 2: authenticated user
				$this->User->id = $session['id'];

				$this->User->saveField('key', String::uuid(), array('callbacks' => false)); // generate new key, no bacllbaks: prevent key regeneration
				$this->hook('after_login', $session);

				if (isset($this->data['User']['remember']) && $this->data['User']['remember'] == 1) {
					$user = $this->User->read();

					$this->Cookie->httpOnly = true;
					$this->Cookie->write('UserLogin',
						array(
							'id' => $user['User']['id'],
							'hash' => $user['User']['password']
						), true, '1 year'
					);
				}

				$this->User->saveField('last_login', time(), array('callbacks' => false)); // last login stamp, no bacllbaks: prevent key regeneration
				$this->Auth->login($session);
				$this->flashMsg(__t('Logged in successfully.'), 'success');

				$return = true;
			} else {
				$this->flashMsg(__t('Invalid username or password'), 'error');
			}

			if (!$return && $loginBlocking) {
				$cache = array_merge($attemptStruct, (array)$cache);
				$cache['attempts'] += 1;
				$cache['last_attempt'] = time();
				$cache['ip'] = env('REMOTE_ADDR');
				$cache['login_stack'][] = array(
					'username' => $this->data['User']['username'],
					'key' => $this->data['User']['key'],
					'time' => time()
				);

				$this->hook('login_failed_cache_alter', $cache);
				Cache::write($cacheName, $cache, 'users_login');
			}
		}

		return $return;
	}

	private function __logout() {
		$this->hook('before_logout', $session = $this->Auth->user());
		$this->Cookie->delete('UserLogin');
		$this->Session->delete('Auth');
		$this->flashMsg(__t('Logout successful.'), 'success');
		$this->hook('after_logout');

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
