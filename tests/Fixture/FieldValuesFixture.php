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

class FieldValuesFixture extends TestFixture {

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
    'type' => 'biginteger',
    'length' => 20,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'autoIncrement' => true,
    'precision' => NULL,
  ),
  'field_instance_id' => 
  array (
    'type' => 'integer',
    'length' => 10,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'field_instance_slug' => 
  array (
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'entity_id' => 
  array (
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => NULL,
    'comment' => 'id of the entity in `table`',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'table_alias' => 
  array (
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'value' => 
  array (
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
  ),
  'raw' => 
  array (
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => 'Extra information required by this field hadnler',
    'precision' => NULL,
  ),
);

	public $records = array (
  0 => 
  array (
    'id' => 1,
    'field_instance_id' => 1,
    'field_instance_slug' => 'article-introduction',
    'entity_id' => '1',
    'table_alias' => 'nodes:article',
    'value' => 'Lorem ipsum.[random]1,2,3,4,5[/random]',
    'raw' => 'a:0:{}',
  ),
  1 => 
  array (
    'id' => 9,
    'field_instance_id' => 3,
    'field_instance_slug' => 'article-body',
    'entity_id' => '1',
    'table_alias' => 'nodes:article',
    'value' => '# QuickApps CMS Site Skeleton

A skeleton for creating web sites with [QuickAppsCMS](http://quickappscms.org) 2.0. This is an unstable repository and should be treated as an alpha.

## Installation

### Install with composer 

1. Download [Composer](http://getcomposer.org/doc/00-intro.md) or update `composer self-update`. 
2. Run `php composer.phar create-project -s dev quickapps/website [website_name]`. 

If Composer is installed globally, run `composer create-project -s dev quickapps/website [website_name]` After composer is done visit `http://example.com/` and start QuickAppsCMS installation.
',
    'raw' => 'a:0:{}',
  ),
  2 => 
  array (
    'id' => 10,
    'field_instance_id' => 3,
    'field_instance_slug' => 'article-body',
    'entity_id' => '2',
    'table_alias' => 'nodes:article',
    'value' => 'Curabitur quis ultricies nisl. Donec eget rutrum nunc. Quisque accumsan, justo sit amet suscipit ullamcorper, nisl lacus dictum arcu, at vehicula enim velit et libero. Vivamus venenatis lacinia eros, et ultrices erat interdum vitae. Aliquam scelerisque leo in tristique tincidunt. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi iaculis nec quam sit amet viverra. Vestibulum sit amet faucibus elit, et mattis urna. In consequat justo vitae augue venenatis lacinia.',
    'raw' => 'a:0:{}',
  ),
  3 => 
  array (
    'id' => 11,
    'field_instance_id' => 1,
    'field_instance_slug' => 'article-introduction',
    'entity_id' => '2',
    'table_alias' => 'nodes:article',
    'value' => 'Curabitur quis ultricies nisl. Donec eget rutrum nunc. Quisque accumsan, justo sit amet suscipit ullamcorper, nisl lacus dictum arcu, at vehicula enim velit et libero.',
    'raw' => 'a:0:{}',
  ),
);

}

