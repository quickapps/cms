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

class LanguagesFixture extends TestFixture {

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
    'code' => 
    array (
      'type' => 'unique',
      'columns' => 
      array (
        0 => 'code',
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
  'code' => 
  array (
    'type' => 'string',
    'length' => 12,
    'null' => false,
    'default' => NULL,
    'comment' => 'Language code, e.g. ’eng’',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'name' => 
  array (
    'type' => 'string',
    'length' => 64,
    'null' => false,
    'default' => NULL,
    'comment' => 'Language name in English.',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'direction' => 
  array (
    'type' => 'string',
    'length' => 3,
    'null' => false,
    'default' => 'ltr',
    'comment' => 'Direction of language (Left-to-Right , Right-to-Left ).',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'icon' => 
  array (
    'type' => 'string',
    'length' => 255,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'status' => 
  array (
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => '0',
    'comment' => 'Enabled flag (1 = Enabled, 0 = Disabled).',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'ordering' => 
  array (
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => '0',
    'comment' => 'Weight, used in lists of languages.',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
);

	public $records = array (
  0 => 
  array (
    'id' => 1,
    'code' => 'en-us',
    'name' => 'English',
    'direction' => 'ltr',
    'icon' => 'us.gif',
    'status' => 1,
    'ordering' => 0,
  ),
  1 => 
  array (
    'id' => 2,
    'code' => 'es',
    'name' => 'Spanish',
    'direction' => 'ltr',
    'icon' => 'es.gif',
    'status' => 1,
    'ordering' => 0,
  ),
);

}

