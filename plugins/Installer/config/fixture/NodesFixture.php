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

class NodesFixture
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
  'node_type_id' => 
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
  'node_type_slug' => 
  [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'translation_for' => 
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
  'slug' => 
  [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'title' => 
  [
    'type' => 'string',
    'length' => 250,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'description' => 
  [
    'type' => 'string',
    'length' => 200,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'promote' => 
  [
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => '0',
    'comment' => 'Show in front page?',
    'precision' => NULL,
  ],
  'sticky' => 
  [
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => '0',
    'comment' => 'Show at top of lists',
    'precision' => NULL,
  ],
  'comment_status' => 
  [
    'type' => 'integer',
    'length' => 2,
    'unsigned' => false,
    'null' => false,
    'default' => '0',
    'comment' => '0=closed, 1=open, 2=readonly',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'language' => 
  [
    'type' => 'string',
    'fixed' => true,
    'length' => 10,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ],
  'status' => 
  [
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
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
  'created_by' => 
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
  'modified_by' => 
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
];

    public $records = [
  0 => 
  [
    'id' => 1,
    'node_type_id' => 1,
    'node_type_slug' => 'article',
    'translation_for' => NULL,
    'slug' => 'hello-world',
    'title' => '¡Hello World!',
    'description' => 'hello world demo article',
    'promote' => true,
    'sticky' => false,
    'comment_status' => 1,
    'language' => '',
    'status' => true,
    'created' => '2014-06-12 07:44:01',
    'modified' => '2015-04-04 03:00:33',
    'created_by' => 1,
    'modified_by' => 1,
  ],
  1 => 
  [
    'id' => 2,
    'node_type_id' => 2,
    'node_type_slug' => 'page',
    'translation_for' => NULL,
    'slug' => 'about',
    'title' => 'About',
    'description' => 'about QuickAppsCMS demo page',
    'promote' => false,
    'sticky' => false,
    'comment_status' => 0,
    'language' => '',
    'status' => true,
    'created' => '2015-03-31 21:06:50',
    'modified' => '2015-03-31 21:06:50',
    'created_by' => 1,
    'modified_by' => 1,
  ],
];
}
