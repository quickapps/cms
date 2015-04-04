<?php
class FieldInstancesFixture
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
    'field_instances_slug' =>
    [
      'type' => 'unique',
      'columns' =>
      [
        0 => 'slug',
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
    'default' => null,
    'comment' => '',
    'autoIncrement' => true,
    'precision' => null,
    ],
    'slug' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => null,
    'comment' => 'Machine name, must be unique',
    'precision' => null,
    'fixed' => null,
    ],
    'table_alias' =>
    [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => null,
    'comment' => 'Name of the table to which this field belongs to. eg: comment, node_article. Must be unique',
    'precision' => null,
    'fixed' => null,
    ],
    'handler' =>
    [
    'type' => 'string',
    'length' => 80,
    'null' => false,
    'default' => null,
    'comment' => 'Name of event handler class under the `Field` namespace',
    'precision' => null,
    'fixed' => null,
    ],
    'label' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => null,
    'comment' => 'Human readble name, used in views. eg: `First Name` (for a textbox)',
    'precision' => null,
    'fixed' => null,
    ],
    'description' =>
    [
    'type' => 'string',
    'length' => 250,
    'null' => true,
    'default' => null,
    'comment' => 'instructions to present to the user below this field on the editing form.',
    'precision' => null,
    'fixed' => null,
    ],
    'required' =>
    [
    'type' => 'boolean',
    'length' => null,
    'null' => false,
    'default' => '0',
    'comment' => '',
    'precision' => null,
    ],
    'settings' =>
    [
    'type' => 'text',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => 'Serialized information',
    'precision' => null,
    ],
    'view_modes' =>
    [
    'type' => 'text',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    'type' =>
    [
    'type' => 'string',
    'length' => 10,
    'null' => false,
    'default' => 'varchar',
    'comment' => 'Data type for this field (datetime, decimal, int, text, varchar)',
    'precision' => null,
    'fixed' => null,
    ],
    'locked' =>
    [
    'type' => 'boolean',
    'length' => null,
    'null' => false,
    'default' => '0',
    'comment' => '0: (unlocked) users can edit this instance; 1: (locked) users can not modify this instance using web interface',
    'precision' => null,
    ],
    'ordering' =>
    [
    'type' => 'integer',
    'length' => 3,
    'unsigned' => false,
    'null' => false,
    'default' => '0',
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    ];

    public $records = [
    0 =>
    [
    'id' => 1,
    'slug' => 'article-introduction',
    'table_alias' => 'nodes:article',
    'handler' => 'TextField',
    'label' => 'Introduction',
    'description' => 'Brief description',
    'required' => true,
    'settings' => 'a:5:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:5:"plain";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}',
    'view_modes' => 'a:5:{s:7:"default";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:1;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:6:"teaser";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"160";}s:13:"search-result";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"200";}s:3:"rss";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"160";}s:4:"full";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}}',
    'type' => 'text',
    'locked' => false,
    'ordering' => 0,
    ],
    1 =>
    [
    'id' => 2,
    'slug' => 'article-body',
    'table_alias' => 'nodes:article',
    'handler' => 'TextField',
    'label' => 'Body',
    'description' => '',
    'required' => true,
    'settings' => 'a:5:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:4:"full";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}',
    'view_modes' => 'a:5:{s:7:"default";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:6:"teaser";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"1";s:8:"ordering";i:1;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:13:"search-result";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"1";s:8:"ordering";i:1;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:3:"rss";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:1;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"200";}s:4:"full";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:1;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}}',
    'type' => 'text',
    'locked' => false,
    'ordering' => 1,
    ],
    2 =>
    [
    'id' => 3,
    'slug' => 'page-body',
    'table_alias' => 'nodes:page',
    'handler' => 'TextField',
    'label' => 'Body',
    'description' => 'Page content',
    'required' => true,
    'settings' => 'a:5:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:4:"full";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}',
    'view_modes' => 'a:5:{s:7:"default";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"1";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}s:6:"teaser";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"160";}s:13:"search-result";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"1";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"200";}s:3:"rss";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";s:1:"1";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"400";}s:4:"full";a:6:{s:16:"label_visibility";s:6:"hidden";s:8:"hooktags";s:1:"1";s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";}}',
    'type' => 'text',
    'locked' => false,
    'ordering' => 0,
    ],
    3 =>
    [
    'id' => 4,
    'slug' => 'article-category',
    'table_alias' => 'nodes:article',
    'handler' => 'TaxonomyField',
    'label' => 'Category',
    'description' => '',
    'required' => false,
    'settings' => 'a:4:{s:10:"vocabulary";s:1:"1";s:4:"type";s:6:"select";s:10:"max_values";s:1:"0";s:13:"error_message";s:0:"";}',
    'view_modes' => 'a:5:{s:7:"default";a:6:{s:16:"label_visibility";s:6:"inline";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:42:"<a href="{{url}}"{{attrs}}>{{content}}</a>";}s:6:"teaser";a:6:{s:16:"label_visibility";s:6:"inline";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:40:"<a href="{url}"{{attrs}}>{{content}}</a>";}s:13:"search-result";a:6:{s:16:"label_visibility";s:6:"inline";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:42:"<a href="{{url}}"{{attrs}}>{{content}}</a>";}s:3:"rss";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"1";s:8:"ordering";i:2;s:9:"formatter";s:5:"plain";s:13:"link_template";s:42:"<a href="{{url}}"{{attrs}}>{{content}}</a>";}s:4:"full";a:6:{s:16:"label_visibility";s:6:"inline";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:42:"<a href="{{url}}"{{attrs}}>{{content}}</a>";}}',
    'type' => 'text',
    'locked' => false,
    'ordering' => 2,
    ],
    ];
}
