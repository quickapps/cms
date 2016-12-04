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
    public $records = [
    0 =>
    [
    'id' => 1,
    'copy_id' => null,
    'handler' => 'Menu\\Widget\\MenuWidget',
    'title' => 'Management [menu:1]',
    'description' => 'Associated block for "Management" menu.',
    'body' => null,
    'visibility' => 'except',
    'pages' => null,
    'locale' => null,
    'settings' => 'a:1:{s:7:"menu_id";i:1;}',
    'status' => true,
    ],
    1 =>
    [
    'id' => 2,
    'copy_id' => null,
    'handler' => 'Menu\\Widget\\MenuWidget',
    'title' => 'Site Main Menu [menu:2]',
    'description' => 'Associated block for "Site Main Menu" menu.',
    'body' => null,
    'visibility' => 'except',
    'pages' => null,
    'locale' => null,
    'settings' => 'a:1:{s:7:"menu_id";i:2;}',
    'status' => true,
    ],
    2 =>
    [
    'id' => 3,
    'copy_id' => null,
    'handler' => 'Content\\Widget\\DashboardLatestContentWidget',
    'title' => 'Recent Content',
    'description' => 'Shows a list of latest created contents.',
    'body' => null,
    'visibility' => 'except',
    'pages' => null,
    'locale' => null,
    'settings' => null,
    'status' => true,
    ],
    3 =>
    [
    'id' => 4,
    'copy_id' => null,
    'handler' => 'Content\\Widget\\DashboardSearchWidget',
    'title' => 'Search',
    'description' => 'Quick Search Form',
    'body' => null,
    'visibility' => 'except',
    'pages' => null,
    'locale' => null,
    'settings' => null,
    'status' => true,
    ],
    4 =>
    [
    'id' => 5,
    'copy_id' => null,
    'handler' => 'Locale\\Widget\\LanguageSwitcherWidget',
    'title' => 'Change Language',
    'description' => 'Language switcher block',
    'body' => null,
    'visibility' => 'except',
    'pages' => '',
    'locale' => '',
    'settings' => 'a:2:{s:4:"type";s:4:"html";s:5:"flags";s:1:"1";}',
    'status' => true,
    ],
    5 =>
    [
    'id' => 6,
    'copy_id' => null,
    'handler' => 'Taxonomy\\Widget\\CategoriesWidget',
    'title' => 'Categories',
    'description' => 'List of terms block',
    'body' => null,
    'visibility' => 'except',
    'pages' => '',
    'locale' => '',
    'settings' => 'a:4:{s:12:"vocabularies";a:1:{i:0;s:1:"1";}s:13:"show_counters";s:1:"1";s:15:"show_vocabulary";s:1:"0";s:13:"link_template";s:0:"";}',
    'status' => true,
    ],
    6 =>
    [
    'id' => 7,
    'copy_id' => null,
    'handler' => 'User\\Widget\\UserMenuWidget',
    'title' => 'User sub-menu',
    'description' => 'Provides links to user\'s account, login, logout, etc',
    'body' => null,
    'visibility' => 'except',
    'pages' => '',
    'locale' => '',
    'settings' => null,
    'status' => true,
    ],
    7 =>
    [
    'id' => 8,
    'copy_id' => null,
    'handler' => 'Content\\Widget\\RecentContentWidget',
    'title' => 'Latest Contents',
    'description' => 'Lists of recently published contents.',
    'body' => null,
    'visibility' => 'except',
    'pages' => null,
    'locale' => null,
    'settings' => null,
    'status' => true,
    ],
    ];
}
