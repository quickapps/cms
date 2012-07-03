<?php
/**
 * Module Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class Module extends SystemAppModel {
	public $name = 'Module';
	public $useTable = "modules";
	public $primaryKey = 'name';
	public $actsAs = array('Serialized' => array('settings'));

	public function beforeValidate($options = array()) {
		// force CamelCase module names
		if (isset($this->data['Module']['name'])) {
			$this->data['Module']['name'] = Inflector::camelize($this->data['Module']['name']);
		}

		return true;
	}
}