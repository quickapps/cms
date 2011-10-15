<?php
/**
 * Node Type Model
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Node.Model
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class NodeType extends NodeAppModel {
    public $name = 'NodeType';
    public $useTable = "node_types";
    public $primaryKey = 'id';
    public $actsAs = array('Sluggable' => array('overwrite' => false, 'slug' => 'id', 'label' => 'name', 'separator' => '_'));
    public $validate = array(
        'name' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'Type name can not be empty'),
        'title_label' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'Title field label can not be empty')
    );

    function beforeDelete(){
        $this->tmpId = $this->id;
        return true;
    }

    function afterDelete(){
        return ClassRegistry::init('Field.Field')->deleteAll(array('Field.belongsTo' => "NodeType-{$this->tmpId}"), true, true);
    }
}