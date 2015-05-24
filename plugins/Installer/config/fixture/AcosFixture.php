<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */

class AcosFixture
{

    public $table = 'acos';

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
    '_indexes' =>
    [
    'acos_parent_id_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'parent_id',
      ],
      'length' =>
      [
      ],
    ],
    'acos_lft_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'lft',
      ],
      'length' =>
      [
      ],
    ],
    'acos_rght_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'rght',
      ],
      'length' =>
      [
      ],
    ],
    ],
    '_options' =>
    [
    'engine' => 'InnoDB',
    'collation' => 'utf8_unicode_ci',
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
    'parent_id' =>
    [
    'type' => 'integer',
    'length' => 10,
    'unsigned' => false,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'lft' =>
    [
    'type' => 'integer',
    'length' => 10,
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
    'length' => 10,
    'unsigned' => false,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'plugin' =>
    [
    'type' => 'string',
    'length' => 255,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'alias' =>
    [
    'type' => 'string',
    'length' => 255,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'alias_hash' =>
    [
    'type' => 'string',
    'length' => 32,
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
    'parent_id' => null,
    'lft' => 1,
    'rght' => 16,
    'plugin' => 'Block',
    'alias' => 'Block',
    'alias_hash' => 'e1e4c8c9ccd9fc39c391da4bcd093fb2',
    ],
    1 =>
    [
    'id' => 2,
    'parent_id' => 1,
    'lft' => 2,
    'rght' => 15,
    'plugin' => 'Block',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    2 =>
    [
    'id' => 3,
    'parent_id' => 2,
    'lft' => 3,
    'rght' => 14,
    'plugin' => 'Block',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    3 =>
    [
    'id' => 4,
    'parent_id' => 3,
    'lft' => 4,
    'rght' => 5,
    'plugin' => 'Block',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    4 =>
    [
    'id' => 5,
    'parent_id' => 3,
    'lft' => 6,
    'rght' => 7,
    'plugin' => 'Block',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    5 =>
    [
    'id' => 6,
    'parent_id' => 3,
    'lft' => 8,
    'rght' => 9,
    'plugin' => 'Block',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    6 =>
    [
    'id' => 7,
    'parent_id' => 3,
    'lft' => 10,
    'rght' => 11,
    'plugin' => 'Block',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    7 =>
    [
    'id' => 8,
    'parent_id' => 3,
    'lft' => 12,
    'rght' => 13,
    'plugin' => 'Block',
    'alias' => 'duplicate',
    'alias_hash' => '24f1b0a79473250c195c7fb84e393392',
    ],
    8 =>
    [
    'id' => 9,
    'parent_id' => null,
    'lft' => 17,
    'rght' => 88,
    'plugin' => 'Content',
    'alias' => 'Content',
    'alias_hash' => 'f15c1cae7882448b3fb0404682e17e61',
    ],
    9 =>
    [
    'id' => 10,
    'parent_id' => 9,
    'lft' => 18,
    'rght' => 29,
    'plugin' => 'Content',
    'alias' => 'Serve',
    'alias_hash' => 'bc9a5b9e9259199a79f67ded0b508dfc',
    ],
    10 =>
    [
    'id' => 11,
    'parent_id' => 10,
    'lft' => 19,
    'rght' => 20,
    'plugin' => 'Content',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    11 =>
    [
    'id' => 12,
    'parent_id' => 10,
    'lft' => 21,
    'rght' => 22,
    'plugin' => 'Content',
    'alias' => 'home',
    'alias_hash' => '106a6c241b8797f52e1e77317b96a201',
    ],
    12 =>
    [
    'id' => 13,
    'parent_id' => 10,
    'lft' => 23,
    'rght' => 24,
    'plugin' => 'Content',
    'alias' => 'details',
    'alias_hash' => '27792947ed5d5da7c0d1f43327ed9dab',
    ],
    13 =>
    [
    'id' => 14,
    'parent_id' => 10,
    'lft' => 25,
    'rght' => 26,
    'plugin' => 'Content',
    'alias' => 'search',
    'alias_hash' => '06a943c59f33a34bb5924aaf72cd2995',
    ],
    14 =>
    [
    'id' => 15,
    'parent_id' => 10,
    'lft' => 27,
    'rght' => 28,
    'plugin' => 'Content',
    'alias' => 'rss',
    'alias_hash' => '8bb856027f758e85ddf2085c98ae2908',
    ],
    15 =>
    [
    'id' => 16,
    'parent_id' => 9,
    'lft' => 30,
    'rght' => 87,
    'plugin' => 'Content',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    16 =>
    [
    'id' => 17,
    'parent_id' => 16,
    'lft' => 31,
    'rght' => 42,
    'plugin' => 'Content',
    'alias' => 'Comments',
    'alias_hash' => '8413c683b4b27cc3f4dbd4c90329d8ba',
    ],
    17 =>
    [
    'id' => 18,
    'parent_id' => 17,
    'lft' => 32,
    'rght' => 33,
    'plugin' => 'Content',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    18 =>
    [
    'id' => 19,
    'parent_id' => 17,
    'lft' => 34,
    'rght' => 35,
    'plugin' => 'Content',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    19 =>
    [
    'id' => 20,
    'parent_id' => 17,
    'lft' => 36,
    'rght' => 37,
    'plugin' => 'Content',
    'alias' => 'status',
    'alias_hash' => '9acb44549b41563697bb490144ec6258',
    ],
    20 =>
    [
    'id' => 21,
    'parent_id' => 17,
    'lft' => 38,
    'rght' => 39,
    'plugin' => 'Content',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    21 =>
    [
    'id' => 22,
    'parent_id' => 17,
    'lft' => 40,
    'rght' => 41,
    'plugin' => 'Content',
    'alias' => 'emptyTrash',
    'alias_hash' => 'b13b982da42afc395fd5f9ad46381e23',
    ],
    22 =>
    [
    'id' => 23,
    'parent_id' => 16,
    'lft' => 43,
    'rght' => 60,
    'plugin' => 'Content',
    'alias' => 'Fields',
    'alias_hash' => 'a4ca5edd20d0b5d502ebece575681f58',
    ],
    23 =>
    [
    'id' => 24,
    'parent_id' => 23,
    'lft' => 44,
    'rght' => 45,
    'plugin' => 'Content',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    24 =>
    [
    'id' => 25,
    'parent_id' => 23,
    'lft' => 46,
    'rght' => 47,
    'plugin' => 'Content',
    'alias' => 'configure',
    'alias_hash' => 'e2d5a00791bce9a01f99bc6fd613a39d',
    ],
    25 =>
    [
    'id' => 26,
    'parent_id' => 23,
    'lft' => 48,
    'rght' => 49,
    'plugin' => 'Content',
    'alias' => 'attach',
    'alias_hash' => '915e375d95d78bf040a2e054caadfb56',
    ],
    26 =>
    [
    'id' => 27,
    'parent_id' => 23,
    'lft' => 50,
    'rght' => 51,
    'plugin' => 'Content',
    'alias' => 'detach',
    'alias_hash' => 'b6bc015ea9587c510c9017988e94e60d',
    ],
    27 =>
    [
    'id' => 28,
    'parent_id' => 23,
    'lft' => 52,
    'rght' => 53,
    'plugin' => 'Content',
    'alias' => 'viewModeList',
    'alias_hash' => 'f6730c40f9e93768852502275e0c9ed5',
    ],
    28 =>
    [
    'id' => 29,
    'parent_id' => 23,
    'lft' => 54,
    'rght' => 55,
    'plugin' => 'Content',
    'alias' => 'viewModeEdit',
    'alias_hash' => 'ecb551e7896f4007ed8df082a8184878',
    ],
    29 =>
    [
    'id' => 30,
    'parent_id' => 23,
    'lft' => 56,
    'rght' => 57,
    'plugin' => 'Content',
    'alias' => 'viewModeMove',
    'alias_hash' => '5a2933f9feebe723793d21c183be08c6',
    ],
    30 =>
    [
    'id' => 31,
    'parent_id' => 23,
    'lft' => 58,
    'rght' => 59,
    'plugin' => 'Content',
    'alias' => 'move',
    'alias_hash' => '3734a903022249b3010be1897042568e',
    ],
    31 =>
    [
    'id' => 32,
    'parent_id' => 16,
    'lft' => 61,
    'rght' => 76,
    'plugin' => 'Content',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    32 =>
    [
    'id' => 33,
    'parent_id' => 32,
    'lft' => 62,
    'rght' => 63,
    'plugin' => 'Content',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    33 =>
    [
    'id' => 34,
    'parent_id' => 32,
    'lft' => 64,
    'rght' => 65,
    'plugin' => 'Content',
    'alias' => 'create',
    'alias_hash' => '76ea0bebb3c22822b4f0dd9c9fd021c5',
    ],
    34 =>
    [
    'id' => 35,
    'parent_id' => 32,
    'lft' => 66,
    'rght' => 67,
    'plugin' => 'Content',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    35 =>
    [
    'id' => 36,
    'parent_id' => 32,
    'lft' => 68,
    'rght' => 69,
    'plugin' => 'Content',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    36 =>
    [
    'id' => 37,
    'parent_id' => 32,
    'lft' => 70,
    'rght' => 71,
    'plugin' => 'Content',
    'alias' => 'translate',
    'alias_hash' => 'fc46e26a907870744758b76166150f62',
    ],
    37 =>
    [
    'id' => 38,
    'parent_id' => 32,
    'lft' => 72,
    'rght' => 73,
    'plugin' => 'Content',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    38 =>
    [
    'id' => 39,
    'parent_id' => 32,
    'lft' => 74,
    'rght' => 75,
    'plugin' => 'Content',
    'alias' => 'deleteRevision',
    'alias_hash' => '0049d291ee36657bd271c65979383af3',
    ],
    39 =>
    [
    'id' => 40,
    'parent_id' => 16,
    'lft' => 77,
    'rght' => 86,
    'plugin' => 'Content',
    'alias' => 'Types',
    'alias_hash' => 'f2d346b1bb7c1c85ab6f7f21e3666b9f',
    ],
    40 =>
    [
    'id' => 41,
    'parent_id' => 40,
    'lft' => 78,
    'rght' => 79,
    'plugin' => 'Content',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    41 =>
    [
    'id' => 42,
    'parent_id' => 40,
    'lft' => 80,
    'rght' => 81,
    'plugin' => 'Content',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    42 =>
    [
    'id' => 43,
    'parent_id' => 40,
    'lft' => 82,
    'rght' => 83,
    'plugin' => 'Content',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    43 =>
    [
    'id' => 44,
    'parent_id' => 40,
    'lft' => 84,
    'rght' => 85,
    'plugin' => 'Content',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    44 =>
    [
    'id' => 45,
    'parent_id' => null,
    'lft' => 89,
    'rght' => 100,
    'plugin' => 'Field',
    'alias' => 'Field',
    'alias_hash' => '6f16a5f8ff5d75ab84c018adacdfcbb7',
    ],
    45 =>
    [
    'id' => 46,
    'parent_id' => 45,
    'lft' => 90,
    'rght' => 95,
    'plugin' => 'Field',
    'alias' => 'FileHandler',
    'alias_hash' => 'd3d5308974962037be1ce87e7b7bbfe2',
    ],
    46 =>
    [
    'id' => 47,
    'parent_id' => 46,
    'lft' => 91,
    'rght' => 92,
    'plugin' => 'Field',
    'alias' => 'upload',
    'alias_hash' => '76ee3de97a1b8b903319b7c013d8c877',
    ],
    47 =>
    [
    'id' => 48,
    'parent_id' => 46,
    'lft' => 93,
    'rght' => 94,
    'plugin' => 'Field',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    48 =>
    [
    'id' => 49,
    'parent_id' => 45,
    'lft' => 96,
    'rght' => 99,
    'plugin' => 'Field',
    'alias' => 'ImageHandler',
    'alias_hash' => '0f6984d93393387e52ea884c7ca0fd93',
    ],
    49 =>
    [
    'id' => 50,
    'parent_id' => 49,
    'lft' => 97,
    'rght' => 98,
    'plugin' => 'Field',
    'alias' => 'thumbnail',
    'alias_hash' => '951d4dff3c22e9fcc4a2707009f45ea8',
    ],
    50 =>
    [
    'id' => 51,
    'parent_id' => null,
    'lft' => 101,
    'rght' => 118,
    'plugin' => 'Installer',
    'alias' => 'Installer',
    'alias_hash' => 'd1be377656960ed04f1564da21d80c8d',
    ],
    51 =>
    [
    'id' => 52,
    'parent_id' => 51,
    'lft' => 102,
    'rght' => 117,
    'plugin' => 'Installer',
    'alias' => 'Startup',
    'alias_hash' => '13e685964c2548aa748f7ea263bad4e5',
    ],
    52 =>
    [
    'id' => 53,
    'parent_id' => 52,
    'lft' => 103,
    'rght' => 104,
    'plugin' => 'Installer',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    53 =>
    [
    'id' => 54,
    'parent_id' => 52,
    'lft' => 105,
    'rght' => 106,
    'plugin' => 'Installer',
    'alias' => 'language',
    'alias_hash' => '8512ae7d57b1396273f76fe6ed341a23',
    ],
    54 =>
    [
    'id' => 55,
    'parent_id' => 52,
    'lft' => 107,
    'rght' => 108,
    'plugin' => 'Installer',
    'alias' => 'requirements',
    'alias_hash' => 'b4851e92b19af0c5c82447fc0937709d',
    ],
    55 =>
    [
    'id' => 56,
    'parent_id' => 52,
    'lft' => 109,
    'rght' => 110,
    'plugin' => 'Installer',
    'alias' => 'license',
    'alias_hash' => '718779752b851ac0dc6281a8c8d77e7e',
    ],
    56 =>
    [
    'id' => 57,
    'parent_id' => 52,
    'lft' => 111,
    'rght' => 112,
    'plugin' => 'Installer',
    'alias' => 'database',
    'alias_hash' => '11e0eed8d3696c0a632f822df385ab3c',
    ],
    57 =>
    [
    'id' => 58,
    'parent_id' => 52,
    'lft' => 113,
    'rght' => 114,
    'plugin' => 'Installer',
    'alias' => 'account',
    'alias_hash' => 'e268443e43d93dab7ebef303bbe9642f',
    ],
    58 =>
    [
    'id' => 59,
    'parent_id' => 52,
    'lft' => 115,
    'rght' => 116,
    'plugin' => 'Installer',
    'alias' => 'finish',
    'alias_hash' => '3248bc7547ce97b2a197b2a06cf7283d',
    ],
    59 =>
    [
    'id' => 60,
    'parent_id' => null,
    'lft' => 119,
    'rght' => 140,
    'plugin' => 'Locale',
    'alias' => 'Locale',
    'alias_hash' => '911f0f24bdce6808f4614d6a263b143b',
    ],
    60 =>
    [
    'id' => 61,
    'parent_id' => 60,
    'lft' => 120,
    'rght' => 139,
    'plugin' => 'Locale',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    61 =>
    [
    'id' => 62,
    'parent_id' => 61,
    'lft' => 121,
    'rght' => 138,
    'plugin' => 'Locale',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    62 =>
    [
    'id' => 63,
    'parent_id' => 62,
    'lft' => 122,
    'rght' => 123,
    'plugin' => 'Locale',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    63 =>
    [
    'id' => 64,
    'parent_id' => 62,
    'lft' => 124,
    'rght' => 125,
    'plugin' => 'Locale',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    64 =>
    [
    'id' => 65,
    'parent_id' => 62,
    'lft' => 126,
    'rght' => 127,
    'plugin' => 'Locale',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    65 =>
    [
    'id' => 66,
    'parent_id' => 62,
    'lft' => 128,
    'rght' => 129,
    'plugin' => 'Locale',
    'alias' => 'setDefault',
    'alias_hash' => 'd16b26f218cfb8cde187e3b95a78813c',
    ],
    66 =>
    [
    'id' => 67,
    'parent_id' => 62,
    'lft' => 130,
    'rght' => 131,
    'plugin' => 'Locale',
    'alias' => 'move',
    'alias_hash' => '3734a903022249b3010be1897042568e',
    ],
    67 =>
    [
    'id' => 68,
    'parent_id' => 62,
    'lft' => 132,
    'rght' => 133,
    'plugin' => 'Locale',
    'alias' => 'enable',
    'alias_hash' => '208f156d4a803025c284bb595a7576b4',
    ],
    68 =>
    [
    'id' => 69,
    'parent_id' => 62,
    'lft' => 134,
    'rght' => 135,
    'plugin' => 'Locale',
    'alias' => 'disable',
    'alias_hash' => '0aaa87422396fdd678498793b6d5250e',
    ],
    69 =>
    [
    'id' => 70,
    'parent_id' => 62,
    'lft' => 136,
    'rght' => 137,
    'plugin' => 'Locale',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    70 =>
    [
    'id' => 71,
    'parent_id' => null,
    'lft' => 141,
    'rght' => 152,
    'plugin' => 'MediaManager',
    'alias' => 'MediaManager',
    'alias_hash' => 'ce0a1f03091160e6528b72e9f9ea7eff',
    ],
    71 =>
    [
    'id' => 72,
    'parent_id' => 71,
    'lft' => 142,
    'rght' => 151,
    'plugin' => 'MediaManager',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    72 =>
    [
    'id' => 73,
    'parent_id' => 72,
    'lft' => 143,
    'rght' => 150,
    'plugin' => 'MediaManager',
    'alias' => 'Explorer',
    'alias_hash' => '94fbbf67e0c8cea8cbaff55287746f3e',
    ],
    73 =>
    [
    'id' => 74,
    'parent_id' => 73,
    'lft' => 144,
    'rght' => 145,
    'plugin' => 'MediaManager',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    74 =>
    [
    'id' => 75,
    'parent_id' => 73,
    'lft' => 146,
    'rght' => 147,
    'plugin' => 'MediaManager',
    'alias' => 'connector',
    'alias_hash' => '266e0d3d29830abfe7d4ed98b47966f7',
    ],
    75 =>
    [
    'id' => 76,
    'parent_id' => 73,
    'lft' => 148,
    'rght' => 149,
    'plugin' => 'MediaManager',
    'alias' => 'pluginFile',
    'alias_hash' => 'a840980787c4260a4a710f753641a8c6',
    ],
    76 =>
    [
    'id' => 77,
    'parent_id' => null,
    'lft' => 153,
    'rght' => 176,
    'plugin' => 'Menu',
    'alias' => 'Menu',
    'alias_hash' => 'b61541208db7fa7dba42c85224405911',
    ],
    77 =>
    [
    'id' => 78,
    'parent_id' => 77,
    'lft' => 154,
    'rght' => 175,
    'plugin' => 'Menu',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    78 =>
    [
    'id' => 79,
    'parent_id' => 78,
    'lft' => 155,
    'rght' => 164,
    'plugin' => 'Menu',
    'alias' => 'Links',
    'alias_hash' => 'bd908db5ccb07777ced8023dffc802f4',
    ],
    79 =>
    [
    'id' => 80,
    'parent_id' => 79,
    'lft' => 156,
    'rght' => 157,
    'plugin' => 'Menu',
    'alias' => 'menu',
    'alias_hash' => '8d6ab84ca2af9fccd4e4048694176ebf',
    ],
    80 =>
    [
    'id' => 81,
    'parent_id' => 79,
    'lft' => 158,
    'rght' => 159,
    'plugin' => 'Menu',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    81 =>
    [
    'id' => 82,
    'parent_id' => 79,
    'lft' => 160,
    'rght' => 161,
    'plugin' => 'Menu',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    82 =>
    [
    'id' => 83,
    'parent_id' => 79,
    'lft' => 162,
    'rght' => 163,
    'plugin' => 'Menu',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    83 =>
    [
    'id' => 84,
    'parent_id' => 78,
    'lft' => 165,
    'rght' => 174,
    'plugin' => 'Menu',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    84 =>
    [
    'id' => 85,
    'parent_id' => 84,
    'lft' => 166,
    'rght' => 167,
    'plugin' => 'Menu',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    85 =>
    [
    'id' => 86,
    'parent_id' => 84,
    'lft' => 168,
    'rght' => 169,
    'plugin' => 'Menu',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    86 =>
    [
    'id' => 87,
    'parent_id' => 84,
    'lft' => 170,
    'rght' => 171,
    'plugin' => 'Menu',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    87 =>
    [
    'id' => 88,
    'parent_id' => 84,
    'lft' => 172,
    'rght' => 173,
    'plugin' => 'Menu',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    88 =>
    [
    'id' => 89,
    'parent_id' => null,
    'lft' => 177,
    'rght' => 228,
    'plugin' => 'System',
    'alias' => 'System',
    'alias_hash' => 'a45da96d0bf6575970f2d27af22be28a',
    ],
    89 =>
    [
    'id' => 90,
    'parent_id' => 89,
    'lft' => 178,
    'rght' => 227,
    'plugin' => 'System',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    90 =>
    [
    'id' => 91,
    'parent_id' => 90,
    'lft' => 179,
    'rght' => 182,
    'plugin' => 'System',
    'alias' => 'Configuration',
    'alias_hash' => '254f642527b45bc260048e30704edb39',
    ],
    91 =>
    [
    'id' => 92,
    'parent_id' => 91,
    'lft' => 180,
    'rght' => 181,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    92 =>
    [
    'id' => 93,
    'parent_id' => 90,
    'lft' => 183,
    'rght' => 186,
    'plugin' => 'System',
    'alias' => 'Dashboard',
    'alias_hash' => '2938c7f7e560ed972f8a4f68e80ff834',
    ],
    93 =>
    [
    'id' => 94,
    'parent_id' => 93,
    'lft' => 184,
    'rght' => 185,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    94 =>
    [
    'id' => 95,
    'parent_id' => 90,
    'lft' => 187,
    'rght' => 192,
    'plugin' => 'System',
    'alias' => 'Help',
    'alias_hash' => '6a26f548831e6a8c26bfbbd9f6ec61e0',
    ],
    95 =>
    [
    'id' => 96,
    'parent_id' => 95,
    'lft' => 188,
    'rght' => 189,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    96 =>
    [
    'id' => 97,
    'parent_id' => 95,
    'lft' => 190,
    'rght' => 191,
    'plugin' => 'System',
    'alias' => 'about',
    'alias_hash' => '46b3931b9959c927df4fc65fdee94b07',
    ],
    97 =>
    [
    'id' => 98,
    'parent_id' => 90,
    'lft' => 193,
    'rght' => 206,
    'plugin' => 'System',
    'alias' => 'Plugins',
    'alias_hash' => 'bb38096ab39160dc20d44f3ea6b44507',
    ],
    98 =>
    [
    'id' => 99,
    'parent_id' => 98,
    'lft' => 194,
    'rght' => 195,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    99 =>
    [
    'id' => 100,
    'parent_id' => 98,
    'lft' => 196,
    'rght' => 197,
    'plugin' => 'System',
    'alias' => 'install',
    'alias_hash' => '19ad89bc3e3c9d7ef68b89523eff1987',
    ],
    100 =>
    [
    'id' => 101,
    'parent_id' => 98,
    'lft' => 198,
    'rght' => 199,
    'plugin' => 'System',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    101 =>
    [
    'id' => 102,
    'parent_id' => 98,
    'lft' => 200,
    'rght' => 201,
    'plugin' => 'System',
    'alias' => 'enable',
    'alias_hash' => '208f156d4a803025c284bb595a7576b4',
    ],
    102 =>
    [
    'id' => 103,
    'parent_id' => 98,
    'lft' => 202,
    'rght' => 203,
    'plugin' => 'System',
    'alias' => 'disable',
    'alias_hash' => '0aaa87422396fdd678498793b6d5250e',
    ],
    103 =>
    [
    'id' => 104,
    'parent_id' => 98,
    'lft' => 204,
    'rght' => 205,
    'plugin' => 'System',
    'alias' => 'settings',
    'alias_hash' => '2e5d8aa3dfa8ef34ca5131d20f9dad51',
    ],
    104 =>
    [
    'id' => 105,
    'parent_id' => 90,
    'lft' => 207,
    'rght' => 210,
    'plugin' => 'System',
    'alias' => 'Structure',
    'alias_hash' => 'dc4c71563b9bc39a65be853457e6b7b6',
    ],
    105 =>
    [
    'id' => 106,
    'parent_id' => 105,
    'lft' => 208,
    'rght' => 209,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    106 =>
    [
    'id' => 107,
    'parent_id' => 90,
    'lft' => 211,
    'rght' => 226,
    'plugin' => 'System',
    'alias' => 'Themes',
    'alias_hash' => '83915d1254927f41241e8630890bec6e',
    ],
    107 =>
    [
    'id' => 108,
    'parent_id' => 107,
    'lft' => 212,
    'rght' => 213,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    108 =>
    [
    'id' => 109,
    'parent_id' => 107,
    'lft' => 214,
    'rght' => 215,
    'plugin' => 'System',
    'alias' => 'install',
    'alias_hash' => '19ad89bc3e3c9d7ef68b89523eff1987',
    ],
    109 =>
    [
    'id' => 110,
    'parent_id' => 107,
    'lft' => 216,
    'rght' => 217,
    'plugin' => 'System',
    'alias' => 'uninstall',
    'alias_hash' => 'fe98497efedbe156ecc4b953aea77e07',
    ],
    110 =>
    [
    'id' => 111,
    'parent_id' => 107,
    'lft' => 218,
    'rght' => 219,
    'plugin' => 'System',
    'alias' => 'activate',
    'alias_hash' => 'd4ee0fbbeb7ffd4fd7a7d477a7ecd922',
    ],
    111 =>
    [
    'id' => 112,
    'parent_id' => 107,
    'lft' => 220,
    'rght' => 221,
    'plugin' => 'System',
    'alias' => 'details',
    'alias_hash' => '27792947ed5d5da7c0d1f43327ed9dab',
    ],
    112 =>
    [
    'id' => 113,
    'parent_id' => 107,
    'lft' => 222,
    'rght' => 223,
    'plugin' => 'System',
    'alias' => 'screenshot',
    'alias_hash' => '62c92ba585f74ecdbef4c4498a438984',
    ],
    113 =>
    [
    'id' => 114,
    'parent_id' => 107,
    'lft' => 224,
    'rght' => 225,
    'plugin' => 'System',
    'alias' => 'settings',
    'alias_hash' => '2e5d8aa3dfa8ef34ca5131d20f9dad51',
    ],
    114 =>
    [
    'id' => 115,
    'parent_id' => null,
    'lft' => 229,
    'rght' => 260,
    'plugin' => 'Taxonomy',
    'alias' => 'Taxonomy',
    'alias_hash' => '30d10883c017c4fd6751c8982e20dae1',
    ],
    115 =>
    [
    'id' => 116,
    'parent_id' => 115,
    'lft' => 230,
    'rght' => 259,
    'plugin' => 'Taxonomy',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    116 =>
    [
    'id' => 117,
    'parent_id' => 116,
    'lft' => 231,
    'rght' => 234,
    'plugin' => 'Taxonomy',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    117 =>
    [
    'id' => 118,
    'parent_id' => 117,
    'lft' => 232,
    'rght' => 233,
    'plugin' => 'Taxonomy',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    118 =>
    [
    'id' => 119,
    'parent_id' => 116,
    'lft' => 235,
    'rght' => 238,
    'plugin' => 'Taxonomy',
    'alias' => 'Tagger',
    'alias_hash' => 'e34d9224f0bf63992e1e77451c6976d1',
    ],
    119 =>
    [
    'id' => 120,
    'parent_id' => 119,
    'lft' => 236,
    'rght' => 237,
    'plugin' => 'Taxonomy',
    'alias' => 'search',
    'alias_hash' => '06a943c59f33a34bb5924aaf72cd2995',
    ],
    120 =>
    [
    'id' => 121,
    'parent_id' => 116,
    'lft' => 239,
    'rght' => 248,
    'plugin' => 'Taxonomy',
    'alias' => 'Terms',
    'alias_hash' => '6f1bf85c9ebb3c7fa26251e1e335e032',
    ],
    121 =>
    [
    'id' => 122,
    'parent_id' => 121,
    'lft' => 240,
    'rght' => 241,
    'plugin' => 'Taxonomy',
    'alias' => 'vocabulary',
    'alias_hash' => '09f06963f502addfeab2a7c87f38802e',
    ],
    122 =>
    [
    'id' => 123,
    'parent_id' => 121,
    'lft' => 242,
    'rght' => 243,
    'plugin' => 'Taxonomy',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    123 =>
    [
    'id' => 124,
    'parent_id' => 121,
    'lft' => 244,
    'rght' => 245,
    'plugin' => 'Taxonomy',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    124 =>
    [
    'id' => 125,
    'parent_id' => 121,
    'lft' => 246,
    'rght' => 247,
    'plugin' => 'Taxonomy',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    125 =>
    [
    'id' => 126,
    'parent_id' => 116,
    'lft' => 249,
    'rght' => 258,
    'plugin' => 'Taxonomy',
    'alias' => 'Vocabularies',
    'alias_hash' => '81a419751eb59e7d35acab8e532d59a7',
    ],
    126 =>
    [
    'id' => 127,
    'parent_id' => 126,
    'lft' => 250,
    'rght' => 251,
    'plugin' => 'Taxonomy',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    127 =>
    [
    'id' => 128,
    'parent_id' => 126,
    'lft' => 252,
    'rght' => 253,
    'plugin' => 'Taxonomy',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    128 =>
    [
    'id' => 129,
    'parent_id' => 126,
    'lft' => 254,
    'rght' => 255,
    'plugin' => 'Taxonomy',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    129 =>
    [
    'id' => 130,
    'parent_id' => 126,
    'lft' => 256,
    'rght' => 257,
    'plugin' => 'Taxonomy',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    130 =>
    [
    'id' => 131,
    'parent_id' => null,
    'lft' => 261,
    'rght' => 350,
    'plugin' => 'User',
    'alias' => 'User',
    'alias_hash' => '8f9bfe9d1345237cb3b2b205864da075',
    ],
    131 =>
    [
    'id' => 132,
    'parent_id' => 131,
    'lft' => 262,
    'rght' => 285,
    'plugin' => 'User',
    'alias' => 'Gateway',
    'alias_hash' => '926dec9494209cb088b4962509df1a91',
    ],
    132 =>
    [
    'id' => 133,
    'parent_id' => 132,
    'lft' => 263,
    'rght' => 264,
    'plugin' => 'User',
    'alias' => 'forgot',
    'alias_hash' => '790f6b6cf6a6fbead525927d69f409fe',
    ],
    133 =>
    [
    'id' => 134,
    'parent_id' => 132,
    'lft' => 265,
    'rght' => 266,
    'plugin' => 'User',
    'alias' => 'cancelRequest',
    'alias_hash' => 'd101217dd06f14b4a695fca3b2407320',
    ],
    134 =>
    [
    'id' => 135,
    'parent_id' => 132,
    'lft' => 267,
    'rght' => 268,
    'plugin' => 'User',
    'alias' => 'cancel',
    'alias_hash' => '10aec35353f9c4096a71c38654c3d402',
    ],
    135 =>
    [
    'id' => 136,
    'parent_id' => 132,
    'lft' => 269,
    'rght' => 270,
    'plugin' => 'User',
    'alias' => 'register',
    'alias_hash' => '9de4a97425678c5b1288aa70c1669a64',
    ],
    136 =>
    [
    'id' => 137,
    'parent_id' => 132,
    'lft' => 271,
    'rght' => 272,
    'plugin' => 'User',
    'alias' => 'activationEmail',
    'alias_hash' => '86b62e721d1fb2f94f296bda930ffd34',
    ],
    137 =>
    [
    'id' => 138,
    'parent_id' => 132,
    'lft' => 273,
    'rght' => 274,
    'plugin' => 'User',
    'alias' => 'activate',
    'alias_hash' => 'd4ee0fbbeb7ffd4fd7a7d477a7ecd922',
    ],
    138 =>
    [
    'id' => 139,
    'parent_id' => 132,
    'lft' => 275,
    'rght' => 276,
    'plugin' => 'User',
    'alias' => 'unauthorized',
    'alias_hash' => '36fd540552b3b1b34e8f0bd8897cbf1e',
    ],
    139 =>
    [
    'id' => 140,
    'parent_id' => 132,
    'lft' => 277,
    'rght' => 278,
    'plugin' => 'User',
    'alias' => 'me',
    'alias_hash' => 'ab86a1e1ef70dff97959067b723c5c24',
    ],
    140 =>
    [
    'id' => 141,
    'parent_id' => 132,
    'lft' => 279,
    'rght' => 280,
    'plugin' => 'User',
    'alias' => 'profile',
    'alias_hash' => '7d97481b1fe66f4b51db90da7e794d9f',
    ],
    141 =>
    [
    'id' => 142,
    'parent_id' => 132,
    'lft' => 281,
    'rght' => 282,
    'plugin' => 'User',
    'alias' => 'login',
    'alias_hash' => 'd56b699830e77ba53855679cb1d252da',
    ],
    142 =>
    [
    'id' => 143,
    'parent_id' => 132,
    'lft' => 283,
    'rght' => 284,
    'plugin' => 'User',
    'alias' => 'logout',
    'alias_hash' => '4236a440a662cc8253d7536e5aa17942',
    ],
    143 =>
    [
    'id' => 144,
    'parent_id' => 131,
    'lft' => 286,
    'rght' => 349,
    'plugin' => 'User',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    144 =>
    [
    'id' => 145,
    'parent_id' => 144,
    'lft' => 287,
    'rght' => 304,
    'plugin' => 'User',
    'alias' => 'Fields',
    'alias_hash' => 'a4ca5edd20d0b5d502ebece575681f58',
    ],
    145 =>
    [
    'id' => 146,
    'parent_id' => 145,
    'lft' => 288,
    'rght' => 289,
    'plugin' => 'User',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    146 =>
    [
    'id' => 147,
    'parent_id' => 145,
    'lft' => 290,
    'rght' => 291,
    'plugin' => 'User',
    'alias' => 'configure',
    'alias_hash' => 'e2d5a00791bce9a01f99bc6fd613a39d',
    ],
    147 =>
    [
    'id' => 148,
    'parent_id' => 145,
    'lft' => 292,
    'rght' => 293,
    'plugin' => 'User',
    'alias' => 'attach',
    'alias_hash' => '915e375d95d78bf040a2e054caadfb56',
    ],
    148 =>
    [
    'id' => 149,
    'parent_id' => 145,
    'lft' => 294,
    'rght' => 295,
    'plugin' => 'User',
    'alias' => 'detach',
    'alias_hash' => 'b6bc015ea9587c510c9017988e94e60d',
    ],
    149 =>
    [
    'id' => 150,
    'parent_id' => 145,
    'lft' => 296,
    'rght' => 297,
    'plugin' => 'User',
    'alias' => 'viewModeList',
    'alias_hash' => 'f6730c40f9e93768852502275e0c9ed5',
    ],
    150 =>
    [
    'id' => 151,
    'parent_id' => 145,
    'lft' => 298,
    'rght' => 299,
    'plugin' => 'User',
    'alias' => 'viewModeEdit',
    'alias_hash' => 'ecb551e7896f4007ed8df082a8184878',
    ],
    151 =>
    [
    'id' => 152,
    'parent_id' => 145,
    'lft' => 300,
    'rght' => 301,
    'plugin' => 'User',
    'alias' => 'viewModeMove',
    'alias_hash' => '5a2933f9feebe723793d21c183be08c6',
    ],
    152 =>
    [
    'id' => 153,
    'parent_id' => 145,
    'lft' => 302,
    'rght' => 303,
    'plugin' => 'User',
    'alias' => 'move',
    'alias_hash' => '3734a903022249b3010be1897042568e',
    ],
    153 =>
    [
    'id' => 154,
    'parent_id' => 144,
    'lft' => 305,
    'rght' => 310,
    'plugin' => 'User',
    'alias' => 'Gateway',
    'alias_hash' => '926dec9494209cb088b4962509df1a91',
    ],
    154 =>
    [
    'id' => 155,
    'parent_id' => 154,
    'lft' => 306,
    'rght' => 307,
    'plugin' => 'User',
    'alias' => 'login',
    'alias_hash' => 'd56b699830e77ba53855679cb1d252da',
    ],
    155 =>
    [
    'id' => 156,
    'parent_id' => 154,
    'lft' => 308,
    'rght' => 309,
    'plugin' => 'User',
    'alias' => 'logout',
    'alias_hash' => '4236a440a662cc8253d7536e5aa17942',
    ],
    156 =>
    [
    'id' => 157,
    'parent_id' => 144,
    'lft' => 311,
    'rght' => 326,
    'plugin' => 'User',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    157 =>
    [
    'id' => 158,
    'parent_id' => 157,
    'lft' => 312,
    'rght' => 313,
    'plugin' => 'User',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    158 =>
    [
    'id' => 159,
    'parent_id' => 157,
    'lft' => 314,
    'rght' => 315,
    'plugin' => 'User',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    159 =>
    [
    'id' => 160,
    'parent_id' => 157,
    'lft' => 316,
    'rght' => 317,
    'plugin' => 'User',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    160 =>
    [
    'id' => 161,
    'parent_id' => 157,
    'lft' => 318,
    'rght' => 319,
    'plugin' => 'User',
    'alias' => 'block',
    'alias_hash' => '14511f2f5564650d129ca7cabc333278',
    ],
    161 =>
    [
    'id' => 162,
    'parent_id' => 157,
    'lft' => 320,
    'rght' => 321,
    'plugin' => 'User',
    'alias' => 'activate',
    'alias_hash' => 'd4ee0fbbeb7ffd4fd7a7d477a7ecd922',
    ],
    162 =>
    [
    'id' => 163,
    'parent_id' => 157,
    'lft' => 322,
    'rght' => 323,
    'plugin' => 'User',
    'alias' => 'passwordInstructions',
    'alias_hash' => '1aae12034ad9d692f6802c1721cc622f',
    ],
    163 =>
    [
    'id' => 164,
    'parent_id' => 157,
    'lft' => 324,
    'rght' => 325,
    'plugin' => 'User',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    164 =>
    [
    'id' => 165,
    'parent_id' => 144,
    'lft' => 327,
    'rght' => 338,
    'plugin' => 'User',
    'alias' => 'Permissions',
    'alias_hash' => 'd08ccf52b4cdd08e41cfb99ec42e0b29',
    ],
    165 =>
    [
    'id' => 166,
    'parent_id' => 165,
    'lft' => 328,
    'rght' => 329,
    'plugin' => 'User',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    166 =>
    [
    'id' => 167,
    'parent_id' => 165,
    'lft' => 330,
    'rght' => 331,
    'plugin' => 'User',
    'alias' => 'aco',
    'alias_hash' => '111c03ddf31a2a03d3fa3377ab07eb56',
    ],
    167 =>
    [
    'id' => 168,
    'parent_id' => 165,
    'lft' => 332,
    'rght' => 333,
    'plugin' => 'User',
    'alias' => 'update',
    'alias_hash' => '3ac340832f29c11538fbe2d6f75e8bcc',
    ],
    168 =>
    [
    'id' => 169,
    'parent_id' => 165,
    'lft' => 334,
    'rght' => 335,
    'plugin' => 'User',
    'alias' => 'export',
    'alias_hash' => 'b2507468f95156358fa490fd543ad2f0',
    ],
    169 =>
    [
    'id' => 170,
    'parent_id' => 165,
    'lft' => 336,
    'rght' => 337,
    'plugin' => 'User',
    'alias' => 'import',
    'alias_hash' => '93473a7344419b15c4219cc2b6c64c6f',
    ],
    170 =>
    [
    'id' => 171,
    'parent_id' => 144,
    'lft' => 339,
    'rght' => 348,
    'plugin' => 'User',
    'alias' => 'Roles',
    'alias_hash' => 'a5cd3ed116608dac017f14c046ea56bf',
    ],
    171 =>
    [
    'id' => 172,
    'parent_id' => 171,
    'lft' => 340,
    'rght' => 341,
    'plugin' => 'User',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    172 =>
    [
    'id' => 173,
    'parent_id' => 171,
    'lft' => 342,
    'rght' => 343,
    'plugin' => 'User',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    173 =>
    [
    'id' => 174,
    'parent_id' => 171,
    'lft' => 344,
    'rght' => 345,
    'plugin' => 'User',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    174 =>
    [
    'id' => 175,
    'parent_id' => 171,
    'lft' => 346,
    'rght' => 347,
    'plugin' => 'User',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    175 =>
    [
    'id' => 176,
    'parent_id' => null,
    'lft' => 351,
    'rght' => 358,
    'plugin' => 'Wysiwyg',
    'alias' => 'Wysiwyg',
    'alias_hash' => 'fcb1d5c3299a281fbb55851547dfac9e',
    ],
    176 =>
    [
    'id' => 177,
    'parent_id' => 176,
    'lft' => 352,
    'rght' => 357,
    'plugin' => 'Wysiwyg',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    177 =>
    [
    'id' => 178,
    'parent_id' => 177,
    'lft' => 353,
    'rght' => 356,
    'plugin' => 'Wysiwyg',
    'alias' => 'Finder',
    'alias_hash' => 'd151508da8d36994e1635f7875594424',
    ],
    178 =>
    [
    'id' => 179,
    'parent_id' => 178,
    'lft' => 354,
    'rght' => 355,
    'plugin' => 'Wysiwyg',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    ];
}
