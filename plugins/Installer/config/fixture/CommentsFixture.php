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
    'default' => NULL,
    'comment' => '',
    'autoIncrement' => true,
    'precision' => NULL,
  ],
  'entity_id' => 
  [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'user_id' => 
  [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'table_alias' => 
  [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'subject' => 
  [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'body' => 
  [
    'type' => 'text',
    'length' => NULL,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ],
  'author_name' => 
  [
    'type' => 'string',
    'length' => 100,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'author_email' => 
  [
    'type' => 'string',
    'length' => 100,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'author_web' => 
  [
    'type' => 'string',
    'length' => 200,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'author_ip' => 
  [
    'type' => 'string',
    'length' => 60,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'parent_id' => 
  [
    'type' => 'integer',
    'length' => 4,
    'unsigned' => false,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'rght' => 
  [
    'type' => 'integer',
    'length' => 4,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'lft' => 
  [
    'type' => 'integer',
    'length' => 4,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'status' => 
  [
    'type' => 'string',
    'length' => 20,
    'null' => false,
    'default' => NULL,
    'comment' => 'pending, approved, spam, trash',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'created' => 
  [
    'type' => 'datetime',
    'length' => NULL,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ],
];

    public $records = [
];
}
