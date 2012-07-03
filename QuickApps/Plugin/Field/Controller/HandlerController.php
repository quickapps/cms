<?php
/**
 * Field Handler Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Field.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class HandlerController extends FieldAppController {
	public $name = 'Handler';
	public $uses = array('Field.Field');

	public function admin_delete($id) {
		$field = $this->Field->findById($id) or $this->redirect($this->referer());

		$this->Field->delete($id);
		$this->redirect($this->referer());
	}

	public function admin_move($id, $dir, $view_mode = false) {
		$this->Field->move($id, $dir, $view_mode);
		$this->redirect($this->referer());
	}
}