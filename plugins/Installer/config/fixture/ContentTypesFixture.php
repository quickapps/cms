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
class ContentTypesFixture
{

    /**
     * Table name.
     *
     * @var string
     */
    public $table = 'content_types';
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
    'content_types_slug' =>
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
    'slug' =>
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
    'name' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => false,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => 'human-readable name',
    'precision' => null,
    'fixed' => null,
    ],
    'description' =>
    [
    'type' => 'string',
    'length' => 255,
    'null' => true,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
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
    'collate' => 'utf8_unicode_ci',
    'comment' => 'the label displayed for the title field on the edit form.',
    'precision' => null,
    'fixed' => null,
    ],
    'defaults' =>
    [
    'type' => 'binary',
    'length' => null,
    'null' => true,
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
    public $records = null;
}
