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
	public function beforeRender($viewFile) {
		if ($this->_View->request->params['plugin'] == 'user' &&
			$this->_View->request->params['controller'] == 'user' &&
			in_array($this->_View->request->params['action'], array('login', 'admin_login'))
		) {
			$this->_View->viewVars['Layout']['stylesheets']['all'] = array();
			$this->_View->Layout->css('reset.css');
			$this->_View->Layout->css('login.css');
			$this->_View->Layout->script('login.js');
		}
	}
}