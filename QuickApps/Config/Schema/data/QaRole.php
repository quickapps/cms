<?php
class QaRole {
	public $table = 'roles';
	public $records = array(
		array(
			'id' => '1',
			'name' => 'administrator',
			'ordering' => '1'
		),
		array(
			'id' => '2',
			'name' => 'authenticated user',
			'ordering' => '2'
		),
		array(
			'id' => '3',
			'name' => 'anonymous user',
			'ordering' => '3'
		),
	);

}
