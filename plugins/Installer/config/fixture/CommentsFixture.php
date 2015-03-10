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
    'default' => null,
    'comment' => '',
    'autoIncrement' => true,
    'precision' => null,
    ],
    'entity_id' =>
    [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'user_id' =>
    [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'table_alias' =>
    [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'subject' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'body' =>
    [
    'type' => 'text',
    'length' => null,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    'author_name' =>
    [
    'type' => 'string',
    'length' => 100,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'author_email' =>
    [
    'type' => 'string',
    'length' => 100,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'author_web' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'author_ip' =>
    [
    'type' => 'string',
    'length' => 60,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'parent_id' =>
    [
    'type' => 'integer',
    'length' => 4,
    'unsigned' => false,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'rght' =>
    [
    'type' => 'integer',
    'length' => 4,
    'unsigned' => false,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'lft' =>
    [
    'type' => 'integer',
    'length' => 4,
    'unsigned' => false,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'status' =>
    [
    'type' => 'string',
    'length' => 20,
    'null' => false,
    'default' => null,
    'comment' => 'pending, approved, spam, trash',
    'precision' => null,
    'fixed' => null,
    ],
    'created' =>
    [
    'type' => 'datetime',
    'length' => null,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    ];

    public $records = [
    0 =>
    [
    'id' => 1,
    'entity_id' => '1',
    'user_id' => null,
    'table_alias' => 'nodes',
    'subject' => 'This is an unstable repository',
    'body' => 'This is an unstable repository and should be treated as an alpha.',
    'author_name' => null,
    'author_email' => null,
    'author_web' => null,
    'author_ip' => '192.168.1.1',
    'parent_id' => null,
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
