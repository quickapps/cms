<?php
class FieldValuesFixture
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
  'field_instance_id' => 
  [
    'type' => 'integer',
    'length' => 10,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ],
  'field_instance_slug' => 
  [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'entity_id' => 
  [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => NULL,
    'comment' => 'id of the entity in `table`',
    'precision' => NULL,
    'fixed' => NULL,
  ],
  'table_alias' => 
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
  'raw' => 
  [
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => 'Extra information required by this field hadnler',
    'precision' => NULL,
  ],
];

    public $records = [
  0 => 
  [
    'id' => 1,
    'field_instance_id' => 1,
    'field_instance_slug' => 'article-introduction',
    'entity_id' => '1',
    'table_alias' => 'nodes:article',
    'value' => 'Lorem ipsum.[random]1,2,3,4,5[/random]',
    'raw' => 'a:0:{}',
  ],
  1 => 
  [
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
  ],
  2 => 
  [
    'id' => 10,
    'field_instance_id' => 3,
    'field_instance_slug' => 'article-body',
    'entity_id' => '2',
    'table_alias' => 'nodes:article',
    'value' => 'Curabitur quis ultricies nisl. Donec eget rutrum nunc. Quisque accumsan, justo sit amet suscipit ullamcorper, nisl lacus dictum arcu, at vehicula enim velit et libero. Vivamus venenatis lacinia eros, et ultrices erat interdum vitae. Aliquam scelerisque leo in tristique tincidunt. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi iaculis nec quam sit amet viverra. Vestibulum sit amet faucibus elit, et mattis urna. In consequat justo vitae augue venenatis lacinia.',
    'raw' => 'a:0:{}',
  ],
  3 => 
  [
    'id' => 11,
    'field_instance_id' => 1,
    'field_instance_slug' => 'article-introduction',
    'entity_id' => '2',
    'table_alias' => 'nodes:article',
    'value' => 'Curabitur quis ultricies nisl. Donec eget rutrum nunc. Quisque accumsan, justo sit amet suscipit ullamcorper, nisl lacus dictum arcu, at vehicula enim velit et libero.',
    'raw' => 'a:0:{}',
  ],
];
}
