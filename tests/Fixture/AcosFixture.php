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

class AcosFixture extends TestFixture
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
    'length' => 10,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'autoIncrement' => true,
    'precision' => NULL,
  ],
  'parent_id' => 
  [
    'type' => 'integer',
    'length' => 10,
    'unsigned' => false,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'lft' => 
  [
    'type' => 'integer',
    'length' => 10,
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
    'length' => 10,
    'unsigned' => false,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'plugin' => 
  [
    'type' => 'string',
    'length' => 255,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'alias' => 
  [
    'type' => 'string',
    'length' => 255,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'alias_hash' => 
  [
    'type' => 'string',
    'length' => 32,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
];

    public $records = [];
}
