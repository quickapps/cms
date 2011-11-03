<?php
class SettingsController extends AppController {
    var $name = 'Settings';
    var $uses = array();

    function admin_index() {
        $this->setCrumb('/admin/system/themes');
        $this->set('title_for_layout', __t('Theme Settings'));
    }
}