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

class NodesFixture extends TestFixture {

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
  'node_type_id' => 
  array (
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'node_type_slug' => 
  array (
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'translation_for' => 
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
  'slug' => 
  array (
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'title' => 
  array (
    'type' => 'string',
    'length' => 250,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'description' => 
  array (
    'type' => 'string',
    'length' => 200,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'promote' => 
  array (
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => '0',
    'comment' => 'Show in front page?',
    'precision' => NULL,
  ),
  'sticky' => 
  array (
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => '0',
    'comment' => 'Show at top of lists',
    'precision' => NULL,
  ),
  'comment_status' => 
  array (
    'type' => 'integer',
    'length' => 2,
    'unsigned' => false,
    'null' => false,
    'default' => '0',
    'comment' => '0=closed, 1=open, 2=readonly',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'language' => 
  array (
    'type' => 'string',
    'fixed' => true,
    'length' => 10,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ),
  'status' => 
  array (
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
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
  'modified' => 
  array (
    'type' => 'datetime',
    'length' => NULL,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ),
  'created_by' => 
  array (
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'modified_by' => 
  array (
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
);

	public $records = array (
  0 => 
  array (
    'id' => 1,
    'node_type_id' => 1,
    'node_type_slug' => 'article',
    'translation_for' => NULL,
    'slug' => 'my-first-article',
    'title' => 'My First Article!',
    'description' => 'Custom meta description',
    'promote' => true,
    'sticky' => false,
    'comment_status' => 1,
    'language' => '',
    'status' => true,
    'created' => '2014-06-12 07:44:01',
    'modified' => '2014-08-10 10:26:27',
    'created_by' => 1,
    'modified_by' => 0,
  ),
  1 => 
  array (
    'id' => 2,
    'node_type_id' => 1,
    'node_type_slug' => 'article',
    'translation_for' => NULL,
    'slug' => 'curabitur-quis-ultricies-nisl',
    'title' => 'Curabitur quis ultricies nisl',
    'description' => 'Donec eget rutrum nunc. Vestibulum sit amet faucibus elit.',
    'promote' => true,
    'sticky' => true,
    'comment_status' => 0,
    'language' => '',
    'status' => true,
    'created' => '2014-08-05 22:19:44',
    'modified' => '2014-08-05 22:19:44',
    'created_by' => 1,
    'modified_by' => 0,
  ),
);

}

