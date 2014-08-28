<?php

class roles {

	public $fields = array (
  '_constraints' => 
  array (
    'primary' => 
    array (
      'type' => 'primary',
      'columns' => 
      array (
        0 => 'id',
      ),
      'length' => 
      array (
      ),
    ),
    'name' => 
    array (
      'type' => 'unique',
      'columns' => 
      array (
        0 => 'name',
      ),
      'length' => 
      array (
      ),
    ),
  ),
  'id' => 
  array (
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'autoIncrement' => true,
    'precision' => NULL,
  ),
  'slug' => 
  array (
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'name' => 
  array (
    'type' => 'string',
    'length' => 128,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
);

	public $records = array (
  0 => 
  array (
    'id' => 1,
    'slug' => 'administrator',
    'name' => 'Administrator',
  ),
  1 => 
  array (
    'id' => 2,
    'slug' => 'authenticated ',
    'name' => 'Authenticated User',
  ),
  2 => 
  array (
    'id' => 3,
    'slug' => 'anonymous',
    'name' => 'Anonymous User',
  ),
);

}

