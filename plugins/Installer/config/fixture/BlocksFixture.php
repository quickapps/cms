<?php
class BlocksFixture
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
    'blocks_delta' => 
    [
      'type' => 'unique',
      'columns' => 
      [
        0 => 'delta',
        1 => 'handler',
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
    'comment' => 'Primary Key - Unique block ID.',
    'autoIncrement' => true,
    'precision' => NULL,
  ],
  'copy_id' => 
  [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => true,
    'default' => NULL,
    'comment' => 'id of the block this block is a copy of',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'delta' => 
  [
    'type' => 'string',
    'length' => 30,
    'null' => false,
    'default' => NULL,
    'comment' => 'unique ID within a handler',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'handler' => 
  [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => 'Block',
    'comment' => 'Name of the plugin that created this block. Used to generate event name, e.g. "Menu" triggers "Block.Menu.display" when rendering the block',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'title' => 
  [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'description' => 
  [
    'type' => 'string',
    'length' => 200,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'body' => 
  [
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ],
  'visibility' => 
  [
    'type' => 'string',
    'length' => 8,
    'null' => false,
    'default' => 'except',
    'comment' => 'indicate how to show blocks on pages. (except = show on all pages except listed pages; only = show only on listed pages; php = use custom PHP code to determine visibility)',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'pages' => 
  [
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => 'Contents of the "Pages" block contains either a list of paths on which to include/exclude the block or PHP code, depending on "visibility" setting.',
    'precision' => NULL,
  ],
  'locale' => 
  [
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ],
  'settings' => 
  [
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => 'additional information used by this block, used by blocks handlers <> `Block`',
    'precision' => NULL,
  ],
  'status' => 
  [
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => '0',
    'comment' => '',
    'precision' => NULL,
  ],
];

    public $records = [
  0 => 
  [
    'id' => 1,
    'copy_id' => NULL,
    'delta' => '1',
    'handler' => 'System',
    'title' => 'Management [menu:1]',
    'description' => 'Associated block for "Management" menu.',
    'body' => NULL,
    'visibility' => 'except',
    'pages' => NULL,
    'locale' => NULL,
    'settings' => NULL,
    'status' => true,
  ],
  1 => 
  [
    'id' => 2,
    'copy_id' => NULL,
    'delta' => '2',
    'handler' => 'System',
    'title' => 'Site Main Menu [menu:2]',
    'description' => 'Associated block for "Site Main Menu" menu.',
    'body' => NULL,
    'visibility' => 'except',
    'pages' => NULL,
    'locale' => NULL,
    'settings' => NULL,
    'status' => true,
  ],
  2 => 
  [
    'id' => 3,
    'copy_id' => NULL,
    'delta' => 'dashboard_recent_content',
    'handler' => 'Node',
    'title' => 'Recent Content',
    'description' => 'Shows a list of latest created contents.',
    'body' => NULL,
    'visibility' => 'except',
    'pages' => NULL,
    'locale' => NULL,
    'settings' => NULL,
    'status' => true,
  ],
  3 => 
  [
    'id' => 4,
    'copy_id' => NULL,
    'delta' => 'dashboard_search',
    'handler' => 'Node',
    'title' => 'Search',
    'description' => 'Quick Search Form',
    'body' => NULL,
    'visibility' => 'except',
    'pages' => NULL,
    'locale' => NULL,
    'settings' => NULL,
    'status' => true,
  ],
  4 => 
  [
    'id' => 5,
    'copy_id' => NULL,
    'delta' => 'language_switcher',
    'handler' => 'Locale',
    'title' => 'Change Language',
    'description' => 'Language switcher block',
    'body' => NULL,
    'visibility' => 'except',
    'pages' => NULL,
    'locale' => NULL,
    'settings' => 'a:2:{s:4:"type";s:4:"html";s:5:"flags";b:1;}',
    'status' => true,
  ],
  5 => 
  [
    'id' => 6,
    'copy_id' => NULL,
    'delta' => 'categories',
    'handler' => 'Taxonomy',
    'title' => 'Categories',
    'description' => 'List of terms block',
    'body' => NULL,
    'visibility' => 'except',
    'pages' => NULL,
    'locale' => NULL,
    'settings' => NULL,
    'status' => false,
  ],
];
}
