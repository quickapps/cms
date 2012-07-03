<?php
/**
 * Field Data Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Field.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class FieldData extends FieldAppModel {
	public $name = 'FieldData';
	public $useTable = 'field_data';

	public $belongsTo = array(
		'Field' => array(
			'className' => 'Field.Field',
			'dependent' => false
		)
	);
}