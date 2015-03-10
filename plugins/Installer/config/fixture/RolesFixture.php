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
    'default' => null,
    'comment' => '',
    'autoIncrement' => true,
    'precision' => null,
    ],
    'slug' =>
    [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'name' =>
    [
    'type' => 'string',
    'length' => 128,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
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
