<?php
/**
 * Menu Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Menu.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class MenuController extends MenuAppController {
	public $name = 'Menu';
	public $uses = array();

	public function admin_index() {
		$this->redirect('/admin/menu/manage');
	}
}