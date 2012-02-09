<?php
App::uses('JqueryUI', 'System.Lib');

/**
 * Jquery UI Component
 *
 * PHP version 5
 *
 * @package  QuickApps.Controller.Plugin.System
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class JqueryUIComponent extends Component {
    public $Controller;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param object $controller Controller with components to initialize
 * @return void
 */
    public function initialize($Controller) {
        $this->Controller = $Controller;
        $this->Controller->helpers[] = 'System.JqueryUI';
    }

    public function add() {
        $files = func_get_args();

        return JqueryUI::add($files, $this->Controller->Layout['javascripts']['file']);
    }

    public function theme($theme = false) {
        return JqueryUI::theme($theme, $this->Controller->Layout['stylesheets']['all']);
    }
}