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

class BlockRegionsFixture extends TestFixture {

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
    'block_id' => 
    array (
      'type' => 'unique',
      'columns' => 
      array (
        0 => 'block_id',
        1 => 'theme',
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
  'block_id' => 
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
  'theme' => 
  array (
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'region' => 
  array (
    'type' => 'string',
    'length' => 200,
    'null' => true,
    'default' => '',
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'ordering' => 
  array (
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => '0',
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
);

	public $records = array (
  0 => 
  array (
    'id' => 1,
    'block_id' => 2,
    'theme' => 'BackendTheme',
    'region' => '',
    'ordering' => 0,
  ),
  1 => 
  array (
    'id' => 2,
    'block_id' => 2,
    'theme' => 'FrontendTheme',
    'region' => 'main-menu',
    'ordering' => 0,
  ),
  2 => 
  array (
    'id' => 3,
    'block_id' => 1,
    'theme' => 'BackendTheme',
    'region' => 'main-menu',
    'ordering' => 0,
  ),
  3 => 
  array (
    'id' => 4,
    'block_id' => 1,
    'theme' => 'FrontendTheme',
    'region' => '',
    'ordering' => 0,
  ),
  4 => 
  array (
    'id' => 5,
    'block_id' => 3,
    'theme' => 'BackendTheme',
    'region' => 'dashboard-main',
    'ordering' => 0,
  ),
  5 => 
  array (
    'id' => 6,
    'block_id' => 3,
    'theme' => 'FrontendTheme',
    'region' => '',
    'ordering' => 0,
  ),
  6 => 
  array (
    'id' => 7,
    'block_id' => 4,
    'theme' => 'BackendTheme',
    'region' => 'dashboard-sidebar',
    'ordering' => 0,
  ),
  7 => 
  array (
    'id' => 8,
    'block_id' => 4,
    'theme' => 'FrontendTheme',
    'region' => '',
    'ordering' => 0,
  ),
);

}

