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

class FieldInstancesFixture extends TestFixture {

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
    'length' => 10,
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
    'length' => 200,
    'null' => false,
    'default' => NULL,
    'comment' => 'Machine name, must be unique',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'table_alias' => 
  array (
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => NULL,
    'comment' => 'Name of the table to which this field belongs to. eg: comment, node_article. Must be unique',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'handler' => 
  array (
    'type' => 'string',
    'length' => 80,
    'null' => false,
    'default' => NULL,
    'comment' => 'Name of event handler class under the `Field` namespace',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'label' => 
  array (
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => NULL,
    'comment' => 'Human readble name, used in views. eg: `First Name` (for a textbox)',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'description' => 
  array (
    'type' => 'string',
    'length' => 250,
    'null' => true,
    'default' => NULL,
    'comment' => 'instructions to present to the user below this field on the editing form.',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'required' => 
  array (
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => '0',
    'comment' => '',
    'precision' => NULL,
  ),
  'settings' => 
  array (
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => 'Serialized information',
    'precision' => NULL,
  ),
  'view_modes' => 
  array (
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ),
  'locked' => 
  array (
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => '0',
    'comment' => '0: (unlocked) users can edit this instance; 1: (locked) users can not modify this instance using web interface',
    'precision' => NULL,
  ),
  'ordering' => 
  array (
    'type' => 'integer',
    'length' => 3,
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
    'slug' => 'article-introduction',
    'table_alias' => 'nodes:article',
    'handler' => 'TextField',
    'label' => 'Introduction',
    'description' => 'Brief description',
    'required' => true,
    'settings' => 'a:5:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:5:"plain";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}',
    'view_modes' => 'a:5:{s:7:"default";a:4:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;}s:6:"teaser";a:4:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;}s:13:"search-result";a:4:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;}s:3:"rss";a:4:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;}s:4:"full";a:4:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;}}',
    'locked' => false,
    'ordering' => 0,
  ),
  1 => 
  array (
    'id' => 3,
    'slug' => 'article-body',
    'table_alias' => 'nodes:article',
    'handler' => 'TextField',
    'label' => 'Body',
    'description' => '',
    'required' => true,
    'settings' => 'a:5:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:8:"markdown";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}',
    'view_modes' => 'a:5:{s:7:"default";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:6:"teaser";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:13:"search-result";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:3:"rss";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:4:"full";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";b:0;s:6:"hidden";b:0;s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}}',
    'locked' => false,
    'ordering' => 1,
  ),
);

}

