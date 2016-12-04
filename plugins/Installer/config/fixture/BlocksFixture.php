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
class BlocksFixture
{

    /**
     * Table name.
     *
     * @var string
     */
    public $table = 'blocks';
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
    'comment' => 'Primary Key - Unique block ID.',
    'autoIncrement' => true,
    'precision' => null,
    ],
    'copy_id' =>
    [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => true,
    'default' => null,
    'comment' => 'id of the block this block is a copy of',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'handler' =>
    [
    'type' => 'string',
    'length' => 100,
    'null' => false,
    'default' => 'Block',
    'collate' => 'utf8_unicode_ci',
    'comment' => 'Name of the plugin that created this block. Used to generate event name, e.g. "Menu" triggers "Block.Menu.display" when rendering the block',
    'precision' => null,
    'fixed' => null,
    ],
    'title' =>
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
    'description' =>
    [
    'type' => 'string',
    'length' => 200,
    'null' => true,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'body' =>
    [
    'type' => 'text',
    'length' => 4294967295,
    'null' => true,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => '',
    'precision' => null,
    ],
    'visibility' =>
    [
    'type' => 'string',
    'length' => 8,
    'null' => false,
    'default' => 'except',
    'collate' => 'utf8_unicode_ci',
    'comment' => 'indicate how to show blocks on pages. (except = show on all pages except listed pages; only = show only on listed pages; php = use custom PHP code to determine visibility)',
    'precision' => null,
    'fixed' => null,
    ],
    'pages' =>
    [
    'type' => 'text',
    'length' => null,
    'null' => true,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => 'Contents of the "Pages" block contains either a list of paths on which to include/exclude the block or PHP code, depending on "visibility" setting.',
    'precision' => null,
    ],
    'locale' =>
    [
    'type' => 'text',
    'length' => null,
    'null' => true,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => '',
    'precision' => null,
    ],
    'settings' =>
    [
    'type' => 'binary',
    'length' => null,
    'null' => true,
    'default' => null,
    'comment' => 'additional information used by this block, used by blocks handlers <> `Block`',
    'precision' => null,
    ],
    'status' =>
    [
    'type' => 'boolean',
    'length' => null,
    'null' => false,
    'default' => '0',
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
