<?php
class RolesFixture
{

    public $fields = [
  '_constraints' => 
  [
    'primary' => 
    [
      'type' => 'primary',
      'columns' => 
      [
        0 => 'id',
      ],
      'length' => 
      [
      ],
    ],
    'roles_name' => 
    [
      'type' => 'unique',
      'columns' => 
      [
        0 => 'name',
      ],
      'length' => 
      [
      ],
    ],
  ],
  'id' => 
  [
    'type' => 'integer',
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'autoIncrement' => true,
    'precision' => NULL,
  ],
  'slug' => 
  [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'name' => 
  [
    'type' => 'string',
    'length' => 128,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
];

    public $records = [
  0 => 
  [
    'id' => 1,
    'slug' => 'administrator',
    'name' => 'Administrator',
  ],
  1 => 
  [
    'id' => 2,
    'slug' => 'authenticated ',
    'name' => 'Authenticated User',
  ],
  2 => 
  [
    'id' => 3,
    'slug' => 'anonymous',
    'name' => 'Anonymous User',
  ],
];
}
