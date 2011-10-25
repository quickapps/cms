<?php
/**
 * Variable Model
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.System.Model
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
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
        ) { # saving data array of type: array('var_name' => 'value')
            $rows = array();

            foreach ($data['Variable'] as $name => $value) {
                $rows['Variable'][] = array(
                    'name' => $name,
                    'value' => $value
                );
            }

            return $this->saveAll($rows['Variable'], array('validate' => $validate));
        } else {
            return parent::save($data, $validate, $fieldList);
        }
    }

    public function afterSave() {
        Cache::delete('Variable');
        $this->writeCache();

        return true;
    }

    public function writeCache() {
        $variables = $this->find('all', array('fields' => array('name', 'value')));

        foreach ($variables as $v) {
            Configure::write('Variable.' . $v['Variable']['name'] , $v['Variable']['value']);
        }

        Cache::write('Variable', Configure::read('Variable'));
    }
}