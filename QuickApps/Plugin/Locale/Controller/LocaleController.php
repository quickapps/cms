<?php
/**
 * Locale Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Locale.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class LocaleController extends LocaleAppController {
    public $name = 'Locale';
    public $uses = array();

    public function admin_index() {
        $this->redirect('/admin/locale/languages');
    }
}