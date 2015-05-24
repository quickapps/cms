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

class CommentsFixture
{

    public $table = 'comments';

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
    'comments_entity_id_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'entity_id',
      ],
      'length' =>
      [
      ],
    ],
    'comments_user_id_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'user_id',
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
    'entity_id' =>
    [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'user_id' =>
    [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'table_alias' =>
    [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'subject' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'body' =>
    [
    'type' => 'text',
    'length' => null,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    'author_name' =>
    [
    'type' => 'string',
    'length' => 100,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'author_email' =>
    [
    'type' => 'string',
    'length' => 100,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'author_web' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'author_ip' =>
    [
    'type' => 'string',
    'length' => 60,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'parent_id' =>
    [
    'type' => 'integer',
    'length' => 4,
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
    'length' => 4,
    'unsigned' => false,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'lft' =>
    [
    'type' => 'integer',
    'length' => 4,
    'unsigned' => false,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'status' =>
    [
    'type' => 'string',
    'length' => 20,
    'null' => false,
    'default' => null,
    'comment' => 'pending, approved, spam, trash',
    'precision' => null,
    'fixed' => null,
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

    public $records = [
    ];
}
