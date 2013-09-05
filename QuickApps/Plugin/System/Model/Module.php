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

	public function afterSave($created) {
		Cache::delete('modules');
		Cache::delete('modules_load_order');

		$this->writeCache();
	}

	public function writeCache() {
		$Modules = (array)$this->find('all', array('recursive' => -1));

		foreach ($Modules as $module) {
			if (!CakePlugin::loaded($module['Module']['name'])) {
				CakePlugin::load($module['Module']['name']);
			}

			$module['Module']['path'] = App::pluginPath($module['Module']['name']);

			if (strpos($module['Module']['name'], 'Theme') === 0) {
				$yamlFile = dirname(dirname($module['Module']['path'])) . DS . basename(dirname(dirname($module['Module']['path']))) . '.yaml';
			} else {
				$yamlFile = $module['Module']['path'] . "{$module['Module']['name']}.yaml";
			}

			$module['Module']['yaml'] = file_exists($yamlFile) ? Spyc::YAMLLoad($yamlFile) : array();
			$modules[$module['Module']['name']] = $module['Module'];
		}

		Configure::write('Modules', $modules);
		Cache::write('Modules', $modules);

		$order = $this->find('all',
			array(
				'conditions' => array('Module.status' => 1, 'Module.type' => 'module'),
				'fields' => array('Module.name', 'Module.type', 'Module.ordering'),
				'order' => array('Module.ordering' => 'ASC'),
				'recursive' => -1
			)
		);
		$load_order = Hash::extract((array)$order, '{n}.Module.name');

		Cache::write('modules_load_order', $load_order);
	}
}