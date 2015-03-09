<?php
class UsersFixture
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
    'users_username' => 
    [
      'type' => 'unique',
      'columns' => 
      [
        0 => 'username',
        1 => 'email',
      ],
      'length' => 
      [
      ],
    ],
  ],
  'id' => 
  [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'autoIncrement' => true,
    'precision' => NULL,
  ],
  'name' => 
  [
    'type' => 'string',
    'length' => 150,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'username' => 
  [
    'type' => 'string',
    'length' => 80,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'password' => 
  [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'email' => 
  [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'web' => 
  [
    'type' => 'string',
    'length' => 200,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'locale' => 
  [
    'type' => 'string',
    'length' => 5,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'public_profile' => 
  [
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ],
  'public_email' => 
  [
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ],
  'token' => 
  [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => NULL,
    'comment' => 'random unique code, used for pass recovery',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'status' => 
  [
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => '1',
    'comment' => '',
    'precision' => NULL,
  ],
  'last_login' => 
  [
    'type' => 'datetime',
    'length' => NULL,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ],
  'created' => 
  [
    'type' => 'datetime',
    'length' => NULL,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ],
];

    public $records = [];
}
