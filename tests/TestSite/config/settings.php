<?php
$config = [
	'Datasources' => [
		'default' => [
			'className' => 'Cake\Database\Connection',
			'driver' => 'Cake\Database\Driver\Mysql',
			'persistent' => false,
			'host' => 'localhost',
			'username' => 'travis',
			'password' => '',
			'database' => 'quick_test',
			'prefix' => 'qa_',
			'encoding' => 'utf8',
			'log' => true,
		],
		'test' => [
			'className' => 'Cake\Database\Connection',
			'driver' => 'Cake\Database\Driver\Mysql',
			'persistent' => false,
			'host' => 'localhost',
			'username' => 'travis',
			'password' => '',
			'database' => 'quick_test',
			'prefix' => 'qa_',
			'encoding' => 'utf8',
			'log' => true,
		],
	],
	'Security' => [
		'salt' => '459dnv028fj20rmv034jv84hv929sadn306139fn)(·%o23',
	],
	'debug' => true,
];
