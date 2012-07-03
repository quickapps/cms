<?php
/**
 * Variable Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class Variable extends SystemAppModel {
	public $name = 'Variable';
	public $useTable = "variables";
	public $primaryKey = 'name';
	public $actsAs = array('Serialized' => array('value'));

	public function save($data = null, $validate = true, $fieldList = array()) {
		if (!isset($data['Variable']['name']) &&
			!isset($data['Variable']['value']) &&
			!empty($data['Variable'])
		) {
			// saving array of data in the format: array('var_name' => 'value')
			$rows = array();

			foreach ($data['Variable'] as $name => $value) {
				if ($name == 'url_language_prefix') {
					continue;
				}

				$rows['Variable'][] = array(
					'name' => $name,
					'value' => $value
				);
			}

			return $this->saveAll($rows['Variable'], array('validate' => $validate));
		} else {
			if (
				(isset($data['Variable']['name']) && $data['Variable']['name'] == 'url_language_prefix') ||
				(isset($data['name']) && $data['name'] == 'url_language_prefix')
			) {
				return true;
			}

			return parent::save($data, $validate, $fieldList);
		}
	}

	public function afterSave($created) {
		Cache::delete('Variable');
		$this->writeCache();

		return true;
	}

	public function writeCache() {
		$variables = $this->find('all', array('fields' => array('name', 'value')));

		foreach ($variables as $v) {
			if ($v['Variable']['name'] == 'url_language_prefix') {
				continue;
			}

			Configure::write('Variable.' . $v['Variable']['name'] , $v['Variable']['value']);
		}

		Cache::write('Variable', Configure::read('Variable'));
	}
}