<?php
/**
 * BlockCustom Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Block.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class BlockCustom extends BlockAppModel {
	public $name = 'BlockCustom';
	public $useTable = "block_custom";
	public $primaryKey = "block_id";
	public $validate = array(
		'description' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'Invalid description.'),
		'body' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'Invalid block body.'),
	);
}