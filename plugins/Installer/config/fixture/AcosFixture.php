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
    'rght' => 68,
    'plugin' => 'User',
    'alias' => 'User',
    'alias_hash' => '8f9bfe9d1345237cb3b2b205864da075',
    ],
    1 =>
    [
    'id' => 2,
    'parent_id' => 1,
    'lft' => 2,
    'rght' => 15,
    'plugin' => 'User',
    'alias' => 'Gateway',
    'alias_hash' => '926dec9494209cb088b4962509df1a91',
    ],
    2 =>
    [
    'id' => 3,
    'parent_id' => 2,
    'lft' => 3,
    'rght' => 4,
    'plugin' => 'User',
    'alias' => 'login',
    'alias_hash' => 'd56b699830e77ba53855679cb1d252da',
    ],
    3 =>
    [
    'id' => 4,
    'parent_id' => 2,
    'lft' => 5,
    'rght' => 6,
    'plugin' => 'User',
    'alias' => 'logout',
    'alias_hash' => '4236a440a662cc8253d7536e5aa17942',
    ],
    4 =>
    [
    'id' => 5,
    'parent_id' => 2,
    'lft' => 7,
    'rght' => 8,
    'plugin' => 'User',
    'alias' => 'forgot',
    'alias_hash' => '790f6b6cf6a6fbead525927d69f409fe',
    ],
    5 =>
    [
    'id' => 6,
    'parent_id' => 2,
    'lft' => 9,
    'rght' => 10,
    'plugin' => 'User',
    'alias' => 'me',
    'alias_hash' => 'ab86a1e1ef70dff97959067b723c5c24',
    ],
    6 =>
    [
    'id' => 7,
    'parent_id' => 2,
    'lft' => 11,
    'rght' => 12,
    'plugin' => 'User',
    'alias' => 'profile',
    'alias_hash' => '7d97481b1fe66f4b51db90da7e794d9f',
    ],
    7 =>
    [
    'id' => 8,
    'parent_id' => 2,
    'lft' => 13,
    'rght' => 14,
    'plugin' => 'User',
    'alias' => 'unauthorized',
    'alias_hash' => '36fd540552b3b1b34e8f0bd8897cbf1e',
    ],
    8 =>
    [
    'id' => 9,
    'parent_id' => 1,
    'lft' => 16,
    'rght' => 67,
    'plugin' => 'User',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    9 =>
    [
    'id' => 10,
    'parent_id' => 9,
    'lft' => 17,
    'rght' => 34,
    'plugin' => 'User',
    'alias' => 'Fields',
    'alias_hash' => 'a4ca5edd20d0b5d502ebece575681f58',
    ],
    10 =>
    [
    'id' => 11,
    'parent_id' => 10,
    'lft' => 18,
    'rght' => 19,
    'plugin' => 'User',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    11 =>
    [
    'id' => 12,
    'parent_id' => 10,
    'lft' => 20,
    'rght' => 21,
    'plugin' => 'User',
    'alias' => 'configure',
    'alias_hash' => 'e2d5a00791bce9a01f99bc6fd613a39d',
    ],
    12 =>
    [
    'id' => 13,
    'parent_id' => 10,
    'lft' => 22,
    'rght' => 23,
    'plugin' => 'User',
    'alias' => 'attach',
    'alias_hash' => '915e375d95d78bf040a2e054caadfb56',
    ],
    13 =>
    [
    'id' => 14,
    'parent_id' => 10,
    'lft' => 24,
    'rght' => 25,
    'plugin' => 'User',
    'alias' => 'detach',
    'alias_hash' => 'b6bc015ea9587c510c9017988e94e60d',
    ],
    14 =>
    [
    'id' => 15,
    'parent_id' => 10,
    'lft' => 26,
    'rght' => 27,
    'plugin' => 'User',
    'alias' => 'view_mode_list',
    'alias_hash' => '50dc11f5c94a739237c8685e567a28d8',
    ],
    15 =>
    [
    'id' => 16,
    'parent_id' => 10,
    'lft' => 28,
    'rght' => 29,
    'plugin' => 'User',
    'alias' => 'view_mode_edit',
    'alias_hash' => 'b04ebb03255647bd460b7f67b763fb89',
    ],
    16 =>
    [
    'id' => 17,
    'parent_id' => 10,
    'lft' => 30,
    'rght' => 31,
    'plugin' => 'User',
    'alias' => 'view_mode_move',
    'alias_hash' => '6d54c39b597f25d371090b1de3bffbfa',
    ],
    17 =>
    [
    'id' => 18,
    'parent_id' => 10,
    'lft' => 32,
    'rght' => 33,
    'plugin' => 'User',
    'alias' => 'move',
    'alias_hash' => '3734a903022249b3010be1897042568e',
    ],
    18 =>
    [
    'id' => 19,
    'parent_id' => 9,
    'lft' => 35,
    'rght' => 44,
    'plugin' => 'User',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    19 =>
    [
    'id' => 20,
    'parent_id' => 19,
    'lft' => 36,
    'rght' => 37,
    'plugin' => 'User',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    20 =>
    [
    'id' => 21,
    'parent_id' => 19,
    'lft' => 38,
    'rght' => 39,
    'plugin' => 'User',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    21 =>
    [
    'id' => 22,
    'parent_id' => 19,
    'lft' => 40,
    'rght' => 41,
    'plugin' => 'User',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    22 =>
    [
    'id' => 23,
    'parent_id' => 19,
    'lft' => 42,
    'rght' => 43,
    'plugin' => 'User',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    23 =>
    [
    'id' => 24,
    'parent_id' => 9,
    'lft' => 45,
    'rght' => 56,
    'plugin' => 'User',
    'alias' => 'Permissions',
    'alias_hash' => 'd08ccf52b4cdd08e41cfb99ec42e0b29',
    ],
    24 =>
    [
    'id' => 25,
    'parent_id' => 24,
    'lft' => 46,
    'rght' => 47,
    'plugin' => 'User',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    25 =>
    [
    'id' => 26,
    'parent_id' => 24,
    'lft' => 48,
    'rght' => 49,
    'plugin' => 'User',
    'alias' => 'aco',
    'alias_hash' => '111c03ddf31a2a03d3fa3377ab07eb56',
    ],
    26 =>
    [
    'id' => 27,
    'parent_id' => 24,
    'lft' => 50,
    'rght' => 51,
    'plugin' => 'User',
    'alias' => 'update',
    'alias_hash' => '3ac340832f29c11538fbe2d6f75e8bcc',
    ],
    27 =>
    [
    'id' => 28,
    'parent_id' => 24,
    'lft' => 52,
    'rght' => 53,
    'plugin' => 'User',
    'alias' => 'export',
    'alias_hash' => 'b2507468f95156358fa490fd543ad2f0',
    ],
    28 =>
    [
    'id' => 29,
    'parent_id' => 24,
    'lft' => 54,
    'rght' => 55,
    'plugin' => 'User',
    'alias' => 'import',
    'alias_hash' => '93473a7344419b15c4219cc2b6c64c6f',
    ],
    29 =>
    [
    'id' => 30,
    'parent_id' => 9,
    'lft' => 57,
    'rght' => 66,
    'plugin' => 'User',
    'alias' => 'Roles',
    'alias_hash' => 'a5cd3ed116608dac017f14c046ea56bf',
    ],
    30 =>
    [
    'id' => 31,
    'parent_id' => 30,
    'lft' => 58,
    'rght' => 59,
    'plugin' => 'User',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    31 =>
    [
    'id' => 32,
    'parent_id' => 30,
    'lft' => 60,
    'rght' => 61,
    'plugin' => 'User',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    32 =>
    [
    'id' => 33,
    'parent_id' => 30,
    'lft' => 62,
    'rght' => 63,
    'plugin' => 'User',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    33 =>
    [
    'id' => 34,
    'parent_id' => 30,
    'lft' => 64,
    'rght' => 65,
    'plugin' => 'User',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    34 =>
    [
    'id' => 35,
    'parent_id' => null,
    'lft' => 69,
    'rght' => 100,
    'plugin' => 'Taxonomy',
    'alias' => 'Taxonomy',
    'alias_hash' => '30d10883c017c4fd6751c8982e20dae1',
    ],
    35 =>
    [
    'id' => 36,
    'parent_id' => 35,
    'lft' => 70,
    'rght' => 99,
    'plugin' => 'Taxonomy',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    36 =>
    [
    'id' => 37,
    'parent_id' => 36,
    'lft' => 71,
    'rght' => 74,
    'plugin' => 'Taxonomy',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    37 =>
    [
    'id' => 38,
    'parent_id' => 37,
    'lft' => 72,
    'rght' => 73,
    'plugin' => 'Taxonomy',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    38 =>
    [
    'id' => 39,
    'parent_id' => 36,
    'lft' => 75,
    'rght' => 78,
    'plugin' => 'Taxonomy',
    'alias' => 'Tagger',
    'alias_hash' => 'e34d9224f0bf63992e1e77451c6976d1',
    ],
    39 =>
    [
    'id' => 40,
    'parent_id' => 39,
    'lft' => 76,
    'rght' => 77,
    'plugin' => 'Taxonomy',
    'alias' => 'search',
    'alias_hash' => '06a943c59f33a34bb5924aaf72cd2995',
    ],
    40 =>
    [
    'id' => 41,
    'parent_id' => 36,
    'lft' => 79,
    'rght' => 88,
    'plugin' => 'Taxonomy',
    'alias' => 'Terms',
    'alias_hash' => '6f1bf85c9ebb3c7fa26251e1e335e032',
    ],
    41 =>
    [
    'id' => 42,
    'parent_id' => 41,
    'lft' => 80,
    'rght' => 81,
    'plugin' => 'Taxonomy',
    'alias' => 'vocabulary',
    'alias_hash' => '09f06963f502addfeab2a7c87f38802e',
    ],
    42 =>
    [
    'id' => 43,
    'parent_id' => 41,
    'lft' => 82,
    'rght' => 83,
    'plugin' => 'Taxonomy',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    43 =>
    [
    'id' => 44,
    'parent_id' => 41,
    'lft' => 84,
    'rght' => 85,
    'plugin' => 'Taxonomy',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    44 =>
    [
    'id' => 45,
    'parent_id' => 41,
    'lft' => 86,
    'rght' => 87,
    'plugin' => 'Taxonomy',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    45 =>
    [
    'id' => 46,
    'parent_id' => 36,
    'lft' => 89,
    'rght' => 98,
    'plugin' => 'Taxonomy',
    'alias' => 'Vocabularies',
    'alias_hash' => '81a419751eb59e7d35acab8e532d59a7',
    ],
    46 =>
    [
    'id' => 47,
    'parent_id' => 46,
    'lft' => 90,
    'rght' => 91,
    'plugin' => 'Taxonomy',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    47 =>
    [
    'id' => 48,
    'parent_id' => 46,
    'lft' => 92,
    'rght' => 93,
    'plugin' => 'Taxonomy',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    48 =>
    [
    'id' => 49,
    'parent_id' => 46,
    'lft' => 94,
    'rght' => 95,
    'plugin' => 'Taxonomy',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    49 =>
    [
    'id' => 50,
    'parent_id' => 46,
    'lft' => 96,
    'rght' => 97,
    'plugin' => 'Taxonomy',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    50 =>
    [
    'id' => 51,
    'parent_id' => null,
    'lft' => 101,
    'rght' => 152,
    'plugin' => 'System',
    'alias' => 'System',
    'alias_hash' => 'a45da96d0bf6575970f2d27af22be28a',
    ],
    51 =>
    [
    'id' => 52,
    'parent_id' => 51,
    'lft' => 102,
    'rght' => 151,
    'plugin' => 'System',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    52 =>
    [
    'id' => 53,
    'parent_id' => 52,
    'lft' => 103,
    'rght' => 106,
    'plugin' => 'System',
    'alias' => 'Configuration',
    'alias_hash' => '254f642527b45bc260048e30704edb39',
    ],
    53 =>
    [
    'id' => 54,
    'parent_id' => 53,
    'lft' => 104,
    'rght' => 105,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    54 =>
    [
    'id' => 55,
    'parent_id' => 52,
    'lft' => 107,
    'rght' => 110,
    'plugin' => 'System',
    'alias' => 'Dashboard',
    'alias_hash' => '2938c7f7e560ed972f8a4f68e80ff834',
    ],
    55 =>
    [
    'id' => 56,
    'parent_id' => 55,
    'lft' => 108,
    'rght' => 109,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    56 =>
    [
    'id' => 57,
    'parent_id' => 52,
    'lft' => 111,
    'rght' => 116,
    'plugin' => 'System',
    'alias' => 'Help',
    'alias_hash' => '6a26f548831e6a8c26bfbbd9f6ec61e0',
    ],
    57 =>
    [
    'id' => 58,
    'parent_id' => 57,
    'lft' => 112,
    'rght' => 113,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    58 =>
    [
    'id' => 59,
    'parent_id' => 57,
    'lft' => 114,
    'rght' => 115,
    'plugin' => 'System',
    'alias' => 'about',
    'alias_hash' => '46b3931b9959c927df4fc65fdee94b07',
    ],
    59 =>
    [
    'id' => 60,
    'parent_id' => 52,
    'lft' => 117,
    'rght' => 130,
    'plugin' => 'System',
    'alias' => 'Plugins',
    'alias_hash' => 'bb38096ab39160dc20d44f3ea6b44507',
    ],
    60 =>
    [
    'id' => 61,
    'parent_id' => 60,
    'lft' => 118,
    'rght' => 119,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    61 =>
    [
    'id' => 62,
    'parent_id' => 60,
    'lft' => 120,
    'rght' => 121,
    'plugin' => 'System',
    'alias' => 'install',
    'alias_hash' => '19ad89bc3e3c9d7ef68b89523eff1987',
    ],
    62 =>
    [
    'id' => 63,
    'parent_id' => 60,
    'lft' => 122,
    'rght' => 123,
    'plugin' => 'System',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    63 =>
    [
    'id' => 64,
    'parent_id' => 60,
    'lft' => 124,
    'rght' => 125,
    'plugin' => 'System',
    'alias' => 'enable',
    'alias_hash' => '208f156d4a803025c284bb595a7576b4',
    ],
    64 =>
    [
    'id' => 65,
    'parent_id' => 60,
    'lft' => 126,
    'rght' => 127,
    'plugin' => 'System',
    'alias' => 'disable',
    'alias_hash' => '0aaa87422396fdd678498793b6d5250e',
    ],
    65 =>
    [
    'id' => 66,
    'parent_id' => 60,
    'lft' => 128,
    'rght' => 129,
    'plugin' => 'System',
    'alias' => 'settings',
    'alias_hash' => '2e5d8aa3dfa8ef34ca5131d20f9dad51',
    ],
    66 =>
    [
    'id' => 67,
    'parent_id' => 52,
    'lft' => 131,
    'rght' => 134,
    'plugin' => 'System',
    'alias' => 'Structure',
    'alias_hash' => 'dc4c71563b9bc39a65be853457e6b7b6',
    ],
    67 =>
    [
    'id' => 68,
    'parent_id' => 67,
    'lft' => 132,
    'rght' => 133,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    68 =>
    [
    'id' => 69,
    'parent_id' => 52,
    'lft' => 135,
    'rght' => 150,
    'plugin' => 'System',
    'alias' => 'Themes',
    'alias_hash' => '83915d1254927f41241e8630890bec6e',
    ],
    69 =>
    [
    'id' => 70,
    'parent_id' => 69,
    'lft' => 136,
    'rght' => 137,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    70 =>
    [
    'id' => 71,
    'parent_id' => 69,
    'lft' => 138,
    'rght' => 139,
    'plugin' => 'System',
    'alias' => 'install',
    'alias_hash' => '19ad89bc3e3c9d7ef68b89523eff1987',
    ],
    71 =>
    [
    'id' => 72,
    'parent_id' => 69,
    'lft' => 140,
    'rght' => 141,
    'plugin' => 'System',
    'alias' => 'uninstall',
    'alias_hash' => 'fe98497efedbe156ecc4b953aea77e07',
    ],
    72 =>
    [
    'id' => 73,
    'parent_id' => 69,
    'lft' => 142,
    'rght' => 143,
    'plugin' => 'System',
    'alias' => 'activate',
    'alias_hash' => 'd4ee0fbbeb7ffd4fd7a7d477a7ecd922',
    ],
    73 =>
    [
    'id' => 74,
    'parent_id' => 69,
    'lft' => 144,
    'rght' => 145,
    'plugin' => 'System',
    'alias' => 'details',
    'alias_hash' => '27792947ed5d5da7c0d1f43327ed9dab',
    ],
    74 =>
    [
    'id' => 75,
    'parent_id' => 69,
    'lft' => 146,
    'rght' => 147,
    'plugin' => 'System',
    'alias' => 'screenshot',
    'alias_hash' => '62c92ba585f74ecdbef4c4498a438984',
    ],
    75 =>
    [
    'id' => 76,
    'parent_id' => 69,
    'lft' => 148,
    'rght' => 149,
    'plugin' => 'System',
    'alias' => 'settings',
    'alias_hash' => '2e5d8aa3dfa8ef34ca5131d20f9dad51',
    ],
    76 =>
    [
    'id' => 77,
    'parent_id' => null,
    'lft' => 153,
    'rght' => 224,
    'plugin' => 'Node',
    'alias' => 'Node',
    'alias_hash' => '6c3a6944a808a7c0bbb6788dbec54a9f',
    ],
    77 =>
    [
    'id' => 78,
    'parent_id' => 77,
    'lft' => 154,
    'rght' => 165,
    'plugin' => 'Node',
    'alias' => 'Serve',
    'alias_hash' => 'bc9a5b9e9259199a79f67ded0b508dfc',
    ],
    78 =>
    [
    'id' => 79,
    'parent_id' => 78,
    'lft' => 155,
    'rght' => 156,
    'plugin' => 'Node',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    79 =>
    [
    'id' => 80,
    'parent_id' => 78,
    'lft' => 157,
    'rght' => 158,
    'plugin' => 'Node',
    'alias' => 'home',
    'alias_hash' => '106a6c241b8797f52e1e77317b96a201',
    ],
    80 =>
    [
    'id' => 81,
    'parent_id' => 78,
    'lft' => 159,
    'rght' => 160,
    'plugin' => 'Node',
    'alias' => 'details',
    'alias_hash' => '27792947ed5d5da7c0d1f43327ed9dab',
    ],
    81 =>
    [
    'id' => 82,
    'parent_id' => 78,
    'lft' => 161,
    'rght' => 162,
    'plugin' => 'Node',
    'alias' => 'search',
    'alias_hash' => '06a943c59f33a34bb5924aaf72cd2995',
    ],
    82 =>
    [
    'id' => 83,
    'parent_id' => 78,
    'lft' => 163,
    'rght' => 164,
    'plugin' => 'Node',
    'alias' => 'rss',
    'alias_hash' => '8bb856027f758e85ddf2085c98ae2908',
    ],
    83 =>
    [
    'id' => 84,
    'parent_id' => 77,
    'lft' => 166,
    'rght' => 223,
    'plugin' => 'Node',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    84 =>
    [
    'id' => 85,
    'parent_id' => 84,
    'lft' => 167,
    'rght' => 178,
    'plugin' => 'Node',
    'alias' => 'Comments',
    'alias_hash' => '8413c683b4b27cc3f4dbd4c90329d8ba',
    ],
    85 =>
    [
    'id' => 86,
    'parent_id' => 85,
    'lft' => 168,
    'rght' => 169,
    'plugin' => 'Node',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    86 =>
    [
    'id' => 87,
    'parent_id' => 85,
    'lft' => 170,
    'rght' => 171,
    'plugin' => 'Node',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    87 =>
    [
    'id' => 88,
    'parent_id' => 85,
    'lft' => 172,
    'rght' => 173,
    'plugin' => 'Node',
    'alias' => 'status',
    'alias_hash' => '9acb44549b41563697bb490144ec6258',
    ],
    88 =>
    [
    'id' => 89,
    'parent_id' => 85,
    'lft' => 174,
    'rght' => 175,
    'plugin' => 'Node',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    89 =>
    [
    'id' => 90,
    'parent_id' => 85,
    'lft' => 176,
    'rght' => 177,
    'plugin' => 'Node',
    'alias' => 'empty_trash',
    'alias_hash' => '5e0e12d2aafec2a296b4d8ed252147b8',
    ],
    90 =>
    [
    'id' => 91,
    'parent_id' => 84,
    'lft' => 179,
    'rght' => 196,
    'plugin' => 'Node',
    'alias' => 'Fields',
    'alias_hash' => 'a4ca5edd20d0b5d502ebece575681f58',
    ],
    91 =>
    [
    'id' => 92,
    'parent_id' => 91,
    'lft' => 180,
    'rght' => 181,
    'plugin' => 'Node',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    92 =>
    [
    'id' => 93,
    'parent_id' => 91,
    'lft' => 182,
    'rght' => 183,
    'plugin' => 'Node',
    'alias' => 'configure',
    'alias_hash' => 'e2d5a00791bce9a01f99bc6fd613a39d',
    ],
    93 =>
    [
    'id' => 94,
    'parent_id' => 91,
    'lft' => 184,
    'rght' => 185,
    'plugin' => 'Node',
    'alias' => 'attach',
    'alias_hash' => '915e375d95d78bf040a2e054caadfb56',
    ],
    94 =>
    [
    'id' => 95,
    'parent_id' => 91,
    'lft' => 186,
    'rght' => 187,
    'plugin' => 'Node',
    'alias' => 'detach',
    'alias_hash' => 'b6bc015ea9587c510c9017988e94e60d',
    ],
    95 =>
    [
    'id' => 96,
    'parent_id' => 91,
    'lft' => 188,
    'rght' => 189,
    'plugin' => 'Node',
    'alias' => 'view_mode_list',
    'alias_hash' => '50dc11f5c94a739237c8685e567a28d8',
    ],
    96 =>
    [
    'id' => 97,
    'parent_id' => 91,
    'lft' => 190,
    'rght' => 191,
    'plugin' => 'Node',
    'alias' => 'view_mode_edit',
    'alias_hash' => 'b04ebb03255647bd460b7f67b763fb89',
    ],
    97 =>
    [
    'id' => 98,
    'parent_id' => 91,
    'lft' => 192,
    'rght' => 193,
    'plugin' => 'Node',
    'alias' => 'view_mode_move',
    'alias_hash' => '6d54c39b597f25d371090b1de3bffbfa',
    ],
    98 =>
    [
    'id' => 99,
    'parent_id' => 91,
    'lft' => 194,
    'rght' => 195,
    'plugin' => 'Node',
    'alias' => 'move',
    'alias_hash' => '3734a903022249b3010be1897042568e',
    ],
    99 =>
    [
    'id' => 100,
    'parent_id' => 84,
    'lft' => 197,
    'rght' => 212,
    'plugin' => 'Node',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    100 =>
    [
    'id' => 101,
    'parent_id' => 100,
    'lft' => 198,
    'rght' => 199,
    'plugin' => 'Node',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    101 =>
    [
    'id' => 102,
    'parent_id' => 100,
    'lft' => 200,
    'rght' => 201,
    'plugin' => 'Node',
    'alias' => 'create',
    'alias_hash' => '76ea0bebb3c22822b4f0dd9c9fd021c5',
    ],
    102 =>
    [
    'id' => 103,
    'parent_id' => 100,
    'lft' => 202,
    'rght' => 203,
    'plugin' => 'Node',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    103 =>
    [
    'id' => 104,
    'parent_id' => 100,
    'lft' => 204,
    'rght' => 205,
    'plugin' => 'Node',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    104 =>
    [
    'id' => 105,
    'parent_id' => 100,
    'lft' => 206,
    'rght' => 207,
    'plugin' => 'Node',
    'alias' => 'translate',
    'alias_hash' => 'fc46e26a907870744758b76166150f62',
    ],
    105 =>
    [
    'id' => 106,
    'parent_id' => 100,
    'lft' => 208,
    'rght' => 209,
    'plugin' => 'Node',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    106 =>
    [
    'id' => 107,
    'parent_id' => 100,
    'lft' => 210,
    'rght' => 211,
    'plugin' => 'Node',
    'alias' => 'delete_revision',
    'alias_hash' => '077308769b80b2240aa845a5dff20436',
    ],
    107 =>
    [
    'id' => 108,
    'parent_id' => 84,
    'lft' => 213,
    'rght' => 222,
    'plugin' => 'Node',
    'alias' => 'Types',
    'alias_hash' => 'f2d346b1bb7c1c85ab6f7f21e3666b9f',
    ],
    108 =>
    [
    'id' => 109,
    'parent_id' => 108,
    'lft' => 214,
    'rght' => 215,
    'plugin' => 'Node',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    109 =>
    [
    'id' => 110,
    'parent_id' => 108,
    'lft' => 216,
    'rght' => 217,
    'plugin' => 'Node',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    110 =>
    [
    'id' => 111,
    'parent_id' => 108,
    'lft' => 218,
    'rght' => 219,
    'plugin' => 'Node',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    111 =>
    [
    'id' => 112,
    'parent_id' => 108,
    'lft' => 220,
    'rght' => 221,
    'plugin' => 'Node',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    112 =>
    [
    'id' => 113,
    'parent_id' => null,
    'lft' => 225,
    'rght' => 248,
    'plugin' => 'Menu',
    'alias' => 'Menu',
    'alias_hash' => 'b61541208db7fa7dba42c85224405911',
    ],
    113 =>
    [
    'id' => 114,
    'parent_id' => 113,
    'lft' => 226,
    'rght' => 247,
    'plugin' => 'Menu',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    114 =>
    [
    'id' => 115,
    'parent_id' => 114,
    'lft' => 227,
    'rght' => 236,
    'plugin' => 'Menu',
    'alias' => 'Links',
    'alias_hash' => 'bd908db5ccb07777ced8023dffc802f4',
    ],
    115 =>
    [
    'id' => 116,
    'parent_id' => 115,
    'lft' => 228,
    'rght' => 229,
    'plugin' => 'Menu',
    'alias' => 'menu',
    'alias_hash' => '8d6ab84ca2af9fccd4e4048694176ebf',
    ],
    116 =>
    [
    'id' => 117,
    'parent_id' => 115,
    'lft' => 230,
    'rght' => 231,
    'plugin' => 'Menu',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    117 =>
    [
    'id' => 118,
    'parent_id' => 115,
    'lft' => 232,
    'rght' => 233,
    'plugin' => 'Menu',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    118 =>
    [
    'id' => 119,
    'parent_id' => 115,
    'lft' => 234,
    'rght' => 235,
    'plugin' => 'Menu',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    119 =>
    [
    'id' => 120,
    'parent_id' => 114,
    'lft' => 237,
    'rght' => 246,
    'plugin' => 'Menu',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    120 =>
    [
    'id' => 121,
    'parent_id' => 120,
    'lft' => 238,
    'rght' => 239,
    'plugin' => 'Menu',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    121 =>
    [
    'id' => 122,
    'parent_id' => 120,
    'lft' => 240,
    'rght' => 241,
    'plugin' => 'Menu',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    122 =>
    [
    'id' => 123,
    'parent_id' => 120,
    'lft' => 242,
    'rght' => 243,
    'plugin' => 'Menu',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    123 =>
    [
    'id' => 124,
    'parent_id' => 120,
    'lft' => 244,
    'rght' => 245,
    'plugin' => 'Menu',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    124 =>
    [
    'id' => 125,
    'parent_id' => null,
    'lft' => 249,
    'rght' => 270,
    'plugin' => 'Locale',
    'alias' => 'Locale',
    'alias_hash' => '911f0f24bdce6808f4614d6a263b143b',
    ],
    125 =>
    [
    'id' => 126,
    'parent_id' => 125,
    'lft' => 250,
    'rght' => 269,
    'plugin' => 'Locale',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    126 =>
    [
    'id' => 127,
    'parent_id' => 126,
    'lft' => 251,
    'rght' => 268,
    'plugin' => 'Locale',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    127 =>
    [
    'id' => 128,
    'parent_id' => 127,
    'lft' => 252,
    'rght' => 253,
    'plugin' => 'Locale',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    128 =>
    [
    'id' => 129,
    'parent_id' => 127,
    'lft' => 254,
    'rght' => 255,
    'plugin' => 'Locale',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    129 =>
    [
    'id' => 130,
    'parent_id' => 127,
    'lft' => 256,
    'rght' => 257,
    'plugin' => 'Locale',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    130 =>
    [
    'id' => 131,
    'parent_id' => 127,
    'lft' => 258,
    'rght' => 259,
    'plugin' => 'Locale',
    'alias' => 'set_default',
    'alias_hash' => '4889ae9437342e57d774bc6d5705c7a4',
    ],
    131 =>
    [
    'id' => 132,
    'parent_id' => 127,
    'lft' => 260,
    'rght' => 261,
    'plugin' => 'Locale',
    'alias' => 'move',
    'alias_hash' => '3734a903022249b3010be1897042568e',
    ],
    132 =>
    [
    'id' => 133,
    'parent_id' => 127,
    'lft' => 262,
    'rght' => 263,
    'plugin' => 'Locale',
    'alias' => 'enable',
    'alias_hash' => '208f156d4a803025c284bb595a7576b4',
    ],
    133 =>
    [
    'id' => 134,
    'parent_id' => 127,
    'lft' => 264,
    'rght' => 265,
    'plugin' => 'Locale',
    'alias' => 'disable',
    'alias_hash' => '0aaa87422396fdd678498793b6d5250e',
    ],
    134 =>
    [
    'id' => 135,
    'parent_id' => 127,
    'lft' => 266,
    'rght' => 267,
    'plugin' => 'Locale',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    135 =>
    [
    'id' => 136,
    'parent_id' => null,
    'lft' => 271,
    'rght' => 288,
    'plugin' => 'Installer',
    'alias' => 'Installer',
    'alias_hash' => 'd1be377656960ed04f1564da21d80c8d',
    ],
    136 =>
    [
    'id' => 137,
    'parent_id' => 136,
    'lft' => 272,
    'rght' => 287,
    'plugin' => 'Installer',
    'alias' => 'Startup',
    'alias_hash' => '13e685964c2548aa748f7ea263bad4e5',
    ],
    137 =>
    [
    'id' => 138,
    'parent_id' => 137,
    'lft' => 273,
    'rght' => 274,
    'plugin' => 'Installer',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    138 =>
    [
    'id' => 139,
    'parent_id' => 137,
    'lft' => 275,
    'rght' => 276,
    'plugin' => 'Installer',
    'alias' => 'language',
    'alias_hash' => '8512ae7d57b1396273f76fe6ed341a23',
    ],
    139 =>
    [
    'id' => 140,
    'parent_id' => 137,
    'lft' => 277,
    'rght' => 278,
    'plugin' => 'Installer',
    'alias' => 'requirements',
    'alias_hash' => 'b4851e92b19af0c5c82447fc0937709d',
    ],
    140 =>
    [
    'id' => 141,
    'parent_id' => 137,
    'lft' => 279,
    'rght' => 280,
    'plugin' => 'Installer',
    'alias' => 'license',
    'alias_hash' => '718779752b851ac0dc6281a8c8d77e7e',
    ],
    141 =>
    [
    'id' => 142,
    'parent_id' => 137,
    'lft' => 281,
    'rght' => 282,
    'plugin' => 'Installer',
    'alias' => 'database',
    'alias_hash' => '11e0eed8d3696c0a632f822df385ab3c',
    ],
    142 =>
    [
    'id' => 143,
    'parent_id' => 137,
    'lft' => 283,
    'rght' => 284,
    'plugin' => 'Installer',
    'alias' => 'account',
    'alias_hash' => 'e268443e43d93dab7ebef303bbe9642f',
    ],
    143 =>
    [
    'id' => 144,
    'parent_id' => 137,
    'lft' => 285,
    'rght' => 286,
    'plugin' => 'Installer',
    'alias' => 'finish',
    'alias_hash' => '3248bc7547ce97b2a197b2a06cf7283d',
    ],
    144 =>
    [
    'id' => 145,
    'parent_id' => null,
    'lft' => 289,
    'rght' => 304,
    'plugin' => 'Block',
    'alias' => 'Block',
    'alias_hash' => 'e1e4c8c9ccd9fc39c391da4bcd093fb2',
    ],
    145 =>
    [
    'id' => 146,
    'parent_id' => 145,
    'lft' => 290,
    'rght' => 303,
    'plugin' => 'Block',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    146 =>
    [
    'id' => 147,
    'parent_id' => 146,
    'lft' => 291,
    'rght' => 302,
    'plugin' => 'Block',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    147 =>
    [
    'id' => 148,
    'parent_id' => 147,
    'lft' => 292,
    'rght' => 293,
    'plugin' => 'Block',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    148 =>
    [
    'id' => 149,
    'parent_id' => 147,
    'lft' => 294,
    'rght' => 295,
    'plugin' => 'Block',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    149 =>
    [
    'id' => 150,
    'parent_id' => 147,
    'lft' => 296,
    'rght' => 297,
    'plugin' => 'Block',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    150 =>
    [
    'id' => 151,
    'parent_id' => 147,
    'lft' => 298,
    'rght' => 299,
    'plugin' => 'Block',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    151 =>
    [
    'id' => 152,
    'parent_id' => 147,
    'lft' => 300,
    'rght' => 301,
    'plugin' => 'Block',
    'alias' => 'duplicate',
    'alias_hash' => '24f1b0a79473250c195c7fb84e393392',
    ],
    152 =>
    [
    'id' => 153,
    'parent_id' => null,
    'lft' => 305,
    'rght' => 316,
    'plugin' => 'Wysiwyg',
    'alias' => 'Wysiwyg',
    'alias_hash' => 'fcb1d5c3299a281fbb55851547dfac9e',
    ],
    153 =>
    [
    'id' => 154,
    'parent_id' => 153,
    'lft' => 306,
    'rght' => 315,
    'plugin' => 'Wysiwyg',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    154 =>
    [
    'id' => 155,
    'parent_id' => 154,
    'lft' => 307,
    'rght' => 314,
    'plugin' => 'Wysiwyg',
    'alias' => 'Finder',
    'alias_hash' => 'd151508da8d36994e1635f7875594424',
    ],
    155 =>
    [
    'id' => 156,
    'parent_id' => 155,
    'lft' => 308,
    'rght' => 309,
    'plugin' => 'Wysiwyg',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    156 =>
    [
    'id' => 157,
    'parent_id' => 155,
    'lft' => 310,
    'rght' => 311,
    'plugin' => 'Wysiwyg',
    'alias' => 'connector',
    'alias_hash' => '266e0d3d29830abfe7d4ed98b47966f7',
    ],
    157 =>
    [
    'id' => 158,
    'parent_id' => 155,
    'lft' => 312,
    'rght' => 313,
    'plugin' => 'Wysiwyg',
    'alias' => 'plugin_file',
    'alias_hash' => '53fcd0f3eb0844a4d22699a9b73a77cd',
    ],
    ];
}
