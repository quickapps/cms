<?php
/**
 * Menu View Hooks
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Menu.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link     http://cms.quickapps.es
 */
class MenuHookHelper extends AppHelper {
    function beforeLayout($layoutFile) {
        if (isset($this->request->params['plugin']) && 
            $this->request->params['plugin'] == 'menu' && 
            $this->request->params['action'] == 'admin_index' && 
            $this->request->controller == 'manage'
        ) {
            $this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar')), 'toolbar');
        }

        return true;
    }
}