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

class MenusFixture
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
  'slug' => 
  [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'title' => 
  [
    'type' => 'string',
    'length' => 255,
    'null' => false,
    'default' => NULL,
    'comment' => 'Menu title, displayed at top of block.',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'description' => 
  [
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => 'Menu description.',
    'precision' => NULL,
  ],
  'handler' => 
  [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => NULL,
    'comment' => 'Name of the plugin that created this menu.',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'settings' => 
  [
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ],
];

    public $records = [
  0 => 
  [
    'id' => 1,
    'slug' => 'management',
    'title' => 'Management',
    'description' => 'The Management menu contains links for administrative tasks.',
    'handler' => 'System',
    'settings' => NULL,
  ],
  1 => 
  [
    'id' => 2,
    'slug' => 'site-main-menu',
    'title' => 'Site Main Menu',
    'description' => 'The Site Main Menu is used on many sites to show the major sections of the site, often in a top navigation bar.',
    'handler' => 'System',
    'settings' => NULL,
  ],
];
}
