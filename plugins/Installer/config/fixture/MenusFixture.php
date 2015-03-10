<?php
class MenusFixture
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
    'title' =>
    [
    'type' => 'string',
    'length' => 255,
    'null' => false,
    'default' => null,
    'comment' => 'Menu title, displayed at top of block.',
    'precision' => null,
    'fixed' => null,
    ],
    'description' =>
    [
    'type' => 'text',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => 'Menu description.',
    'precision' => null,
    ],
    'handler' =>
    [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => null,
    'comment' => 'Name of the plugin that created this menu.',
    'precision' => null,
    'fixed' => null,
    ],
    'settings' =>
    [
    'type' => 'text',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    ];

    public $records = [
    0 =>
    [
    'id' => 1,
    'slug' => 'management',
    'title' => 'Management',
    'description' => 'The Management menu contains links for administrative tasks.',
    'handler' => 'System',
    'settings' => null,
    ],
    1 =>
    [
    'id' => 2,
    'slug' => 'site-main-menu',
    'title' => 'Site Main Menu',
    'description' => 'The Site Main Menu is used on many sites to show the major sections of the site, often in a top navigation bar.',
    'handler' => 'System',
    'settings' => null,
    ],
    ];
}
