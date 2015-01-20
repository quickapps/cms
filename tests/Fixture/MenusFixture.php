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

class MenusFixture extends TestFixture {

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
  'slug' => 
  array (
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'title' => 
  array (
    'type' => 'string',
    'length' => 255,
    'null' => false,
    'default' => NULL,
    'comment' => 'Menu title, displayed at top of block.',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'description' => 
  array (
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => 'Menu description.',
    'precision' => NULL,
  ),
  'handler' => 
  array (
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => NULL,
    'comment' => 'Name of the plugin that created this menu.',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'settings' => 
  array (
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ),
);

	public $records = array (
  0 => 
  array (
    'id' => 1,
    'slug' => 'management',
    'title' => 'Management',
    'description' => 'The Management menu contains links for administrative tasks.',
    'handler' => 'System',
    'settings' => NULL,
  ),
  1 => 
  array (
    'id' => 2,
    'slug' => 'site-main-menu',
    'title' => 'Site Main Menu',
    'description' => 'The Site Main Menu is used on many sites to show the major sections of the site, often in a top navigation bar.',
    'handler' => 'System',
    'settings' => NULL,
  ),
);

}

