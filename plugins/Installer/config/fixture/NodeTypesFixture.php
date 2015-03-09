<?php
class NodeTypesFixture
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
    'node_types_slug' => 
    [
      'type' => 'unique',
      'columns' => 
      [
        0 => 'slug',
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
  'slug' => 
  [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'name' => 
  [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => NULL,
    'comment' => 'human-readable name',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'description' => 
  [
    'type' => 'string',
    'length' => 255,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'title_label' => 
  [
    'type' => 'string',
    'length' => 80,
    'null' => false,
    'default' => NULL,
    'comment' => 'the label displayed for the title field on the edit form.',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'defaults' => 
  [
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ],
];

    public $records = [
  0 => 
  [
    'id' => 1,
    'slug' => 'article',
    'name' => 'Article',
    'description' => 'Use articles for time-sensitive content like news, press releases or blog posts.',
    'title_label' => 'Title',
    'defaults' => 'a:7:{s:6:"status";s:1:"1";s:7:"promote";s:1:"1";s:6:"sticky";s:1:"1";s:11:"author_name";s:1:"1";s:9:"show_date";s:1:"1";s:14:"comment_status";s:1:"0";s:8:"language";s:0:"";}',
  ],
];
}
