<?php
/**
 * Theme Helper
 * Theme: Admin
 *
 * PHP version 5
 *
 * @package  QuickApps.Themes.Admin.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class ThemeAdminHookHelper extends AppHelper {
	public $helpers = array('ThemeAdmin.BootstrapPaginator');

	public function beforeRender($viewFile) {
		if ($this->_View->request->params['plugin'] == 'user' &&
			$this->_View->request->params['controller'] == 'user' &&
			in_array($this->_View->request->params['action'], array('login', 'admin_login'))
		) {
			$this->_View->Layout->script('login.js');
		}
	}

	public function pagination() {
		return $this->BootstrapPaginator->pagination();
	}

	public function html_table_alter(&$info) {
		$info['options']['tableOptions'] = array('class' => 'table table-bordered');
	}

	public function form_input_alter(&$info) {
		if (isset($info['options']['type']) && $info['options']['type'] == 'submit') {
			$this->__button($info);
		}
	}

	public function menu_toolbar_alter(&$info) {
		$info['options']['class'] = 'nav nav-pills';
	}

	public function form_submit_alter(&$info) {
		$this->__button($info);
	}

	public function form_button_alter(&$info) {
		$this->__button($info);
	}

	private function __button(&$info) {
		$info['options']['label'] = false;
		$info['options']['div'] = false;
		$info['options']['class'] = 'btn btn-primary';
	}
}