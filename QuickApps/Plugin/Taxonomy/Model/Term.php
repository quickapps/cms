<?php
/**
 * Term Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Taxonomy.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class Term extends TaxonomyAppModel {
	public $useTable = 'terms';
	public $actsAs = array('Tree', 'Sluggable' => array('label' => 'name'));
	public $validate = array(
		'name' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'Term name can not be empty.')
	);
}