<?php
class NodesFixture
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
    'node_type_id' =>
    [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'node_type_slug' =>
    [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'translation_for' =>
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
    'slug' =>
    [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'title' =>
    [
    'type' => 'string',
    'length' => 250,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'description' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'promote' =>
    [
    'type' => 'boolean',
    'length' => null,
    'null' => false,
    'default' => '0',
    'comment' => 'Show in front page?',
    'precision' => null,
    ],
    'sticky' =>
    [
    'type' => 'boolean',
    'length' => null,
    'null' => false,
    'default' => '0',
    'comment' => 'Show at top of lists',
    'precision' => null,
    ],
    'comment_status' =>
    [
    'type' => 'integer',
    'length' => 2,
    'unsigned' => false,
    'null' => false,
    'default' => '0',
    'comment' => '0=closed, 1=open, 2=readonly',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'language' =>
    [
    'type' => 'string',
    'fixed' => true,
    'length' => 10,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    'status' =>
    [
    'type' => 'boolean',
    'length' => null,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
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
    'modified' =>
    [
    'type' => 'datetime',
    'length' => null,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    'created_by' =>
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
    'modified_by' =>
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
    ];

    public $records = [
    0 =>
    [
    'id' => 1,
    'node_type_id' => 1,
    'node_type_slug' => 'article',
    'translation_for' => null,
    'slug' => 'my-first-article',
    'title' => 'My First Article!',
    'description' => 'Custom meta description',
    'promote' => true,
    'sticky' => false,
    'comment_status' => 1,
    'language' => '',
    'status' => true,
    'created' => '2014-06-12 07:44:01',
    'modified' => '2014-08-10 10:26:27',
    'created_by' => 1,
    'modified_by' => 0,
    ],
    1 =>
    [
    'id' => 2,
    'node_type_id' => 1,
    'node_type_slug' => 'article',
    'translation_for' => null,
    'slug' => 'curabitur-quis-ultricies-nisl',
    'title' => 'Curabitur quis ultricies nisl',
    'description' => 'Donec eget rutrum nunc. Vestibulum sit amet faucibus elit.',
    'promote' => true,
    'sticky' => true,
    'comment_status' => 0,
    'language' => '',
    'status' => true,
    'created' => '2014-08-05 22:19:44',
    'modified' => '2014-08-05 22:19:44',
    'created_by' => 1,
    'modified_by' => 0,
    ],
    ];
}
