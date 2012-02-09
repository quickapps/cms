<?php
/**
 * Jquery UI Helper
 *
 * PHP version 5
 *
 * @package  QuickApps.Controller.Plugin.System
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class JqueryUIHelper extends AppHelper {
    public function add() {
        $files = func_get_args();

        return JqueryUI::add($files, $this->_View->viewVars['Layout']['javascripts']['file']);
    }

    public function theme($theme = false) {
        return JqueryUI::theme($theme, $this->_View->viewVars['Layout']['stylesheets']['all']);
    }
}