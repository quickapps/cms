<?php
/**
 * Display Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.User.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link     http://cms.quickapps.es
 */
class DisplayController extends UserAppController {
    public $name = 'Display';
    public $uses = array('Field.Field');

    public function admin_index($viewMode = false) {
        if (!$viewMode && !isset($this->data['User']['viewModes'])) {
            $this->redirect("/admin/user/display/index/default");
        }

        if (isset($this->data['User']['viewModes'])) { # set available view modes
            $this->Field->setViewModes($this->data['User']['viewModes'], array('Field.belongsTo' => 'User'));
            $this->redirect($this->referer());
        }

        $fields = $this->Field->find('all',  array('conditions' => array('Field.belongsTo' => 'User')));
        $fields = @Set::sort((array)$fields, '{n}.Field.settings.display.' . $viewMode . '.ordering', 'asc');
        $data['User']['viewModes'] = isset($fields[0]['Field']['settings']['display']) ? array_keys($fields[0]['Field']['settings']['display']) : array();
        $this->data = $data;

        $this->set('result', $fields);
        $this->set('viewMode', $viewMode);
        $this->setCrumb(
            '/admin/user',
            array(__t('Manage Display'))
        );
        $this->title(__t('User Display Settings'));
    }

    public function admin_field_formatter($id, $viewMode = 'default') {
        $field = $this->Field->findById($id) or $this->redirect($this->referer());

        if (isset($this->data['Field'])) {
            if ($this->Field->save($this->data)) {
                $this->flashMsg(__t('Field has been saved.'), 'success');
                $this->redirect($this->referer());
            } else {
                $this->flashMsg(__t('Field could not be saved. Please, try again.'), 'error');
            }
        } else {
            $field['Field']['viewMode'] = $viewMode;
            $this->data = $field;
        }

        $this->setCrumb(
            '/admin/user/',
            array(__t('User Display Settings')),
            array(__t('Field display settings'))
        );
        $this->title(__t('Field Display Settings'));
    }
}