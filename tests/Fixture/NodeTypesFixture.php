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

class NodeTypesFixture extends TestFixture {

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
    'slug' => 
    array (
      'type' => 'unique',
      'columns' => 
      array (
        0 => 'slug',
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
    'length' => 100,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'name' => 
  array (
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => NULL,
    'comment' => 'human-readable name',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'description' => 
  array (
    'type' => 'string',
    'length' => 255,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'title_label' => 
  array (
    'type' => 'string',
    'length' => 80,
    'null' => false,
    'default' => NULL,
    'comment' => 'the label displayed for the title field on the edit form.',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'defaults' => 
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
    'slug' => 'article',
    'name' => 'Article',
    'description' => 'Use articles for time-sensitive content like news, press releases or blog posts.',
    'title_label' => 'Title',
    'defaults' => 'a:7:{s:6:"status";s:1:"1";s:7:"promote";s:1:"1";s:6:"sticky";s:1:"1";s:11:"author_name";s:1:"1";s:9:"show_date";s:1:"1";s:14:"comment_status";s:1:"0";s:8:"language";s:0:"";}',
  ),
);

}

