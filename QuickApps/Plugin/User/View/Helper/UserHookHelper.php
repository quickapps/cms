<?php
/**
 * User View Hooks
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.User.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class UserHookHelper extends AppHelper {
/**
 * Toolbar menu for section: `Users`.
 *
 * @return void
 */
	public function beforeLayout($layoutFile) {
		if (Router::getParam('admin') &&
			$this->request->params['plugin'] == 'user' &&
			$this->request->params['action'] == 'admin_index'
		) {
			$this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar') . '<!-- NodeHookHelper -->'), 'toolbar');
		}

		return true;
	}
}