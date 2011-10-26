<?php
/**
 * Module Model
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.System.Model
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class Module extends SystemAppModel {
    public $name = 'Module';
    public $useTable = "modules";
    public $primaryKey = 'name';
    public $actsAs = array('Serialized' => array('settings'));

    public function beforeValidate() {
        # force CamelCase module names
        if (isset($this->data['Module']['name'])) {
            $this->data['Module']['name'] = Inflector::camelize($this->data['Module']['name']);
        }

        # merge settings (array treatment):
        if (isset($this->data['Module']['name']) && isset($this->data['Module']['settings'])) {
            $this->validate = false;
            $settings = $this->field('settings', array('Module.name' => $this->data['Module']['name']));
            $this->data['Module']['settings'] = Set::merge($settings, $this->data['Module']['settings']);
            $this->data['Module']['settings'] = Set::filter($this->data['Module']['settings']);
        } elseif (!isset($this->data['Module']['name'])) { # new module
            $this->data['Module']['settings'] = array();
        }
        return true;
    }
}