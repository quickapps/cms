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

class NodeTypesFixture
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
    'node_types_slug' =>
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
    'length' => 100,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'name' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => null,
    'comment' => 'human-readable name',
    'precision' => null,
    'fixed' => null,
    ],
    'description' =>
    [
    'type' => 'string',
    'length' => 255,
    'null' => false,
    'default' => null,
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'title_label' =>
    [
    'type' => 'string',
    'length' => 80,
    'null' => false,
    'default' => null,
    'comment' => 'the label displayed for the title field on the edit form.',
    'precision' => null,
    'fixed' => null,
    ],
    'defaults' =>
    [
    'type' => 'text',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => '',
    'precision' => null,
    ],
    ];

    public $records = [
    0 =>
    [
    'id' => 1,
    'slug' => 'article',
    'name' => 'Article',
    'description' => 'Use <em>Articles</em> for time-sensitive content like news, press releases or blog posts.',
    'title_label' => 'Title',
    'defaults' => 'a:7:{s:6:"status";s:1:"1";s:7:"promote";s:1:"1";s:6:"sticky";s:1:"1";s:11:"author_name";s:1:"1";s:9:"show_date";s:1:"1";s:14:"comment_status";s:1:"1";s:8:"language";s:0:"";}',
    ],
    1 =>
    [
    'id' => 2,
    'slug' => 'page',
    'name' => 'Basic Page',
    'description' => 'Use <em>Basic Pages</em> for your static content, such as an \'About us\' page.',
    'title_label' => 'Title',
    'defaults' => 'a:7:{s:6:"status";s:1:"1";s:7:"promote";s:1:"0";s:6:"sticky";s:1:"0";s:11:"author_name";s:1:"0";s:9:"show_date";s:1:"0";s:14:"comment_status";s:1:"0";s:8:"language";s:0:"";}',
    ],
    ];
}
