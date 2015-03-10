<?php
class CommentsFixture
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
  'entity_id' => 
  [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'user_id' => 
  [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'table_alias' => 
  [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'subject' => 
  [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'body' => 
  [
    'type' => 'text',
    'length' => NULL,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ],
  'author_name' => 
  [
    'type' => 'string',
    'length' => 100,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'author_email' => 
  [
    'type' => 'string',
    'length' => 100,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'author_web' => 
  [
    'type' => 'string',
    'length' => 200,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'author_ip' => 
  [
    'type' => 'string',
    'length' => 60,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'parent_id' => 
  [
    'type' => 'integer',
    'length' => 4,
    'unsigned' => false,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'rght' => 
  [
    'type' => 'integer',
    'length' => 4,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'lft' => 
  [
    'type' => 'integer',
    'length' => 4,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'status' => 
  [
    'type' => 'string',
    'length' => 20,
    'null' => false,
    'default' => NULL,
    'comment' => 'pending, approved, spam, trash',
    'precision' => NULL,
    'fixed' => NULL,
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

    public $records = [
  0 => 
  [
    'id' => 1,
    'entity_id' => '1',
    'user_id' => NULL,
    'table_alias' => 'nodes',
    'subject' => 'This is an unstable repository',
    'body' => 'This is an unstable repository and should be treated as an alpha.',
    'author_name' => NULL,
    'author_email' => NULL,
    'author_web' => NULL,
    'author_ip' => '192.168.1.1',
    'parent_id' => NULL,
    'rght' => 2,
    'lft' => 2,
    'status' => 'approved',
    'created' => '2014-08-03 05:14:42',
  ],
  1 => 
  [
    'id' => 4,
    'entity_id' => '1',
    'user_id' => 1,
    'table_alias' => 'nodes',
    'subject' => 'asd ad asd',
    'body' => 'Lorem Ipsum',
    'author_name' => '',
    'author_email' => '',
    'author_web' => '',
    'author_ip' => '192.168.1.1',
    'parent_id' => 1,
    'rght' => 1,
    'lft' => 0,
    'status' => 'approved',
    'created' => '2014-08-03 08:01:29',
  ],
];
}
