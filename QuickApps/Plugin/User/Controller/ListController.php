<?php
/**
 * List Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.User.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class ListController extends UserAppController {
	public $name = 'List';
	public $uses = array('User.User');

	public function admin_index() {
		if (isset($this->data['User']['update'])) {
			if (isset($this->data['Items']['id'])) {
				$update = ($this->data['User']['update'] != 'delete');

				foreach ($this->data['Items']['id'] as $key => $id) {
					if ($id === 1) {
						continue; // admin protected
					}

					if ($update) {
						// update User
						switch ($this->data['User']['update']) {
							case 'block':
								$this->requestAction('/admin/user/list/block/' . $id);
							break;

							case 'unblock':
								$this->requestAction('/admin/user/list/activate/' . $id);
							break;
						}
					} else {
						// delete User
						$this->requestAction('/admin/user/list/delete/' . $id);
					}
				}
			}

			$this->redirect($this->referer());
		}

		$paginationScope = array();

		if (isset($this->data['User']['filter']) || $this->Session->check('User.filter')) {
			if (isset($this->data['User']['filter']) && empty($this->data['User']['filter'])) {
				$this->Session->delete('User.filter');
			} else {
				$filter = isset($this->data['User']['filter']) ? $this->data['User']['filter'] : $this->Session->read('User.filter');

				foreach ($filter as $field => $value) {
					if ($value !== '') {
						$paginationScope[str_replace('|', '.', $field)] = strpos($field, 'LIKE') !== false ? "%{$value}%" : $value;
					}
				}

				$this->Session->write('User.filter', $filter);
			}
		}

		$results = $this->paginate('User', $paginationScope);

		$this->set('results', $results);
		$this->setCrumb('/admin/user/');
		$this->title(__t('Users'));
	}

	public function admin_delete($id) {
		$del = false;

		if ($id != 1) {
			$user = $this->User->findById($id) or $this->redirect($this->referer());
			$notify = $this->Variable->findByName('user_mail_canceled_notify');
			$del = $this->User->delete($id);

			if ($del && $notify) {
				$this->Mailer->send($user, 'canceled');
			}
		}

		if (isset($this->request->params['requested'])) {
			return $del;
		} else {
			$this->redirect($this->referer());
		}
	}

	public function admin_block($id) {
		$data = array();
		$data = array(
			'User' => array(
				'status' => 0,
				'id' => $id
			)
		);

		$save = $this->User->save($data, false);
		$notify = $this->Variable->findByName('user_mail_blocked_notify');

		if ($save && $notify) {
			$this->Mailer->send($this->User->id, 'blocked');
		}

		return $save;
	}

	public function admin_activate($id) {
		$data = array();
		$data = array(
			'User' => array(
				'status' => 1,
				'id' => $id
			)
		);

		$save = $this->User->save($data, false);
		$notify = $this->Variable->findByName('user_mail_activation_notify');

		if ($save && $notify) {
			$this->Mailer->send($this->User->id, 'activation');
		}

		return $save;
	}

	public function admin_add() {
		$this->__setLangs();

		if (isset($this->data['User'])) {
			if ($this->User->save($this->data)) {
				$this->Mailer->send($this->User->id, 'welcome');
				$this->flashMsg(__t('User has been saved'), 'success');
				$this->redirect('/admin/user/list');
			} else {
				$this->flashMsg(__t('User could not be saved. Please, try again.'), 'error');
			}
		}

		$this->set('fields', $this->User->fieldInstances());
		$this->set('roles',
			$this->User->Role->find('list',
				array(
					'conditions' => array(
						'Role.id NOT' => array(2, 3)
					)
				)
			)
		);
		$this->title(__t('Add User'));
		$this->setCrumb(
			'/admin/user/',
			array(__t('Add new user'))
		);
	}

	public function admin_edit($id) {
		$user = $this->User->findById($id) or $this->redirect('/admin/user/list');

		if (isset($this->data['User']['id']) && $this->data['User']['id'] == $id) {
			if ($this->User->save($this->data)) {

				/*****************/
				/* Email sending */
				/*****************/
				// Send "activated" mail
				if ($user['User']['status'] == 0 && $this->data['User']['status'] == 1) {
					$notify = $this->Variable->findByName('user_mail_activation_notify');

					if ($notify['Variable']['value']) {
						$this->Mailer->send($id, 'activation');
					}
				}

				// Send "blocked" mail
				if ($user['User']['status'] == 1 && $this->data['User']['status'] == 0) {
					$notify = $this->Variable->findByName('user_mail_blocked_notify_notify');

					if ($notify['Variable']['value']) {
						$this->Mailer->send($id, 'blocked');
					}
				}

				$this->flashMsg(__t('User has been saved'), 'success');
				$this->redirect($this->referer());
			} else {
				$this->flashMsg(__t('User could not be saved. Please, try again.'), 'error');
			}
		}

		unset($user['User']['password']);

		$this->data = $user;

		$this->__setLangs();
		$this->set('roles',
			$this->User->Role->find('list',
				array(
					'conditions' => array(
						'Role.id NOT' => array(2, 3)
					)
				)
			)
		);
		$this->title(__t('Editing User'));
		$this->setCrumb(
			'/admin/user/',
			array(__t('Editing user'))
		);
	}

	private function __setLangs() {
		$languages = array();

		foreach (Configure::read('Variable.languages') as $l) {
			$languages[$l['Language']['code']] = $l['Language']['native'];
		}

		$this->set('languages', $languages);
	}
}