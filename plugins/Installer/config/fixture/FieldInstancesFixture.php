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
class FieldInstancesFixture
{

    /**
     * Table name.
     *
     * @var string
     */
    public $table = 'field_instances';
/**
 * Table columns.
 *
 * @var array
 */
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
    '_indexes' =>
    [
    'field_instances_id_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'id',
      ],
      'length' =>
      [
      ],
    ],
    'field_instances_eav_attribute_id_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'eav_attribute_id',
      ],
      'length' =>
      [
      ],
    ],
    ],
    '_options' =>
    [
    'engine' => 'InnoDB',
    'collation' => 'utf8_unicode_ci',
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
    'eav_attribute_id' =>
    [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'handler' =>
    [
    'type' => 'string',
    'length' => 80,
    'null' => false,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
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
    'collate' => 'utf8_unicode_ci',
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
    'collate' => 'utf8_unicode_ci',
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
    'type' => 'binary',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => 'Serialized information',
    'precision' => null,
    ],
    'view_modes' =>
    [
    'type' => 'binary',
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
    'collate' => 'utf8_unicode_ci',
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
/**
 * Table records.
 *
 * @var array
 */
    public $records = [
    0 =>
    [
    'id' => 1,
    'eav_attribute_id' => 1,
    'handler' => 'Field\\Field\\TextField',
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
    'eav_attribute_id' => 2,
    'handler' => 'Field\\Field\\TextField',
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
    'eav_attribute_id' => 4,
    'handler' => 'Field\\Field\\TextField',
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
    'eav_attribute_id' => 3,
    'handler' => 'Taxonomy\\Field\\TermField',
    'label' => 'Category',
    'description' => '',
    'required' => false,
    'settings' => 'a:4:{s:10:"vocabulary";s:1:"1";s:4:"type";s:6:"select";s:10:"max_values";s:1:"0";s:13:"error_message";s:0:"";}',
    'view_modes' => 'a:5:{s:7:"default";a:6:{s:16:"label_visibility";s:6:"inline";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:55:"<a href="{{url}} type:article"{{attrs}}>{{content}}</a>";}s:6:"teaser";a:6:{s:16:"label_visibility";s:6:"inline";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:55:"<a href="{{url}} type:article"{{attrs}}>{{content}}</a>";}s:13:"search-result";a:6:{s:16:"label_visibility";s:6:"inline";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:55:"<a href="{{url}} type:article"{{attrs}}>{{content}}</a>";}s:3:"rss";a:6:{s:16:"label_visibility";s:5:"above";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"1";s:8:"ordering";i:2;s:9:"formatter";s:5:"plain";s:13:"link_template";s:42:"<a href="{{url}}"{{attrs}}>{{content}}</a>";}s:4:"full";a:6:{s:16:"label_visibility";s:6:"inline";s:8:"hooktags";s:1:"0";s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:55:"<a href="{{url}} type:article"{{attrs}}>{{content}}</a>";}}',
    'type' => 'text',
    'locked' => false,
    'ordering' => 2,
    ],
    ];
}
