<?php
/**
 * System Controller Hooks
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.System.Controller.Component
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class SystemHookComponent extends Component {
    public $Controller = null;
    public $components = array('Hook');

    public function initialize(&$Controller) {
        $this->Controller = $Controller;
    }
}