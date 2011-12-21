<?php
/**
 * Block View Hooks
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Block.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link     http://cms.quickapps.es
 */
class BlockHookHelper extends AppHelper {
    public function beforeLayout() {
        if (isset($this->request->params['plugin']) &&
            $this->request->params['plugin'] == 'block' &&
            $this->request->params['action'] != 'admin_add'
        ) {
            $this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar')), 'toolbar');
        }

        return true;
    }
}