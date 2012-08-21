<?php
/**
 * Display Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.User.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class DisplayController extends UserAppController {
	public $name = 'Display';
	public $uses = array('Field.Field');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->QuickApps->enableSecurity();
	}

	public function admin_index($display = false) {
		if (!$display && !isset($this->data['User']['displayModes'])) {
			$this->redirect("/admin/user/display/index/default");
		}

		if (isset($this->data['User']['displayModes'])) {
			// set available view modes
			$this->Field->setViewModes($this->data['User']['displayModes'], array('Field.belongsTo' => 'User'));
			$this->redirect($this->referer());
		}

		$fields = $this->Field->find('all',  array('conditions' => array('Field.belongsTo' => 'User')));
		$fields = @Hash::sort((array)$fields, '{n}.Field.settings.display.' . $display . '.ordering', 'asc');
		$data['User']['displayModes'] = isset($fields[0]['Field']['settings']['display']) ? array_keys($fields[0]['Field']['settings']['display']) : array();
		$this->data = $data;

		$this->set('result', $fields);
		$this->set('display', $display);
		$this->setCrumb(
			'/admin/user',
			array(__t('Manage Display'))
		);
		$this->title(__t('User Display Settings'));
	}

	public function admin_field_formatter($id) {
		$display = isset($this->request->params['named']['display']) ? $this->request->params['named']['display'] : false;
		$displayModes = array_keys(QuickApps::displayModes('Node'));

		if (!in_array($display, $displayModes)) {
			$this->redirect($this->referer());
		}

		$field = $this->Field->findById($id) or $this->redirect($this->referer());

		if (isset($this->data['Field'])) {
			if ($this->Field->save($this->data)) {
				$this->flashMsg(__t('Field has been saved.'), 'success');
				$this->redirect($this->referer());
			} else {
				$this->flashMsg(__t('Field could not be saved. Please, try again.'), 'error');
			}
		} else {
			$this->data = $field;
		}

		$this->setCrumb(
			'/admin/user/',
			array(__t('User Display Settings')),
			array(__t('Field display settings'))
		);
		$this->title(__t('Field Display Settings'));
		$this->set('display', $display);
	}
}