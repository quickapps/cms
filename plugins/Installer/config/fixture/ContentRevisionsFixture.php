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
class ContentRevisionsFixture
{

    /**
     * Table name.
     *
     * @var string
     */
    public $table = 'content_revisions';
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
    'content_id' =>
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
    'summary' =>
    [
    'type' => 'string',
    'length' => 160,
    'null' => true,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'data' =>
    [
    'type' => 'binary',
    'length' => null,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    'hash' =>
    [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'created' =>
    [
    'type' => 'datetime',
    'length' => null,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
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
    'content_id' => 1,
    'summary' => '',
    'data' => 'O:28:"Content\\Model\\Entity\\Content":11:{s:14:"' . "\0" . '*' . "\0" . '_properties";a:17:{s:2:"id";i:1;s:15:"content_type_id";i:1;s:17:"content_type_slug";s:7:"article";s:15:"translation_for";N;s:4:"slug";s:11:"hello-world";s:5:"title";s:14:"¡Hello World!";s:11:"description";s:24:"hello world demo article";s:7:"promote";b:1;s:6:"sticky";b:0;s:14:"comment_status";i:1;s:8:"language";s:0:"";s:6:"status";b:1;s:7:"created";O:14:"Cake\\I18n\\Time":3:{s:4:"date";s:26:"2014-06-12 07:44:01.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:8:"modified";O:14:"Cake\\I18n\\Time":3:{s:4:"date";s:26:"2015-04-04 03:00:33.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:3:"UTC";}s:10:"created_by";i:1;s:11:"modified_by";i:1;s:7:"_fields";C:32:"Field\\Collection\\FieldCollection":9766:{x:i:1;a:3:{i:0;O:24:"Field\\Model\\Entity\\Field":11:{s:14:"' . "\0" . '*' . "\0" . '_properties";a:5:{s:4:"name";s:20:"article-introduction";s:5:"label";s:12:"Introduction";s:5:"value";s:52:"Welcome to QuickAppsCMS. This is an example content.";s:5:"extra";N;s:8:"metadata";O:15:"Cake\\ORM\\Entity":11:{s:14:"' . "\0" . '*' . "\0" . '_properties";a:14:{s:8:"value_id";i:1;s:11:"instance_id";i:1;s:12:"attribute_id";i:1;s:9:"entity_id";s:1:"1";s:11:"table_alias";s:8:"contents";s:4:"type";s:4:"text";s:6:"bundle";s:7:"article";s:7:"handler";s:21:"Field\\Field\\TextField";s:8:"required";b:1;s:11:"description";s:17:"Brief description";s:8:"settings";a:5:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:5:"plain";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}s:10:"view_modes";a:5:{s:7:"default";a:7:{s:16:"label_visibility";s:6:"hidden";s:10:"shortcodes";b:1;s:6:"hidden";s:1:"0";s:8:"ordering";i:1;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";s:8:"hooktags";s:1:"0";}s:6:"teaser";a:7:{s:16:"label_visibility";s:6:"hidden";s:10:"shortcodes";b:1;s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"160";s:8:"hooktags";s:1:"0";}s:13:"search-result";a:7:{s:16:"label_visibility";s:6:"hidden";s:10:"shortcodes";b:1;s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"200";s:8:"hooktags";s:1:"0";}s:3:"rss";a:7:{s:16:"label_visibility";s:6:"hidden";s:10:"shortcodes";b:1;s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"160";s:8:"hooktags";s:1:"0";}s:4:"full";a:7:{s:16:"label_visibility";s:6:"hidden";s:10:"shortcodes";b:1;s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";s:8:"hooktags";s:1:"0";}}s:6:"entity";r:1;s:6:"errors";a:0:{}}s:12:"' . "\0" . '*' . "\0" . '_original";a:1:{s:8:"value_id";N;}s:10:"' . "\0" . '*' . "\0" . '_hidden";a:0:{}s:11:"' . "\0" . '*' . "\0" . '_virtual";a:0:{}s:13:"' . "\0" . '*' . "\0" . '_className";N;s:9:"' . "\0" . '*' . "\0" . '_dirty";a:14:{s:8:"value_id";b:1;s:11:"instance_id";b:1;s:12:"attribute_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:4:"type";b:1;s:6:"bundle";b:1;s:7:"handler";b:1;s:8:"required";b:1;s:11:"description";b:1;s:8:"settings";b:1;s:10:"view_modes";b:1;s:6:"entity";b:1;s:6:"errors";b:1;}s:7:"' . "\0" . '*' . "\0" . '_new";b:1;s:10:"' . "\0" . '*' . "\0" . '_errors";a:0:{}s:11:"' . "\0" . '*' . "\0" . '_invalid";a:0:{}s:14:"' . "\0" . '*' . "\0" . '_accessible";a:1:{s:1:"*";b:1;}s:17:"' . "\0" . '*' . "\0" . '_registryAlias";N;}}s:12:"' . "\0" . '*' . "\0" . '_original";a:1:{s:5:"value";N;}s:10:"' . "\0" . '*' . "\0" . '_hidden";a:0:{}s:11:"' . "\0" . '*' . "\0" . '_virtual";a:0:{}s:13:"' . "\0" . '*' . "\0" . '_className";N;s:9:"' . "\0" . '*' . "\0" . '_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"' . "\0" . '*' . "\0" . '_new";b:0;s:10:"' . "\0" . '*' . "\0" . '_errors";a:0:{}s:11:"' . "\0" . '*' . "\0" . '_invalid";a:0:{}s:14:"' . "\0" . '*' . "\0" . '_accessible";a:1:{s:1:"*";b:1;}s:17:"' . "\0" . '*' . "\0" . '_registryAlias";N;}i:1;O:24:"Field\\Model\\Entity\\Field":11:{s:14:"' . "\0" . '*' . "\0" . '_properties";a:5:{s:4:"name";s:12:"article-body";s:5:"label";s:4:"Body";s:5:"value";s:1413:"<p><strong>Lorem ipsum</strong> dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>

<p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus.</p>

<p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem. Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc</p>
";s:5:"extra";N;s:8:"metadata";O:15:"Cake\\ORM\\Entity":11:{s:14:"' . "\0" . '*' . "\0" . '_properties";a:14:{s:8:"value_id";i:2;s:11:"instance_id";i:2;s:12:"attribute_id";i:2;s:9:"entity_id";s:1:"1";s:11:"table_alias";s:8:"contents";s:4:"type";s:4:"text";s:6:"bundle";s:7:"article";s:7:"handler";s:21:"Field\\Field\\TextField";s:8:"required";b:1;s:11:"description";s:0:"";s:8:"settings";a:5:{s:4:"type";s:8:"textarea";s:15:"text_processing";s:4:"full";s:7:"max_len";s:0:"";s:15:"validation_rule";s:0:"";s:18:"validation_message";s:0:"";}s:10:"view_modes";a:5:{s:7:"default";a:7:{s:16:"label_visibility";s:6:"hidden";s:10:"shortcodes";b:1;s:6:"hidden";s:1:"0";s:8:"ordering";i:0;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";s:8:"hooktags";s:1:"0";}s:6:"teaser";a:7:{s:16:"label_visibility";s:5:"above";s:10:"shortcodes";b:1;s:6:"hidden";s:1:"1";s:8:"ordering";i:1;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";s:8:"hooktags";s:1:"0";}s:13:"search-result";a:7:{s:16:"label_visibility";s:5:"above";s:10:"shortcodes";b:1;s:6:"hidden";s:1:"1";s:8:"ordering";i:1;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";s:8:"hooktags";s:1:"0";}s:3:"rss";a:7:{s:16:"label_visibility";s:6:"hidden";s:10:"shortcodes";b:1;s:6:"hidden";s:1:"0";s:8:"ordering";i:1;s:9:"formatter";s:7:"trimmed";s:11:"trim_length";s:3:"200";s:8:"hooktags";s:1:"0";}s:4:"full";a:7:{s:16:"label_visibility";s:6:"hidden";s:10:"shortcodes";b:1;s:6:"hidden";s:1:"0";s:8:"ordering";i:1;s:9:"formatter";s:4:"full";s:11:"trim_length";s:0:"";s:8:"hooktags";s:1:"0";}}s:6:"entity";r:1;s:6:"errors";a:0:{}}s:12:"' . "\0" . '*' . "\0" . '_original";a:1:{s:8:"value_id";N;}s:10:"' . "\0" . '*' . "\0" . '_hidden";a:0:{}s:11:"' . "\0" . '*' . "\0" . '_virtual";a:0:{}s:13:"' . "\0" . '*' . "\0" . '_className";N;s:9:"' . "\0" . '*' . "\0" . '_dirty";a:14:{s:8:"value_id";b:1;s:11:"instance_id";b:1;s:12:"attribute_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:4:"type";b:1;s:6:"bundle";b:1;s:7:"handler";b:1;s:8:"required";b:1;s:11:"description";b:1;s:8:"settings";b:1;s:10:"view_modes";b:1;s:6:"entity";b:1;s:6:"errors";b:1;}s:7:"' . "\0" . '*' . "\0" . '_new";b:1;s:10:"' . "\0" . '*' . "\0" . '_errors";a:0:{}s:11:"' . "\0" . '*' . "\0" . '_invalid";a:0:{}s:14:"' . "\0" . '*' . "\0" . '_accessible";a:1:{s:1:"*";b:1;}s:17:"' . "\0" . '*' . "\0" . '_registryAlias";N;}}s:12:"' . "\0" . '*' . "\0" . '_original";a:1:{s:5:"value";N;}s:10:"' . "\0" . '*' . "\0" . '_hidden";a:0:{}s:11:"' . "\0" . '*' . "\0" . '_virtual";a:0:{}s:13:"' . "\0" . '*' . "\0" . '_className";N;s:9:"' . "\0" . '*' . "\0" . '_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"' . "\0" . '*' . "\0" . '_new";b:0;s:10:"' . "\0" . '*' . "\0" . '_errors";a:0:{}s:11:"' . "\0" . '*' . "\0" . '_invalid";a:0:{}s:14:"' . "\0" . '*' . "\0" . '_accessible";a:1:{s:1:"*";b:1;}s:17:"' . "\0" . '*' . "\0" . '_registryAlias";N;}i:2;O:24:"Field\\Model\\Entity\\Field":11:{s:14:"' . "\0" . '*' . "\0" . '_properties";a:5:{s:4:"name";s:16:"article-category";s:5:"label";s:8:"Category";s:5:"value";s:16:"PHP QuickAppsCMS";s:5:"extra";a:2:{i:0;s:1:"1";i:1;s:1:"5";}s:8:"metadata";O:15:"Cake\\ORM\\Entity":11:{s:14:"' . "\0" . '*' . "\0" . '_properties";a:14:{s:8:"value_id";i:4;s:11:"instance_id";i:4;s:12:"attribute_id";i:3;s:9:"entity_id";s:1:"1";s:11:"table_alias";s:8:"contents";s:4:"type";s:4:"text";s:6:"bundle";s:7:"article";s:7:"handler";s:24:"Taxonomy\\Field\\TermField";s:8:"required";b:0;s:11:"description";s:0:"";s:8:"settings";a:4:{s:10:"vocabulary";s:1:"1";s:4:"type";s:6:"select";s:10:"max_values";s:1:"0";s:13:"error_message";s:0:"";}s:10:"view_modes";a:5:{s:7:"default";a:7:{s:16:"label_visibility";s:6:"inline";s:10:"shortcodes";b:0;s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:55:"<a href="{{url}} type:article"{{attrs}}>{{content}}</a>";s:8:"hooktags";s:1:"0";}s:6:"teaser";a:7:{s:16:"label_visibility";s:6:"inline";s:10:"shortcodes";b:0;s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:55:"<a href="{{url}} type:article"{{attrs}}>{{content}}</a>";s:8:"hooktags";s:1:"0";}s:13:"search-result";a:7:{s:16:"label_visibility";s:6:"inline";s:10:"shortcodes";b:0;s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:55:"<a href="{{url}} type:article"{{attrs}}>{{content}}</a>";s:8:"hooktags";s:1:"0";}s:3:"rss";a:7:{s:16:"label_visibility";s:5:"above";s:10:"shortcodes";b:0;s:6:"hidden";s:1:"1";s:8:"ordering";i:2;s:9:"formatter";s:5:"plain";s:13:"link_template";s:42:"<a href="{{url}}"{{attrs}}>{{content}}</a>";s:8:"hooktags";s:1:"0";}s:4:"full";a:7:{s:16:"label_visibility";s:6:"inline";s:10:"shortcodes";b:0;s:6:"hidden";s:1:"0";s:8:"ordering";i:2;s:9:"formatter";s:14:"link_localized";s:13:"link_template";s:55:"<a href="{{url}} type:article"{{attrs}}>{{content}}</a>";s:8:"hooktags";s:1:"0";}}s:6:"entity";r:1;s:6:"errors";a:0:{}}s:12:"' . "\0" . '*' . "\0" . '_original";a:1:{s:8:"value_id";N;}s:10:"' . "\0" . '*' . "\0" . '_hidden";a:0:{}s:11:"' . "\0" . '*' . "\0" . '_virtual";a:0:{}s:13:"' . "\0" . '*' . "\0" . '_className";N;s:9:"' . "\0" . '*' . "\0" . '_dirty";a:14:{s:8:"value_id";b:1;s:11:"instance_id";b:1;s:12:"attribute_id";b:1;s:9:"entity_id";b:1;s:11:"table_alias";b:1;s:4:"type";b:1;s:6:"bundle";b:1;s:7:"handler";b:1;s:8:"required";b:1;s:11:"description";b:1;s:8:"settings";b:1;s:10:"view_modes";b:1;s:6:"entity";b:1;s:6:"errors";b:1;}s:7:"' . "\0" . '*' . "\0" . '_new";b:1;s:10:"' . "\0" . '*' . "\0" . '_errors";a:0:{}s:11:"' . "\0" . '*' . "\0" . '_invalid";a:0:{}s:14:"' . "\0" . '*' . "\0" . '_accessible";a:1:{s:1:"*";b:1;}s:17:"' . "\0" . '*' . "\0" . '_registryAlias";N;}}s:12:"' . "\0" . '*' . "\0" . '_original";a:2:{s:5:"value";N;s:5:"extra";N;}s:10:"' . "\0" . '*' . "\0" . '_hidden";a:0:{}s:11:"' . "\0" . '*' . "\0" . '_virtual";a:0:{}s:13:"' . "\0" . '*' . "\0" . '_className";N;s:9:"' . "\0" . '*' . "\0" . '_dirty";a:5:{s:4:"name";b:1;s:5:"label";b:1;s:5:"value";b:1;s:5:"extra";b:1;s:8:"metadata";b:1;}s:7:"' . "\0" . '*' . "\0" . '_new";b:0;s:10:"' . "\0" . '*' . "\0" . '_errors";a:0:{}s:11:"' . "\0" . '*' . "\0" . '_invalid";a:0:{}s:14:"' . "\0" . '*' . "\0" . '_accessible";a:1:{s:1:"*";b:1;}s:17:"' . "\0" . '*' . "\0" . '_registryAlias";N;}};m:a:1:{s:11:"' . "\0" . '*' . "\0" . '_keysMap";a:3:{s:20:"article-introduction";i:0;s:12:"article-body";i:1;s:16:"article-category";i:2;}}}}s:12:"' . "\0" . '*' . "\0" . '_original";a:0:{}s:10:"' . "\0" . '*' . "\0" . '_hidden";a:0:{}s:11:"' . "\0" . '*' . "\0" . '_virtual";a:0:{}s:13:"' . "\0" . '*' . "\0" . '_className";N;s:9:"' . "\0" . '*' . "\0" . '_dirty";a:1:{s:7:"_fields";b:1;}s:7:"' . "\0" . '*' . "\0" . '_new";b:0;s:10:"' . "\0" . '*' . "\0" . '_errors";a:0:{}s:11:"' . "\0" . '*' . "\0" . '_invalid";a:0:{}s:14:"' . "\0" . '*' . "\0" . '_accessible";a:1:{s:1:"*";b:1;}s:17:"' . "\0" . '*' . "\0" . '_registryAlias";s:16:"Content.Contents";}',
    'hash' => '426b2c60697365b74a1905aaef4c0a41',
    'created' => '2016-12-04 20:52:11',
    ],
    ];
}
