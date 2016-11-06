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
class EavAttributesFixture
{

    /**
     * Table name.
     *
     * @var string
     */
    public $table = 'eav_attributes';
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
    'eav_attributes_table_alias_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'table_alias',
      ],
      'length' =>
      [
      ],
    ],
    'eav_attributes_bundle_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'bundle',
      ],
      'length' =>
      [
      ],
    ],
    'eav_attributes_name_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'name',
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
    'table_alias' =>
    [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'bundle' =>
    [
    'type' => 'string',
    'length' => 50,
    'null' => true,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'name' =>
    [
    'type' => 'string',
    'length' => 50,
    'null' => false,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'type' =>
    [
    'type' => 'string',
    'length' => 10,
    'null' => false,
    'default' => 'varchar',
    'collate' => 'utf8_unicode_ci',
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'searchable' =>
    [
    'type' => 'boolean',
    'length' => null,
    'null' => false,
    'default' => '1',
    'comment' => '',
    'precision' => null,
    ],
    'extra' =>
    [
    'type' => 'text',
    'length' => null,
    'null' => true,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
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
    'table_alias' => 'contents',
    'bundle' => 'article',
    'name' => 'article-introduction',
    'type' => 'text',
    'searchable' => true,
    'extra' => null,
    ],
    1 =>
    [
    'id' => 2,
    'table_alias' => 'contents',
    'bundle' => 'article',
    'name' => 'article-body',
    'type' => 'text',
    'searchable' => true,
    'extra' => null,
    ],
    2 =>
    [
    'id' => 3,
    'table_alias' => 'contents',
    'bundle' => 'article',
    'name' => 'article-category',
    'type' => 'text',
    'searchable' => true,
    'extra' => null,
    ],
    3 =>
    [
    'id' => 4,
    'table_alias' => 'contents',
    'bundle' => 'page',
    'name' => 'page-body',
    'type' => 'text',
    'searchable' => true,
    'extra' => null,
    ],
    ];
}
