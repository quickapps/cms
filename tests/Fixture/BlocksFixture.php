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

class BlocksFixture extends TestFixture {

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
    'delta' => 
    array (
      'type' => 'unique',
      'columns' => 
      array (
        0 => 'delta',
        1 => 'handler',
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
    'comment' => 'Primary Key - Unique block ID.',
    'autoIncrement' => true,
    'precision' => NULL,
  ),
  'copy_id' => 
  array (
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => true,
    'default' => NULL,
    'comment' => 'id of the block this block is a copy of',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'delta' => 
  array (
    'type' => 'string',
    'length' => 30,
    'null' => false,
    'default' => NULL,
    'comment' => 'unique ID within a handler',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'handler' => 
  array (
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => 'Block',
    'comment' => 'Name of the plugin that created this block. Used to generate event name, e.g. "Menu" triggers "Block.Menu.display" when rendering the block',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'title' => 
  array (
    'type' => 'string',
    'length' => 100,
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
  'body' => 
  array (
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ),
  'visibility' => 
  array (
    'type' => 'string',
    'length' => 8,
    'null' => false,
    'default' => 'except',
    'comment' => 'indicate how to show blocks on pages. (except = show on all pages except listed pages; only = show only on listed pages; php = use custom PHP code to determine visibility)',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'pages' => 
  array (
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => 'Contents of the "Pages" block contains either a list of paths on which to include/exclude the block or PHP code, depending on "visibility" setting.',
    'precision' => NULL,
  ),
  'locale' => 
  array (
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ),
  'settings' => 
  array (
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => 'additional information used by this block, used by blocks handlers <> `Block`',
    'precision' => NULL,
  ),
  'status' => 
  array (
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => '0',
    'comment' => '',
    'precision' => NULL,
  ),
);

	public $records = array (
  0 => 
  array (
    'id' => 1,
    'copy_id' => NULL,
    'delta' => '1',
    'handler' => 'System',
    'title' => 'Management [menu:1]',
    'description' => 'Associated block for "Management" menu.',
    'body' => NULL,
    'visibility' => 'except',
    'pages' => NULL,
    'locale' => NULL,
    'settings' => NULL,
    'status' => true,
  ),
  1 => 
  array (
    'id' => 2,
    'copy_id' => NULL,
    'delta' => '2',
    'handler' => 'System',
    'title' => 'Site Main Menu [menu:2]',
    'description' => 'Associated block for "Site Main Menu" menu.',
    'body' => NULL,
    'visibility' => 'except',
    'pages' => NULL,
    'locale' => NULL,
    'settings' => NULL,
    'status' => true,
  ),
  2 => 
  array (
    'id' => 3,
    'copy_id' => NULL,
    'delta' => 'dashboard_recent_content',
    'handler' => 'Node',
    'title' => 'Recent Content',
    'description' => 'Shows a list of latest created contents.',
    'body' => NULL,
    'visibility' => 'except',
    'pages' => NULL,
    'locale' => NULL,
    'settings' => NULL,
    'status' => true,
  ),
  3 => 
  array (
    'id' => 4,
    'copy_id' => NULL,
    'delta' => 'dashboard_search',
    'handler' => 'Node',
    'title' => 'Search',
    'description' => 'Quick Search Form',
    'body' => NULL,
    'visibility' => 'except',
    'pages' => NULL,
    'locale' => NULL,
    'settings' => NULL,
    'status' => true,
  ),
);

}

