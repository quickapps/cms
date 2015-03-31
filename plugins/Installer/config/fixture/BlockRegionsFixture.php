<?php
class BlockRegionsFixture
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
    'block_regions_block_id' =>
    [
      'type' => 'unique',
      'columns' =>
      [
        0 => 'block_id',
        1 => 'theme',
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
    'block_id' =>
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
    'theme' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'region' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => true,
    'default' => '',
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'ordering' =>
    [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => '0',
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    ];

    public $records = [
    0 =>
    [
    'id' => 1,
    'block_id' => 2,
    'theme' => 'BackendTheme',
    'region' => '',
    'ordering' => 0,
    ],
    1 =>
    [
    'id' => 2,
    'block_id' => 2,
    'theme' => 'FrontendTheme',
    'region' => 'main-menu',
    'ordering' => 0,
    ],
    2 =>
    [
    'id' => 3,
    'block_id' => 1,
    'theme' => 'BackendTheme',
    'region' => 'main-menu',
    'ordering' => 0,
    ],
    3 =>
    [
    'id' => 4,
    'block_id' => 1,
    'theme' => 'FrontendTheme',
    'region' => '',
    'ordering' => 0,
    ],
    4 =>
    [
    'id' => 5,
    'block_id' => 3,
    'theme' => 'BackendTheme',
    'region' => 'dashboard-main',
    'ordering' => 0,
    ],
    5 =>
    [
    'id' => 6,
    'block_id' => 3,
    'theme' => 'FrontendTheme',
    'region' => '',
    'ordering' => 0,
    ],
    6 =>
    [
    'id' => 7,
    'block_id' => 4,
    'theme' => 'BackendTheme',
    'region' => 'dashboard-sidebar',
    'ordering' => 0,
    ],
    7 =>
    [
    'id' => 8,
    'block_id' => 4,
    'theme' => 'FrontendTheme',
    'region' => '',
    'ordering' => 0,
    ],
    8 =>
    [
    'id' => 9,
    'block_id' => 7,
    'theme' => 'BackendTheme',
    'region' => '',
    'ordering' => 0,
    ],
    9 =>
    [
    'id' => 10,
    'block_id' => 7,
    'theme' => 'FrontendTheme',
    'region' => 'sub-menu',
    'ordering' => 0,
    ],
    10 =>
    [
    'id' => 11,
    'block_id' => 5,
    'theme' => 'BackendTheme',
    'region' => '',
    'ordering' => 0,
    ],
    11 =>
    [
    'id' => 12,
    'block_id' => 5,
    'theme' => 'FrontendTheme',
    'region' => 'sub-menu',
    'ordering' => 0,
    ],
    12 =>
    [
    'id' => 13,
    'block_id' => 6,
    'theme' => 'BackendTheme',
    'region' => '',
    'ordering' => 0,
    ],
    13 =>
    [
    'id' => 14,
    'block_id' => 6,
    'theme' => 'FrontendTheme',
    'region' => 'right-sidebar',
    'ordering' => 0,
    ],
    ];
}
