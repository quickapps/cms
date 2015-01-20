<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    1.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace QuickApps\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class CommentsFixture extends TestFixture {

	public $fields = array (
  '_constraints' => 
  array (
    'primary' => 
    array (
      'type' => 'primary',
      'columns' => 
      array (
        0 => 'id',
      ),
      'length' => 
      array (
      ),
    ),
  ),
  'id' => 
  array (
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'autoIncrement' => true,
    'precision' => NULL,
  ),
  'entity_id' => 
  array (
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'user_id' => 
  array (
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'table_alias' => 
  array (
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'subject' => 
  array (
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'body' => 
  array (
    'type' => 'text',
    'length' => NULL,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ),
  'author_name' => 
  array (
    'type' => 'string',
    'length' => 100,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'author_email' => 
  array (
    'type' => 'string',
    'length' => 100,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'author_web' => 
  array (
    'type' => 'string',
    'length' => 200,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'author_ip' => 
  array (
    'type' => 'string',
    'length' => 60,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'parent_id' => 
  array (
    'type' => 'integer',
    'length' => 4,
    'unsigned' => false,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'rght' => 
  array (
    'type' => 'integer',
    'length' => 4,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'lft' => 
  array (
    'type' => 'integer',
    'length' => 4,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'status' => 
  array (
    'type' => 'string',
    'length' => 20,
    'null' => false,
    'default' => NULL,
    'comment' => 'pending, approved, spam, trash',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'created' => 
  array (
    'type' => 'datetime',
    'length' => NULL,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ),
);

	public $records = array (
  0 => 
  array (
    'id' => 1,
    'entity_id' => '1',
    'user_id' => NULL,
    'table_alias' => 'nodes',
    'subject' => 'This is an unstable repository',
    'body' => 'This is an unstable repository and should be treated as an alpha.',
    'author_name' => NULL,
    'author_email' => NULL,
    'author_web' => NULL,
    'author_ip' => '192.168.1.1',
    'parent_id' => NULL,
    'rght' => 2,
    'lft' => 2,
    'status' => 'approved',
    'created' => '2014-08-03 05:14:42',
  ),
  1 => 
  array (
    'id' => 4,
    'entity_id' => '1',
    'user_id' => 1,
    'table_alias' => 'nodes',
    'subject' => 'asd ad asd',
    'body' => 'Lorem Ipsum',
    'author_name' => '',
    'author_email' => '',
    'author_web' => '',
    'author_ip' => '192.168.1.1',
    'parent_id' => 1,
    'rght' => 1,
    'lft' => 0,
    'status' => 'approved',
    'created' => '2014-08-03 08:01:29',
  ),
);

}

