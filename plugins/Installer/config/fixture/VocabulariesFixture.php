<?php
class VocabulariesFixture
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
    'length' => 10,
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
    'length' => 255,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'slug' => 
  [
    'type' => 'string',
    'length' => 255,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'description' => 
  [
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ],
  'ordering' => 
  [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => true,
    'default' => '0',
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'locked' => 
  [
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => '0',
    'comment' => 'if set to 1 users can not delete this vocabulary',
    'precision' => NULL,
  ],
  'modified' => 
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

    public $records = [
];
}
