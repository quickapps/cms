<?php
/**
 * System Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class SystemController extends SystemAppController {
	public $name = 'System';
	public $uses = array();

	public function admin_index() {
		$this->redirect("/admin/system/dashboard");
	}
}