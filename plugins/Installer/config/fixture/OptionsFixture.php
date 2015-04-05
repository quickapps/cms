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

class OptionsFixture
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
    'options_name' => 
    [
      'type' => 'unique',
      'columns' => 
      [
        0 => 'name',
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
  'name' => 
  [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'value' => 
  [
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ],
  'autoload' => 
  [
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => '0',
    'comment' => '1: true (autoload); 0:false',
    'precision' => NULL,
  ],
];

    public $records = [
  0 => 
  [
    'id' => 1,
    'name' => 'front_theme',
    'value' => 'FrontendTheme',
    'autoload' => true,
  ],
  1 => 
  [
    'id' => 2,
    'name' => 'default_language',
    'value' => 'en_US',
    'autoload' => true,
  ],
  2 => 
  [
    'id' => 3,
    'name' => 'site_description',
    'value' => 'Open Source CMS built on CakePHP 3.0',
    'autoload' => true,
  ],
  3 => 
  [
    'id' => 4,
    'name' => 'site_slogan',
    'value' => 'Open Source CMS built on CakePHP 3.0',
    'autoload' => true,
  ],
  4 => 
  [
    'id' => 5,
    'name' => 'back_theme',
    'value' => 'BackendTheme',
    'autoload' => true,
  ],
  5 => 
  [
    'id' => 6,
    'name' => 'site_title',
    'value' => 'My QuickApps CMS Site',
    'autoload' => true,
  ],
  6 => 
  [
    'id' => 7,
    'name' => 'url_locale_prefix',
    'value' => '1',
    'autoload' => true,
  ],
  7 => 
  [
    'id' => 8,
    'name' => 'site_email',
    'value' => 'demo@email.com',
    'autoload' => false,
  ],
  8 => 
  [
    'id' => 9,
    'name' => 'site_maintenance_message',
    'value' => 'We sincerely apologize for the inconvenience.<br/>Our site is currently undergoing scheduled maintenance and upgrades, but will return shortly.<br/>Thanks you for your patience.',
    'autoload' => false,
  ],
  9 => 
  [
    'id' => 10,
    'name' => 'site_maintenance_ip',
    'value' => NULL,
    'autoload' => false,
  ],
  10 => 
  [
    'id' => 11,
    'name' => 'site_nodes_home',
    'value' => '5',
    'autoload' => true,
  ],
  11 => 
  [
    'id' => 12,
    'name' => 'site_maintenance',
    'value' => '0',
    'autoload' => true,
  ],
];
}
