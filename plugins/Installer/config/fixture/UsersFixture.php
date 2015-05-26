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

class UsersFixture
{

    /**
     * Table name.
     *
     * @var string
     */
    public $table = 'users';

    /**
     * Table columns.
     *
     * @var array
     */
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
    'users_username' =>
    [
      'type' => 'unique',
      'columns' =>
      [
        0 => 'username',
        1 => 'email',
      ],
      'length' =>
      [
      ],
    ],
    ],
    '_indexes' =>
    [
    'users_email_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'email',
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
    'name' =>
    [
    'type' => 'string',
    'length' => 150,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'username' =>
    [
    'type' => 'string',
    'length' => 80,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'password' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'email' =>
    [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'web' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'locale' =>
    [
    'type' => 'string',
    'length' => 5,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'public_profile' =>
    [
    'type' => 'boolean',
    'length' => null,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    'public_email' =>
    [
    'type' => 'boolean',
    'length' => null,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    'token' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => null,
    'comment' => 'random unique code, used for pass recovery',
    'precision' => null,
    'fixed' => null,
    ],
    'token_expiration' =>
    [
    'type' => 'datetime',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => 'expiration date of user token',
    'precision' => null,
    ],
    'status' =>
    [
    'type' => 'boolean',
    'length' => null,
    'null' => false,
    'default' => '1',
    'comment' => '',
    'precision' => null,
    ],
    'last_login' =>
    [
    'type' => 'datetime',
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
    ];

    /**
     * Table records.
     *
     * @var array
     */
    public $records = [];
}
