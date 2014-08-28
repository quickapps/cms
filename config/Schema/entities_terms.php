<?php

class entities_terms {

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
  ),
  'id' => 
  array (
    'type' => 'biginteger',
    'length' => 20,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'autoIncrement' => true,
    'precision' => NULL,
  ),
  'entity_id' => 
  array (
    'type' => 'integer',
    'length' => 20,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'term_id' => 
  array (
    'type' => 'integer',
    'length' => 20,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'field_instance_id' => 
  array (
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'table_alias' => 
  array (
    'type' => 'string',
    'length' => 30,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
);

	public $records = array (
);

}

