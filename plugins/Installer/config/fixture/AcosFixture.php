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
    'rght' => 90,
    'plugin' => 'User',
    'alias' => 'User',
    'alias_hash' => '8f9bfe9d1345237cb3b2b205864da075',
    ],
    1 =>
    [
    'id' => 2,
    'parent_id' => 1,
    'lft' => 2,
    'rght' => 25,
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
    'lft' => 26,
    'rght' => 89,
    'plugin' => 'User',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    9 =>
    [
    'id' => 10,
    'parent_id' => 9,
    'lft' => 27,
    'rght' => 44,
    'plugin' => 'User',
    'alias' => 'Fields',
    'alias_hash' => 'a4ca5edd20d0b5d502ebece575681f58',
    ],
    10 =>
    [
    'id' => 11,
    'parent_id' => 10,
    'lft' => 28,
    'rght' => 29,
    'plugin' => 'User',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    11 =>
    [
    'id' => 12,
    'parent_id' => 10,
    'lft' => 30,
    'rght' => 31,
    'plugin' => 'User',
    'alias' => 'configure',
    'alias_hash' => 'e2d5a00791bce9a01f99bc6fd613a39d',
    ],
    12 =>
    [
    'id' => 13,
    'parent_id' => 10,
    'lft' => 32,
    'rght' => 33,
    'plugin' => 'User',
    'alias' => 'attach',
    'alias_hash' => '915e375d95d78bf040a2e054caadfb56',
    ],
    13 =>
    [
    'id' => 14,
    'parent_id' => 10,
    'lft' => 34,
    'rght' => 35,
    'plugin' => 'User',
    'alias' => 'detach',
    'alias_hash' => 'b6bc015ea9587c510c9017988e94e60d',
    ],
    14 =>
    [
    'id' => 18,
    'parent_id' => 10,
    'lft' => 36,
    'rght' => 37,
    'plugin' => 'User',
    'alias' => 'move',
    'alias_hash' => '3734a903022249b3010be1897042568e',
    ],
    15 =>
    [
    'id' => 19,
    'parent_id' => 9,
    'lft' => 45,
    'rght' => 60,
    'plugin' => 'User',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    16 =>
    [
    'id' => 20,
    'parent_id' => 19,
    'lft' => 46,
    'rght' => 47,
    'plugin' => 'User',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    17 =>
    [
    'id' => 21,
    'parent_id' => 19,
    'lft' => 48,
    'rght' => 49,
    'plugin' => 'User',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    18 =>
    [
    'id' => 22,
    'parent_id' => 19,
    'lft' => 50,
    'rght' => 51,
    'plugin' => 'User',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    19 =>
    [
    'id' => 23,
    'parent_id' => 19,
    'lft' => 52,
    'rght' => 53,
    'plugin' => 'User',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    20 =>
    [
    'id' => 24,
    'parent_id' => 9,
    'lft' => 61,
    'rght' => 72,
    'plugin' => 'User',
    'alias' => 'Permissions',
    'alias_hash' => 'd08ccf52b4cdd08e41cfb99ec42e0b29',
    ],
    21 =>
    [
    'id' => 25,
    'parent_id' => 24,
    'lft' => 62,
    'rght' => 63,
    'plugin' => 'User',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    22 =>
    [
    'id' => 26,
    'parent_id' => 24,
    'lft' => 64,
    'rght' => 65,
    'plugin' => 'User',
    'alias' => 'aco',
    'alias_hash' => '111c03ddf31a2a03d3fa3377ab07eb56',
    ],
    23 =>
    [
    'id' => 27,
    'parent_id' => 24,
    'lft' => 66,
    'rght' => 67,
    'plugin' => 'User',
    'alias' => 'update',
    'alias_hash' => '3ac340832f29c11538fbe2d6f75e8bcc',
    ],
    24 =>
    [
    'id' => 28,
    'parent_id' => 24,
    'lft' => 68,
    'rght' => 69,
    'plugin' => 'User',
    'alias' => 'export',
    'alias_hash' => 'b2507468f95156358fa490fd543ad2f0',
    ],
    25 =>
    [
    'id' => 29,
    'parent_id' => 24,
    'lft' => 70,
    'rght' => 71,
    'plugin' => 'User',
    'alias' => 'import',
    'alias_hash' => '93473a7344419b15c4219cc2b6c64c6f',
    ],
    26 =>
    [
    'id' => 30,
    'parent_id' => 9,
    'lft' => 73,
    'rght' => 82,
    'plugin' => 'User',
    'alias' => 'Roles',
    'alias_hash' => 'a5cd3ed116608dac017f14c046ea56bf',
    ],
    27 =>
    [
    'id' => 31,
    'parent_id' => 30,
    'lft' => 74,
    'rght' => 75,
    'plugin' => 'User',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    28 =>
    [
    'id' => 32,
    'parent_id' => 30,
    'lft' => 76,
    'rght' => 77,
    'plugin' => 'User',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    29 =>
    [
    'id' => 33,
    'parent_id' => 30,
    'lft' => 78,
    'rght' => 79,
    'plugin' => 'User',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    30 =>
    [
    'id' => 34,
    'parent_id' => 30,
    'lft' => 80,
    'rght' => 81,
    'plugin' => 'User',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    31 =>
    [
    'id' => 35,
    'parent_id' => null,
    'lft' => 91,
    'rght' => 122,
    'plugin' => 'Taxonomy',
    'alias' => 'Taxonomy',
    'alias_hash' => '30d10883c017c4fd6751c8982e20dae1',
    ],
    32 =>
    [
    'id' => 36,
    'parent_id' => 35,
    'lft' => 92,
    'rght' => 121,
    'plugin' => 'Taxonomy',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    33 =>
    [
    'id' => 37,
    'parent_id' => 36,
    'lft' => 93,
    'rght' => 96,
    'plugin' => 'Taxonomy',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    34 =>
    [
    'id' => 38,
    'parent_id' => 37,
    'lft' => 94,
    'rght' => 95,
    'plugin' => 'Taxonomy',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    35 =>
    [
    'id' => 39,
    'parent_id' => 36,
    'lft' => 97,
    'rght' => 100,
    'plugin' => 'Taxonomy',
    'alias' => 'Tagger',
    'alias_hash' => 'e34d9224f0bf63992e1e77451c6976d1',
    ],
    36 =>
    [
    'id' => 40,
    'parent_id' => 39,
    'lft' => 98,
    'rght' => 99,
    'plugin' => 'Taxonomy',
    'alias' => 'search',
    'alias_hash' => '06a943c59f33a34bb5924aaf72cd2995',
    ],
    37 =>
    [
    'id' => 41,
    'parent_id' => 36,
    'lft' => 101,
    'rght' => 110,
    'plugin' => 'Taxonomy',
    'alias' => 'Terms',
    'alias_hash' => '6f1bf85c9ebb3c7fa26251e1e335e032',
    ],
    38 =>
    [
    'id' => 42,
    'parent_id' => 41,
    'lft' => 102,
    'rght' => 103,
    'plugin' => 'Taxonomy',
    'alias' => 'vocabulary',
    'alias_hash' => '09f06963f502addfeab2a7c87f38802e',
    ],
    39 =>
    [
    'id' => 43,
    'parent_id' => 41,
    'lft' => 104,
    'rght' => 105,
    'plugin' => 'Taxonomy',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    40 =>
    [
    'id' => 44,
    'parent_id' => 41,
    'lft' => 106,
    'rght' => 107,
    'plugin' => 'Taxonomy',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    41 =>
    [
    'id' => 45,
    'parent_id' => 41,
    'lft' => 108,
    'rght' => 109,
    'plugin' => 'Taxonomy',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    42 =>
    [
    'id' => 46,
    'parent_id' => 36,
    'lft' => 111,
    'rght' => 120,
    'plugin' => 'Taxonomy',
    'alias' => 'Vocabularies',
    'alias_hash' => '81a419751eb59e7d35acab8e532d59a7',
    ],
    43 =>
    [
    'id' => 47,
    'parent_id' => 46,
    'lft' => 112,
    'rght' => 113,
    'plugin' => 'Taxonomy',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    44 =>
    [
    'id' => 48,
    'parent_id' => 46,
    'lft' => 114,
    'rght' => 115,
    'plugin' => 'Taxonomy',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    45 =>
    [
    'id' => 49,
    'parent_id' => 46,
    'lft' => 116,
    'rght' => 117,
    'plugin' => 'Taxonomy',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    46 =>
    [
    'id' => 50,
    'parent_id' => 46,
    'lft' => 118,
    'rght' => 119,
    'plugin' => 'Taxonomy',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    47 =>
    [
    'id' => 51,
    'parent_id' => null,
    'lft' => 123,
    'rght' => 174,
    'plugin' => 'System',
    'alias' => 'System',
    'alias_hash' => 'a45da96d0bf6575970f2d27af22be28a',
    ],
    48 =>
    [
    'id' => 52,
    'parent_id' => 51,
    'lft' => 124,
    'rght' => 173,
    'plugin' => 'System',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    49 =>
    [
    'id' => 53,
    'parent_id' => 52,
    'lft' => 125,
    'rght' => 128,
    'plugin' => 'System',
    'alias' => 'Configuration',
    'alias_hash' => '254f642527b45bc260048e30704edb39',
    ],
    50 =>
    [
    'id' => 54,
    'parent_id' => 53,
    'lft' => 126,
    'rght' => 127,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    51 =>
    [
    'id' => 55,
    'parent_id' => 52,
    'lft' => 129,
    'rght' => 132,
    'plugin' => 'System',
    'alias' => 'Dashboard',
    'alias_hash' => '2938c7f7e560ed972f8a4f68e80ff834',
    ],
    52 =>
    [
    'id' => 56,
    'parent_id' => 55,
    'lft' => 130,
    'rght' => 131,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    53 =>
    [
    'id' => 57,
    'parent_id' => 52,
    'lft' => 133,
    'rght' => 138,
    'plugin' => 'System',
    'alias' => 'Help',
    'alias_hash' => '6a26f548831e6a8c26bfbbd9f6ec61e0',
    ],
    54 =>
    [
    'id' => 58,
    'parent_id' => 57,
    'lft' => 134,
    'rght' => 135,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    55 =>
    [
    'id' => 59,
    'parent_id' => 57,
    'lft' => 136,
    'rght' => 137,
    'plugin' => 'System',
    'alias' => 'about',
    'alias_hash' => '46b3931b9959c927df4fc65fdee94b07',
    ],
    56 =>
    [
    'id' => 60,
    'parent_id' => 52,
    'lft' => 139,
    'rght' => 152,
    'plugin' => 'System',
    'alias' => 'Plugins',
    'alias_hash' => 'bb38096ab39160dc20d44f3ea6b44507',
    ],
    57 =>
    [
    'id' => 61,
    'parent_id' => 60,
    'lft' => 140,
    'rght' => 141,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    58 =>
    [
    'id' => 62,
    'parent_id' => 60,
    'lft' => 142,
    'rght' => 143,
    'plugin' => 'System',
    'alias' => 'install',
    'alias_hash' => '19ad89bc3e3c9d7ef68b89523eff1987',
    ],
    59 =>
    [
    'id' => 63,
    'parent_id' => 60,
    'lft' => 144,
    'rght' => 145,
    'plugin' => 'System',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    60 =>
    [
    'id' => 64,
    'parent_id' => 60,
    'lft' => 146,
    'rght' => 147,
    'plugin' => 'System',
    'alias' => 'enable',
    'alias_hash' => '208f156d4a803025c284bb595a7576b4',
    ],
    61 =>
    [
    'id' => 65,
    'parent_id' => 60,
    'lft' => 148,
    'rght' => 149,
    'plugin' => 'System',
    'alias' => 'disable',
    'alias_hash' => '0aaa87422396fdd678498793b6d5250e',
    ],
    62 =>
    [
    'id' => 66,
    'parent_id' => 60,
    'lft' => 150,
    'rght' => 151,
    'plugin' => 'System',
    'alias' => 'settings',
    'alias_hash' => '2e5d8aa3dfa8ef34ca5131d20f9dad51',
    ],
    63 =>
    [
    'id' => 67,
    'parent_id' => 52,
    'lft' => 153,
    'rght' => 156,
    'plugin' => 'System',
    'alias' => 'Structure',
    'alias_hash' => 'dc4c71563b9bc39a65be853457e6b7b6',
    ],
    64 =>
    [
    'id' => 68,
    'parent_id' => 67,
    'lft' => 154,
    'rght' => 155,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    65 =>
    [
    'id' => 69,
    'parent_id' => 52,
    'lft' => 157,
    'rght' => 172,
    'plugin' => 'System',
    'alias' => 'Themes',
    'alias_hash' => '83915d1254927f41241e8630890bec6e',
    ],
    66 =>
    [
    'id' => 70,
    'parent_id' => 69,
    'lft' => 158,
    'rght' => 159,
    'plugin' => 'System',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    67 =>
    [
    'id' => 71,
    'parent_id' => 69,
    'lft' => 160,
    'rght' => 161,
    'plugin' => 'System',
    'alias' => 'install',
    'alias_hash' => '19ad89bc3e3c9d7ef68b89523eff1987',
    ],
    68 =>
    [
    'id' => 72,
    'parent_id' => 69,
    'lft' => 162,
    'rght' => 163,
    'plugin' => 'System',
    'alias' => 'uninstall',
    'alias_hash' => 'fe98497efedbe156ecc4b953aea77e07',
    ],
    69 =>
    [
    'id' => 73,
    'parent_id' => 69,
    'lft' => 164,
    'rght' => 165,
    'plugin' => 'System',
    'alias' => 'activate',
    'alias_hash' => 'd4ee0fbbeb7ffd4fd7a7d477a7ecd922',
    ],
    70 =>
    [
    'id' => 74,
    'parent_id' => 69,
    'lft' => 166,
    'rght' => 167,
    'plugin' => 'System',
    'alias' => 'details',
    'alias_hash' => '27792947ed5d5da7c0d1f43327ed9dab',
    ],
    71 =>
    [
    'id' => 75,
    'parent_id' => 69,
    'lft' => 168,
    'rght' => 169,
    'plugin' => 'System',
    'alias' => 'screenshot',
    'alias_hash' => '62c92ba585f74ecdbef4c4498a438984',
    ],
    72 =>
    [
    'id' => 76,
    'parent_id' => 69,
    'lft' => 170,
    'rght' => 171,
    'plugin' => 'System',
    'alias' => 'settings',
    'alias_hash' => '2e5d8aa3dfa8ef34ca5131d20f9dad51',
    ],
    73 =>
    [
    'id' => 77,
    'parent_id' => null,
    'lft' => 175,
    'rght' => 246,
    'plugin' => 'Node',
    'alias' => 'Node',
    'alias_hash' => '6c3a6944a808a7c0bbb6788dbec54a9f',
    ],
    74 =>
    [
    'id' => 78,
    'parent_id' => 77,
    'lft' => 176,
    'rght' => 187,
    'plugin' => 'Node',
    'alias' => 'Serve',
    'alias_hash' => 'bc9a5b9e9259199a79f67ded0b508dfc',
    ],
    75 =>
    [
    'id' => 79,
    'parent_id' => 78,
    'lft' => 177,
    'rght' => 178,
    'plugin' => 'Node',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    76 =>
    [
    'id' => 80,
    'parent_id' => 78,
    'lft' => 179,
    'rght' => 180,
    'plugin' => 'Node',
    'alias' => 'home',
    'alias_hash' => '106a6c241b8797f52e1e77317b96a201',
    ],
    77 =>
    [
    'id' => 81,
    'parent_id' => 78,
    'lft' => 181,
    'rght' => 182,
    'plugin' => 'Node',
    'alias' => 'details',
    'alias_hash' => '27792947ed5d5da7c0d1f43327ed9dab',
    ],
    78 =>
    [
    'id' => 82,
    'parent_id' => 78,
    'lft' => 183,
    'rght' => 184,
    'plugin' => 'Node',
    'alias' => 'search',
    'alias_hash' => '06a943c59f33a34bb5924aaf72cd2995',
    ],
    79 =>
    [
    'id' => 83,
    'parent_id' => 78,
    'lft' => 185,
    'rght' => 186,
    'plugin' => 'Node',
    'alias' => 'rss',
    'alias_hash' => '8bb856027f758e85ddf2085c98ae2908',
    ],
    80 =>
    [
    'id' => 84,
    'parent_id' => 77,
    'lft' => 188,
    'rght' => 245,
    'plugin' => 'Node',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    81 =>
    [
    'id' => 85,
    'parent_id' => 84,
    'lft' => 189,
    'rght' => 200,
    'plugin' => 'Node',
    'alias' => 'Comments',
    'alias_hash' => '8413c683b4b27cc3f4dbd4c90329d8ba',
    ],
    82 =>
    [
    'id' => 86,
    'parent_id' => 85,
    'lft' => 190,
    'rght' => 191,
    'plugin' => 'Node',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    83 =>
    [
    'id' => 87,
    'parent_id' => 85,
    'lft' => 192,
    'rght' => 193,
    'plugin' => 'Node',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    84 =>
    [
    'id' => 88,
    'parent_id' => 85,
    'lft' => 194,
    'rght' => 195,
    'plugin' => 'Node',
    'alias' => 'status',
    'alias_hash' => '9acb44549b41563697bb490144ec6258',
    ],
    85 =>
    [
    'id' => 89,
    'parent_id' => 85,
    'lft' => 196,
    'rght' => 197,
    'plugin' => 'Node',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    86 =>
    [
    'id' => 91,
    'parent_id' => 84,
    'lft' => 201,
    'rght' => 218,
    'plugin' => 'Node',
    'alias' => 'Fields',
    'alias_hash' => 'a4ca5edd20d0b5d502ebece575681f58',
    ],
    87 =>
    [
    'id' => 92,
    'parent_id' => 91,
    'lft' => 202,
    'rght' => 203,
    'plugin' => 'Node',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    88 =>
    [
    'id' => 93,
    'parent_id' => 91,
    'lft' => 204,
    'rght' => 205,
    'plugin' => 'Node',
    'alias' => 'configure',
    'alias_hash' => 'e2d5a00791bce9a01f99bc6fd613a39d',
    ],
    89 =>
    [
    'id' => 94,
    'parent_id' => 91,
    'lft' => 206,
    'rght' => 207,
    'plugin' => 'Node',
    'alias' => 'attach',
    'alias_hash' => '915e375d95d78bf040a2e054caadfb56',
    ],
    90 =>
    [
    'id' => 95,
    'parent_id' => 91,
    'lft' => 208,
    'rght' => 209,
    'plugin' => 'Node',
    'alias' => 'detach',
    'alias_hash' => 'b6bc015ea9587c510c9017988e94e60d',
    ],
    91 =>
    [
    'id' => 99,
    'parent_id' => 91,
    'lft' => 210,
    'rght' => 211,
    'plugin' => 'Node',
    'alias' => 'move',
    'alias_hash' => '3734a903022249b3010be1897042568e',
    ],
    92 =>
    [
    'id' => 100,
    'parent_id' => 84,
    'lft' => 219,
    'rght' => 234,
    'plugin' => 'Node',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    93 =>
    [
    'id' => 101,
    'parent_id' => 100,
    'lft' => 220,
    'rght' => 221,
    'plugin' => 'Node',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    94 =>
    [
    'id' => 102,
    'parent_id' => 100,
    'lft' => 222,
    'rght' => 223,
    'plugin' => 'Node',
    'alias' => 'create',
    'alias_hash' => '76ea0bebb3c22822b4f0dd9c9fd021c5',
    ],
    95 =>
    [
    'id' => 103,
    'parent_id' => 100,
    'lft' => 224,
    'rght' => 225,
    'plugin' => 'Node',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    96 =>
    [
    'id' => 104,
    'parent_id' => 100,
    'lft' => 226,
    'rght' => 227,
    'plugin' => 'Node',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    97 =>
    [
    'id' => 105,
    'parent_id' => 100,
    'lft' => 228,
    'rght' => 229,
    'plugin' => 'Node',
    'alias' => 'translate',
    'alias_hash' => 'fc46e26a907870744758b76166150f62',
    ],
    98 =>
    [
    'id' => 106,
    'parent_id' => 100,
    'lft' => 230,
    'rght' => 231,
    'plugin' => 'Node',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    99 =>
    [
    'id' => 108,
    'parent_id' => 84,
    'lft' => 235,
    'rght' => 244,
    'plugin' => 'Node',
    'alias' => 'Types',
    'alias_hash' => 'f2d346b1bb7c1c85ab6f7f21e3666b9f',
    ],
    100 =>
    [
    'id' => 109,
    'parent_id' => 108,
    'lft' => 236,
    'rght' => 237,
    'plugin' => 'Node',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    101 =>
    [
    'id' => 110,
    'parent_id' => 108,
    'lft' => 238,
    'rght' => 239,
    'plugin' => 'Node',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    102 =>
    [
    'id' => 111,
    'parent_id' => 108,
    'lft' => 240,
    'rght' => 241,
    'plugin' => 'Node',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    103 =>
    [
    'id' => 112,
    'parent_id' => 108,
    'lft' => 242,
    'rght' => 243,
    'plugin' => 'Node',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    104 =>
    [
    'id' => 113,
    'parent_id' => null,
    'lft' => 247,
    'rght' => 270,
    'plugin' => 'Menu',
    'alias' => 'Menu',
    'alias_hash' => 'b61541208db7fa7dba42c85224405911',
    ],
    105 =>
    [
    'id' => 114,
    'parent_id' => 113,
    'lft' => 248,
    'rght' => 269,
    'plugin' => 'Menu',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    106 =>
    [
    'id' => 115,
    'parent_id' => 114,
    'lft' => 249,
    'rght' => 258,
    'plugin' => 'Menu',
    'alias' => 'Links',
    'alias_hash' => 'bd908db5ccb07777ced8023dffc802f4',
    ],
    107 =>
    [
    'id' => 116,
    'parent_id' => 115,
    'lft' => 250,
    'rght' => 251,
    'plugin' => 'Menu',
    'alias' => 'menu',
    'alias_hash' => '8d6ab84ca2af9fccd4e4048694176ebf',
    ],
    108 =>
    [
    'id' => 117,
    'parent_id' => 115,
    'lft' => 252,
    'rght' => 253,
    'plugin' => 'Menu',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    109 =>
    [
    'id' => 118,
    'parent_id' => 115,
    'lft' => 254,
    'rght' => 255,
    'plugin' => 'Menu',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    110 =>
    [
    'id' => 119,
    'parent_id' => 115,
    'lft' => 256,
    'rght' => 257,
    'plugin' => 'Menu',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    111 =>
    [
    'id' => 120,
    'parent_id' => 114,
    'lft' => 259,
    'rght' => 268,
    'plugin' => 'Menu',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    112 =>
    [
    'id' => 121,
    'parent_id' => 120,
    'lft' => 260,
    'rght' => 261,
    'plugin' => 'Menu',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    113 =>
    [
    'id' => 122,
    'parent_id' => 120,
    'lft' => 262,
    'rght' => 263,
    'plugin' => 'Menu',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    114 =>
    [
    'id' => 123,
    'parent_id' => 120,
    'lft' => 264,
    'rght' => 265,
    'plugin' => 'Menu',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    115 =>
    [
    'id' => 124,
    'parent_id' => 120,
    'lft' => 266,
    'rght' => 267,
    'plugin' => 'Menu',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    116 =>
    [
    'id' => 125,
    'parent_id' => null,
    'lft' => 271,
    'rght' => 292,
    'plugin' => 'Locale',
    'alias' => 'Locale',
    'alias_hash' => '911f0f24bdce6808f4614d6a263b143b',
    ],
    117 =>
    [
    'id' => 126,
    'parent_id' => 125,
    'lft' => 272,
    'rght' => 291,
    'plugin' => 'Locale',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    118 =>
    [
    'id' => 127,
    'parent_id' => 126,
    'lft' => 273,
    'rght' => 290,
    'plugin' => 'Locale',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    119 =>
    [
    'id' => 128,
    'parent_id' => 127,
    'lft' => 274,
    'rght' => 275,
    'plugin' => 'Locale',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    120 =>
    [
    'id' => 129,
    'parent_id' => 127,
    'lft' => 276,
    'rght' => 277,
    'plugin' => 'Locale',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    121 =>
    [
    'id' => 130,
    'parent_id' => 127,
    'lft' => 278,
    'rght' => 279,
    'plugin' => 'Locale',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    122 =>
    [
    'id' => 132,
    'parent_id' => 127,
    'lft' => 280,
    'rght' => 281,
    'plugin' => 'Locale',
    'alias' => 'move',
    'alias_hash' => '3734a903022249b3010be1897042568e',
    ],
    123 =>
    [
    'id' => 133,
    'parent_id' => 127,
    'lft' => 282,
    'rght' => 283,
    'plugin' => 'Locale',
    'alias' => 'enable',
    'alias_hash' => '208f156d4a803025c284bb595a7576b4',
    ],
    124 =>
    [
    'id' => 134,
    'parent_id' => 127,
    'lft' => 284,
    'rght' => 285,
    'plugin' => 'Locale',
    'alias' => 'disable',
    'alias_hash' => '0aaa87422396fdd678498793b6d5250e',
    ],
    125 =>
    [
    'id' => 135,
    'parent_id' => 127,
    'lft' => 286,
    'rght' => 287,
    'plugin' => 'Locale',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    126 =>
    [
    'id' => 136,
    'parent_id' => null,
    'lft' => 293,
    'rght' => 310,
    'plugin' => 'Installer',
    'alias' => 'Installer',
    'alias_hash' => 'd1be377656960ed04f1564da21d80c8d',
    ],
    127 =>
    [
    'id' => 137,
    'parent_id' => 136,
    'lft' => 294,
    'rght' => 309,
    'plugin' => 'Installer',
    'alias' => 'Startup',
    'alias_hash' => '13e685964c2548aa748f7ea263bad4e5',
    ],
    128 =>
    [
    'id' => 138,
    'parent_id' => 137,
    'lft' => 295,
    'rght' => 296,
    'plugin' => 'Installer',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    129 =>
    [
    'id' => 139,
    'parent_id' => 137,
    'lft' => 297,
    'rght' => 298,
    'plugin' => 'Installer',
    'alias' => 'language',
    'alias_hash' => '8512ae7d57b1396273f76fe6ed341a23',
    ],
    130 =>
    [
    'id' => 140,
    'parent_id' => 137,
    'lft' => 299,
    'rght' => 300,
    'plugin' => 'Installer',
    'alias' => 'requirements',
    'alias_hash' => 'b4851e92b19af0c5c82447fc0937709d',
    ],
    131 =>
    [
    'id' => 141,
    'parent_id' => 137,
    'lft' => 301,
    'rght' => 302,
    'plugin' => 'Installer',
    'alias' => 'license',
    'alias_hash' => '718779752b851ac0dc6281a8c8d77e7e',
    ],
    132 =>
    [
    'id' => 142,
    'parent_id' => 137,
    'lft' => 303,
    'rght' => 304,
    'plugin' => 'Installer',
    'alias' => 'database',
    'alias_hash' => '11e0eed8d3696c0a632f822df385ab3c',
    ],
    133 =>
    [
    'id' => 143,
    'parent_id' => 137,
    'lft' => 305,
    'rght' => 306,
    'plugin' => 'Installer',
    'alias' => 'account',
    'alias_hash' => 'e268443e43d93dab7ebef303bbe9642f',
    ],
    134 =>
    [
    'id' => 144,
    'parent_id' => 137,
    'lft' => 307,
    'rght' => 308,
    'plugin' => 'Installer',
    'alias' => 'finish',
    'alias_hash' => '3248bc7547ce97b2a197b2a06cf7283d',
    ],
    135 =>
    [
    'id' => 145,
    'parent_id' => null,
    'lft' => 311,
    'rght' => 326,
    'plugin' => 'Block',
    'alias' => 'Block',
    'alias_hash' => 'e1e4c8c9ccd9fc39c391da4bcd093fb2',
    ],
    136 =>
    [
    'id' => 146,
    'parent_id' => 145,
    'lft' => 312,
    'rght' => 325,
    'plugin' => 'Block',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    137 =>
    [
    'id' => 147,
    'parent_id' => 146,
    'lft' => 313,
    'rght' => 324,
    'plugin' => 'Block',
    'alias' => 'Manage',
    'alias_hash' => '34e34c43ec6b943c10a3cc1a1a16fb11',
    ],
    138 =>
    [
    'id' => 148,
    'parent_id' => 147,
    'lft' => 314,
    'rght' => 315,
    'plugin' => 'Block',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    139 =>
    [
    'id' => 149,
    'parent_id' => 147,
    'lft' => 316,
    'rght' => 317,
    'plugin' => 'Block',
    'alias' => 'add',
    'alias_hash' => '34ec78fcc91ffb1e54cd85e4a0924332',
    ],
    140 =>
    [
    'id' => 150,
    'parent_id' => 147,
    'lft' => 318,
    'rght' => 319,
    'plugin' => 'Block',
    'alias' => 'edit',
    'alias_hash' => 'de95b43bceeb4b998aed4aed5cef1ae7',
    ],
    141 =>
    [
    'id' => 151,
    'parent_id' => 147,
    'lft' => 320,
    'rght' => 321,
    'plugin' => 'Block',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    142 =>
    [
    'id' => 152,
    'parent_id' => 147,
    'lft' => 322,
    'rght' => 323,
    'plugin' => 'Block',
    'alias' => 'duplicate',
    'alias_hash' => '24f1b0a79473250c195c7fb84e393392',
    ],
    143 =>
    [
    'id' => 153,
    'parent_id' => null,
    'lft' => 327,
    'rght' => 334,
    'plugin' => 'Wysiwyg',
    'alias' => 'Wysiwyg',
    'alias_hash' => 'fcb1d5c3299a281fbb55851547dfac9e',
    ],
    144 =>
    [
    'id' => 154,
    'parent_id' => 153,
    'lft' => 328,
    'rght' => 333,
    'plugin' => 'Wysiwyg',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    145 =>
    [
    'id' => 155,
    'parent_id' => 154,
    'lft' => 329,
    'rght' => 332,
    'plugin' => 'Wysiwyg',
    'alias' => 'Finder',
    'alias_hash' => 'd151508da8d36994e1635f7875594424',
    ],
    146 =>
    [
    'id' => 156,
    'parent_id' => 155,
    'lft' => 330,
    'rght' => 331,
    'plugin' => 'Wysiwyg',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    147 =>
    [
    'id' => 159,
    'parent_id' => null,
    'lft' => 335,
    'rght' => 346,
    'plugin' => 'Field',
    'alias' => 'Field',
    'alias_hash' => '6f16a5f8ff5d75ab84c018adacdfcbb7',
    ],
    148 =>
    [
    'id' => 160,
    'parent_id' => 159,
    'lft' => 336,
    'rght' => 341,
    'plugin' => 'Field',
    'alias' => 'FileHandler',
    'alias_hash' => 'd3d5308974962037be1ce87e7b7bbfe2',
    ],
    149 =>
    [
    'id' => 161,
    'parent_id' => 160,
    'lft' => 337,
    'rght' => 338,
    'plugin' => 'Field',
    'alias' => 'upload',
    'alias_hash' => '76ee3de97a1b8b903319b7c013d8c877',
    ],
    150 =>
    [
    'id' => 162,
    'parent_id' => 160,
    'lft' => 339,
    'rght' => 340,
    'plugin' => 'Field',
    'alias' => 'delete',
    'alias_hash' => '099af53f601532dbd31e0ea99ffdeb64',
    ],
    151 =>
    [
    'id' => 163,
    'parent_id' => 159,
    'lft' => 342,
    'rght' => 345,
    'plugin' => 'Field',
    'alias' => 'ImageHandler',
    'alias_hash' => '0f6984d93393387e52ea884c7ca0fd93',
    ],
    152 =>
    [
    'id' => 164,
    'parent_id' => 163,
    'lft' => 343,
    'rght' => 344,
    'plugin' => 'Field',
    'alias' => 'thumbnail',
    'alias_hash' => '951d4dff3c22e9fcc4a2707009f45ea8',
    ],
    153 =>
    [
    'id' => 165,
    'parent_id' => 127,
    'lft' => 288,
    'rght' => 289,
    'plugin' => 'Locale',
    'alias' => 'setDefault',
    'alias_hash' => 'd16b26f218cfb8cde187e3b95a78813c',
    ],
    154 =>
    [
    'id' => 166,
    'parent_id' => 85,
    'lft' => 198,
    'rght' => 199,
    'plugin' => 'Node',
    'alias' => 'emptyTrash',
    'alias_hash' => 'b13b982da42afc395fd5f9ad46381e23',
    ],
    155 =>
    [
    'id' => 167,
    'parent_id' => 91,
    'lft' => 212,
    'rght' => 213,
    'plugin' => 'Node',
    'alias' => 'viewModeList',
    'alias_hash' => 'f6730c40f9e93768852502275e0c9ed5',
    ],
    156 =>
    [
    'id' => 168,
    'parent_id' => 91,
    'lft' => 214,
    'rght' => 215,
    'plugin' => 'Node',
    'alias' => 'viewModeEdit',
    'alias_hash' => 'ecb551e7896f4007ed8df082a8184878',
    ],
    157 =>
    [
    'id' => 169,
    'parent_id' => 91,
    'lft' => 216,
    'rght' => 217,
    'plugin' => 'Node',
    'alias' => 'viewModeMove',
    'alias_hash' => '5a2933f9feebe723793d21c183be08c6',
    ],
    158 =>
    [
    'id' => 170,
    'parent_id' => 100,
    'lft' => 232,
    'rght' => 233,
    'plugin' => 'Node',
    'alias' => 'deleteRevision',
    'alias_hash' => '0049d291ee36657bd271c65979383af3',
    ],
    159 =>
    [
    'id' => 171,
    'parent_id' => 2,
    'lft' => 15,
    'rght' => 16,
    'plugin' => 'User',
    'alias' => 'cancelRequest',
    'alias_hash' => 'd101217dd06f14b4a695fca3b2407320',
    ],
    160 =>
    [
    'id' => 172,
    'parent_id' => 2,
    'lft' => 17,
    'rght' => 18,
    'plugin' => 'User',
    'alias' => 'cancel',
    'alias_hash' => '10aec35353f9c4096a71c38654c3d402',
    ],
    161 =>
    [
    'id' => 173,
    'parent_id' => 2,
    'lft' => 19,
    'rght' => 20,
    'plugin' => 'User',
    'alias' => 'register',
    'alias_hash' => '9de4a97425678c5b1288aa70c1669a64',
    ],
    162 =>
    [
    'id' => 174,
    'parent_id' => 2,
    'lft' => 21,
    'rght' => 22,
    'plugin' => 'User',
    'alias' => 'activationEmail',
    'alias_hash' => '86b62e721d1fb2f94f296bda930ffd34',
    ],
    163 =>
    [
    'id' => 175,
    'parent_id' => 2,
    'lft' => 23,
    'rght' => 24,
    'plugin' => 'User',
    'alias' => 'activate',
    'alias_hash' => 'd4ee0fbbeb7ffd4fd7a7d477a7ecd922',
    ],
    164 =>
    [
    'id' => 176,
    'parent_id' => 10,
    'lft' => 38,
    'rght' => 39,
    'plugin' => 'User',
    'alias' => 'viewModeList',
    'alias_hash' => 'f6730c40f9e93768852502275e0c9ed5',
    ],
    165 =>
    [
    'id' => 177,
    'parent_id' => 10,
    'lft' => 40,
    'rght' => 41,
    'plugin' => 'User',
    'alias' => 'viewModeEdit',
    'alias_hash' => 'ecb551e7896f4007ed8df082a8184878',
    ],
    166 =>
    [
    'id' => 178,
    'parent_id' => 10,
    'lft' => 42,
    'rght' => 43,
    'plugin' => 'User',
    'alias' => 'viewModeMove',
    'alias_hash' => '5a2933f9feebe723793d21c183be08c6',
    ],
    167 =>
    [
    'id' => 179,
    'parent_id' => 9,
    'lft' => 83,
    'rght' => 88,
    'plugin' => 'User',
    'alias' => 'Gateway',
    'alias_hash' => '926dec9494209cb088b4962509df1a91',
    ],
    168 =>
    [
    'id' => 180,
    'parent_id' => 179,
    'lft' => 84,
    'rght' => 85,
    'plugin' => 'User',
    'alias' => 'login',
    'alias_hash' => 'd56b699830e77ba53855679cb1d252da',
    ],
    169 =>
    [
    'id' => 181,
    'parent_id' => 179,
    'lft' => 86,
    'rght' => 87,
    'plugin' => 'User',
    'alias' => 'logout',
    'alias_hash' => '4236a440a662cc8253d7536e5aa17942',
    ],
    170 =>
    [
    'id' => 182,
    'parent_id' => 19,
    'lft' => 54,
    'rght' => 55,
    'plugin' => 'User',
    'alias' => 'block',
    'alias_hash' => '14511f2f5564650d129ca7cabc333278',
    ],
    171 =>
    [
    'id' => 183,
    'parent_id' => 19,
    'lft' => 56,
    'rght' => 57,
    'plugin' => 'User',
    'alias' => 'activate',
    'alias_hash' => 'd4ee0fbbeb7ffd4fd7a7d477a7ecd922',
    ],
    172 =>
    [
    'id' => 184,
    'parent_id' => 19,
    'lft' => 58,
    'rght' => 59,
    'plugin' => 'User',
    'alias' => 'passwordInstructions',
    'alias_hash' => '1aae12034ad9d692f6802c1721cc622f',
    ],
    173 =>
    [
    'id' => 186,
    'parent_id' => null,
    'lft' => 347,
    'rght' => 358,
    'plugin' => 'MediaManager',
    'alias' => 'MediaManager',
    'alias_hash' => 'ce0a1f03091160e6528b72e9f9ea7eff',
    ],
    174 =>
    [
    'id' => 187,
    'parent_id' => 186,
    'lft' => 348,
    'rght' => 357,
    'plugin' => 'MediaManager',
    'alias' => 'Admin',
    'alias_hash' => 'e3afed0047b08059d0fada10f400c1e5',
    ],
    175 =>
    [
    'id' => 188,
    'parent_id' => 187,
    'lft' => 349,
    'rght' => 356,
    'plugin' => 'MediaManager',
    'alias' => 'Explorer',
    'alias_hash' => '94fbbf67e0c8cea8cbaff55287746f3e',
    ],
    176 =>
    [
    'id' => 189,
    'parent_id' => 188,
    'lft' => 350,
    'rght' => 351,
    'plugin' => 'MediaManager',
    'alias' => 'index',
    'alias_hash' => '6a992d5529f459a44fee58c733255e86',
    ],
    177 =>
    [
    'id' => 190,
    'parent_id' => 188,
    'lft' => 352,
    'rght' => 353,
    'plugin' => 'MediaManager',
    'alias' => 'connector',
    'alias_hash' => '266e0d3d29830abfe7d4ed98b47966f7',
    ],
    178 =>
    [
    'id' => 191,
    'parent_id' => 188,
    'lft' => 354,
    'rght' => 355,
    'plugin' => 'MediaManager',
    'alias' => 'pluginFile',
    'alias_hash' => 'a840980787c4260a4a710f753641a8c6',
    ],
    ];
}
