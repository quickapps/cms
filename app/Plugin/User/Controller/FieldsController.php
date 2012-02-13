<?php
/**
 * Fields Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.User.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link     http://cms.quickapps.es
 */
class FieldsController extends UserAppController {
    public $name = 'Fields';
    public $uses = array('Field.Field', 'User.User');

    public function admin_index() {
        if (isset($this->data['Field'])) {
            if ($field_id = $this->User->attachFieldInstance($this->data)) {
                $this->redirect("/admin/user/fields/field_settings/{$field_id}");
            }

            $this->flashMsg(__t('Field could not be created. Please, try again.'), 'error');
        }

        $fields = $this->Field->find('all', array('conditions' => array('Field.belongsTo' => 'User')));

        $this->set('results', $fields);
        $this->set('field_modules', $this->hook('field_info', $this, array('collectReturn' => false)));
        $this->setCrumb(
            '/admin/user/',
            array(__t('Manage Fields'))
        );
        $this->title(__t('Manage User Fields'));
    }

    public function admin_field_settings($id) {
        if (isset($this->data['Field'])) {
            if ($this->Field->save($this->data)) {
                $this->redirect($this->referer());
            }
        }

        $this->data = $this->Field->findById($id) or  $this->redirect('/admin/node/types');

        $this->setCrumb(
            '/admin/user',
            array(__t('Fields'), '/admin/user/fields'),
            array(__t('Field settings'))
        );
        $this->title(__t('Field Settings'));
        $this->set('result', $this->data);
    }
}