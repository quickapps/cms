<?php
/**
 * Block View Hooks
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Block.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class BlockHookHelper extends AppHelper {
/**
 * Toolbar menu for section: `Structure/Blocks`.
 *
 * @return void
 */
	public function beforeLayout($layoutFile) {
		if (isset($this->request->params['plugin']) &&
			$this->request->params['plugin'] == 'block' &&
			$this->request->params['action'] != 'admin_add'
		) {
			$this->_View->Block->push(array('body' => $this->_View->element('toolbar')), 'toolbar');
		}

		return true;
	}
}