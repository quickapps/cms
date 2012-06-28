<?php
/**
 * Roles Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.User.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class RolesController extends UserAppController {
	public $name = 'Roles';
	public $uses = array('User.User');

	public function admin_index() {
		if (isset($this->data['Role'])) {
			if ($this->User->Role->save($this->data)) {
				$this->flashMsg(__t('Role has been saved'), 'success');
				$this->redirect($this->referer());
			} else {
				$this->flashMsg(__t('Role could not be saved. Please, try again.'), 'error');
			}
		}

		$roles = $this->User->Role->find('all');

		$this->set('results', $roles);
		$this->setCrumb(
			'/admin/user/',
			array(__t('User roles'))
		);
		$this->title(__t('User Roles'));
	}

	public function admin_edit($id) {
		if (isset($this->data['Role'])) {
			if ($this->User->Role->save($this->data)) {
				$this->flashMsg(__t('Role has been saved'), 'success');
				$this->redirect($this->referer());
			} else {
				$this->flashMsg(__t('Role could not be saved. Please, try again.'), 'error');
			}
		}

		$this->data = $this->User->Role->findById($id) or $this->redirect('/admin/user/roles');

		$this->setCrumb(
			'/admin/user/',
			array(__t('User roles'), '/admin/user/roles'),
			array(__t('Editing role'))
		);
		$this->title(__t('Editing Role'));
	}

	public function admin_delete($id) {
		if (in_array($id, array(1, 2, 3))) {
			$this->redirect('/admin/user/roles');
		}

		$this->User->Role->delete($id);
		$this->redirect('/admin/user/roles');
	}
}