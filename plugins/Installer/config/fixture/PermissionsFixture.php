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

class PermissionsFixture
{

    public $table = 'permissions';

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
    'aco_id' =>
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
    'role_id' =>
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
    ];

    public $records = [
    0 =>
    [
    'id' => 1,
    'aco_id' => 141,
    'role_id' => 2,
    ],
    1 =>
    [
    'id' => 2,
    'aco_id' => 141,
    'role_id' => 3,
    ],
    2 =>
    [
    'id' => 3,
    'aco_id' => 143,
    'role_id' => 2,
    ],
    3 =>
    [
    'id' => 4,
    'aco_id' => 142,
    'role_id' => 3,
    ],
    4 =>
    [
    'id' => 5,
    'aco_id' => 133,
    'role_id' => 3,
    ],
    5 =>
    [
    'id' => 6,
    'aco_id' => 134,
    'role_id' => 2,
    ],
    6 =>
    [
    'id' => 7,
    'aco_id' => 134,
    'role_id' => 3,
    ],
    7 =>
    [
    'id' => 8,
    'aco_id' => 135,
    'role_id' => 2,
    ],
    8 =>
    [
    'id' => 9,
    'aco_id' => 135,
    'role_id' => 3,
    ],
    9 =>
    [
    'id' => 10,
    'aco_id' => 136,
    'role_id' => 2,
    ],
    10 =>
    [
    'id' => 11,
    'aco_id' => 136,
    'role_id' => 3,
    ],
    11 =>
    [
    'id' => 12,
    'aco_id' => 137,
    'role_id' => 2,
    ],
    12 =>
    [
    'id' => 13,
    'aco_id' => 137,
    'role_id' => 3,
    ],
    13 =>
    [
    'id' => 14,
    'aco_id' => 138,
    'role_id' => 2,
    ],
    14 =>
    [
    'id' => 15,
    'aco_id' => 138,
    'role_id' => 3,
    ],
    15 =>
    [
    'id' => 16,
    'aco_id' => 139,
    'role_id' => 2,
    ],
    16 =>
    [
    'id' => 17,
    'aco_id' => 139,
    'role_id' => 3,
    ],
    17 =>
    [
    'id' => 18,
    'aco_id' => 140,
    'role_id' => 2,
    ],
    18 =>
    [
    'id' => 19,
    'aco_id' => 11,
    'role_id' => 2,
    ],
    19 =>
    [
    'id' => 20,
    'aco_id' => 11,
    'role_id' => 3,
    ],
    20 =>
    [
    'id' => 21,
    'aco_id' => 12,
    'role_id' => 2,
    ],
    21 =>
    [
    'id' => 22,
    'aco_id' => 12,
    'role_id' => 3,
    ],
    22 =>
    [
    'id' => 23,
    'aco_id' => 13,
    'role_id' => 2,
    ],
    23 =>
    [
    'id' => 24,
    'aco_id' => 13,
    'role_id' => 3,
    ],
    24 =>
    [
    'id' => 25,
    'aco_id' => 14,
    'role_id' => 2,
    ],
    25 =>
    [
    'id' => 26,
    'aco_id' => 14,
    'role_id' => 3,
    ],
    26 =>
    [
    'id' => 27,
    'aco_id' => 15,
    'role_id' => 2,
    ],
    27 =>
    [
    'id' => 28,
    'aco_id' => 15,
    'role_id' => 3,
    ],
    ];
}
