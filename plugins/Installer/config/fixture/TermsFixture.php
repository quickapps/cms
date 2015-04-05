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

class TermsFixture
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
    'terms_slug' => 
    [
      'type' => 'unique',
      'columns' => 
      [
        0 => 'slug',
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
  'vocabulary_id' => 
  [
    'type' => 'integer',
    'length' => 11,
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
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'rght' => 
  [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'parent_id' => 
  [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'name' => 
  [
    'type' => 'string',
    'length' => 255,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'slug' => 
  [
    'type' => 'string',
    'length' => 255,
    'null' => false,
    'default' => NULL,
    'comment' => '',
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
  'modified' => 
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
  0 => 
  [
    'id' => 1,
    'vocabulary_id' => 1,
    'lft' => 1,
    'rght' => 6,
    'parent_id' => 0,
    'name' => 'PHP',
    'slug' => 'php',
    'created' => '2015-03-31 21:20:39',
    'modified' => '2015-03-31 21:20:39',
  ],
  1 => 
  [
    'id' => 2,
    'vocabulary_id' => 1,
    'lft' => 7,
    'rght' => 10,
    'parent_id' => 0,
    'name' => 'JavaScript',
    'slug' => 'javascript',
    'created' => '2015-03-31 21:20:51',
    'modified' => '2015-03-31 21:20:51',
  ],
  2 => 
  [
    'id' => 3,
    'vocabulary_id' => 1,
    'lft' => 2,
    'rght' => 3,
    'parent_id' => 1,
    'name' => 'CakePHP',
    'slug' => 'cakephp',
    'created' => '2015-03-31 21:20:56',
    'modified' => '2015-03-31 21:20:56',
  ],
  3 => 
  [
    'id' => 4,
    'vocabulary_id' => 1,
    'lft' => 8,
    'rght' => 9,
    'parent_id' => 2,
    'name' => 'jQuery',
    'slug' => 'jquery',
    'created' => '2015-03-31 21:21:01',
    'modified' => '2015-03-31 21:21:01',
  ],
  4 => 
  [
    'id' => 5,
    'vocabulary_id' => 1,
    'lft' => 4,
    'rght' => 5,
    'parent_id' => 1,
    'name' => 'QuickAppsCMS',
    'slug' => 'quickappscms',
    'created' => '2015-03-31 21:21:07',
    'modified' => '2015-03-31 21:21:07',
  ],
];
}
