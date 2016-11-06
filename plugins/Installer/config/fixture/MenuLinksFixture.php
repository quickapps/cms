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
class MenuLinksFixture
{

    /**
     * Table name.
     *
     * @var string
     */
    public $table = 'menu_links';
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
    'menu_links_menu_id_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'menu_id',
      ],
      'length' =>
      [
      ],
    ],
    'menu_links_lft_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'lft',
      ],
      'length' =>
      [
      ],
    ],
    'menu_links_rght_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'rght',
      ],
      'length' =>
      [
      ],
    ],
    'menu_links_parent_id_index' =>
    [
      'type' => 'index',
      'columns' =>
      [
        0 => 'parent_id',
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
    'menu_id' =>
    [
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => null,
    'comment' => 'All links with the same menu ID are part of the same menu.',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'lft' =>
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
    'rght' =>
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
    'parent_id' =>
    [
    'type' => 'integer',
    'length' => 10,
    'unsigned' => false,
    'null' => false,
    'default' => '0',
    'comment' => 'The parent link ID (plid) is the mlid of the link above in the hierarchy, or zero if the link is at the top level in its menu.',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'url' =>
    [
    'type' => 'string',
    'length' => 255,
    'null' => true,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => 'the url',
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
    'title' =>
    [
    'type' => 'string',
    'length' => 255,
    'null' => true,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => 'The text displayed for the link, which may be modified by a title callback stored in menu_router.',
    'precision' => null,
    'fixed' => null,
    ],
    'target' =>
    [
    'type' => 'string',
    'length' => 15,
    'null' => false,
    'default' => '_self',
    'collate' => 'utf8_unicode_ci',
    'comment' => '',
    'precision' => null,
    'fixed' => null,
    ],
    'expanded' =>
    [
    'type' => 'integer',
    'length' => 1,
    'unsigned' => false,
    'null' => false,
    'default' => '1',
    'comment' => 'Flag for whether this link should be rendered as expanded in menus - expanded links always have their child links displayed, instead of only when the link is in the active trail (1 = expanded, 0 = not expanded)',
    'precision' => null,
    'autoIncrement' => null,
    ],
    'active' =>
    [
    'type' => 'text',
    'length' => null,
    'null' => true,
    'default' => null,
    'collate' => 'utf8_unicode_ci',
    'comment' => 'php code, or regular expression. based on active_on_type',
    'precision' => null,
    ],
    'activation' =>
    [
    'type' => 'string',
    'length' => 5,
    'null' => true,
    'default' => 'auto',
    'collate' => 'utf8_unicode_ci',
    'comment' => 'php: on php return TRUE. auto: auto-detect; any: request\'s URL matches any in "active" column; none: request\'s URL matches none of listed in "active" column',
    'precision' => null,
    'fixed' => null,
    ],
    'status' =>
    [
    'type' => 'boolean',
    'length' => null,
    'null' => false,
    'default' => '1',
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
    'menu_id' => 1,
    'lft' => 1,
    'rght' => 2,
    'parent_id' => 0,
    'url' => '/admin/system/dashboard',
    'description' => null,
    'title' => 'Dashboard',
    'target' => '_self',
    'expanded' => 1,
    'active' => '/admin/system/dashboard
/admin
/admin/',
    'activation' => 'any',
    'status' => true,
    ],
    1 =>
    [
    'id' => 2,
    'menu_id' => 1,
    'lft' => 3,
    'rght' => 10,
    'parent_id' => 0,
    'url' => '/admin/system/structure',
    'description' => '',
    'title' => 'Structure',
    'target' => '_self',
    'expanded' => 1,
    'active' => '',
    'activation' => 'auto',
    'status' => true,
    ],
    2 =>
    [
    'id' => 3,
    'menu_id' => 1,
    'lft' => 11,
    'rght' => 20,
    'parent_id' => 0,
    'url' => '/admin/content/manage',
    'description' => '',
    'title' => 'Content',
    'target' => '_self',
    'expanded' => 1,
    'active' => '/admin/content/manage*
/admin/content/comments*
/admin/content/types*
/admin/content/fields*',
    'activation' => 'any',
    'status' => true,
    ],
    3 =>
    [
    'id' => 4,
    'menu_id' => 1,
    'lft' => 21,
    'rght' => 22,
    'parent_id' => 0,
    'url' => '/admin/media_manager/explorer',
    'description' => '',
    'title' => 'Media',
    'target' => '',
    'expanded' => 1,
    'active' => '/admin/media_manager/explorer*',
    'activation' => 'any',
    'status' => true,
    ],
    4 =>
    [
    'id' => 5,
    'menu_id' => 1,
    'lft' => 23,
    'rght' => 28,
    'parent_id' => 0,
    'url' => '/admin/system/themes',
    'description' => '',
    'title' => 'Appearance',
    'target' => '_self',
    'expanded' => 1,
    'active' => 'admin/system/themes*',
    'activation' => 'any',
    'status' => true,
    ],
    5 =>
    [
    'id' => 6,
    'menu_id' => 1,
    'lft' => 29,
    'rght' => 34,
    'parent_id' => 0,
    'url' => '/admin/system/plugins',
    'description' => '',
    'title' => 'Extensions',
    'target' => '_self',
    'expanded' => 1,
    'active' => 'admin/system/plugins*',
    'activation' => 'any',
    'status' => true,
    ],
    6 =>
    [
    'id' => 7,
    'menu_id' => 1,
    'lft' => 35,
    'rght' => 44,
    'parent_id' => 0,
    'url' => '/admin/user/manage',
    'description' => '',
    'title' => 'Users & Security',
    'target' => '_self',
    'expanded' => 1,
    'active' => '/admin/user*',
    'activation' => 'any',
    'status' => true,
    ],
    7 =>
    [
    'id' => 8,
    'menu_id' => 1,
    'lft' => 45,
    'rght' => 50,
    'parent_id' => 0,
    'url' => '/admin/locale/',
    'description' => '',
    'title' => 'Languages',
    'target' => '_self',
    'expanded' => 1,
    'active' => '/admin/locale/*',
    'activation' => 'any',
    'status' => true,
    ],
    8 =>
    [
    'id' => 9,
    'menu_id' => 1,
    'lft' => 51,
    'rght' => 52,
    'parent_id' => 0,
    'url' => '/admin/system/configuration',
    'description' => '',
    'title' => 'Configuration',
    'target' => '_self',
    'expanded' => 0,
    'active' => '/admin/system/configuration*',
    'activation' => 'any',
    'status' => true,
    ],
    9 =>
    [
    'id' => 10,
    'menu_id' => 1,
    'lft' => 53,
    'rght' => 54,
    'parent_id' => 0,
    'url' => '/admin/system/help',
    'description' => '',
    'title' => 'Help',
    'target' => '_self',
    'expanded' => 0,
    'active' => '/admin/system/help*',
    'activation' => 'any',
    'status' => true,
    ],
    10 =>
    [
    'id' => 11,
    'menu_id' => 1,
    'lft' => 6,
    'rght' => 7,
    'parent_id' => 2,
    'url' => '/admin/menu/manage',
    'description' => 'Add new menus to your site, edit existing menus, and rename and reorganize menu links.',
    'title' => 'Menus',
    'target' => '_self',
    'expanded' => 0,
    'active' => null,
    'activation' => null,
    'status' => true,
    ],
    11 =>
    [
    'id' => 12,
    'menu_id' => 1,
    'lft' => 8,
    'rght' => 9,
    'parent_id' => 2,
    'url' => '/admin/taxonomy/vocabularies',
    'description' => 'Manage tagging, categorization, and classification of your content.',
    'title' => 'Taxonomy',
    'target' => '_self',
    'expanded' => 0,
    'active' => null,
    'activation' => null,
    'status' => true,
    ],
    12 =>
    [
    'id' => 13,
    'menu_id' => 2,
    'lft' => 5,
    'rght' => 6,
    'parent_id' => 0,
    'url' => '/page/about.html',
    'description' => '',
    'title' => 'About',
    'target' => '_self',
    'expanded' => 0,
    'active' => null,
    'activation' => null,
    'status' => true,
    ],
    13 =>
    [
    'id' => 14,
    'menu_id' => 2,
    'lft' => 1,
    'rght' => 2,
    'parent_id' => 0,
    'url' => '/',
    'description' => '',
    'title' => 'Home',
    'target' => '_self',
    'expanded' => 0,
    'active' => null,
    'activation' => null,
    'status' => true,
    ],
    14 =>
    [
    'id' => 15,
    'menu_id' => 2,
    'lft' => 3,
    'rght' => 4,
    'parent_id' => 0,
    'url' => '/find/type:article',
    'description' => '',
    'title' => 'Blog',
    'target' => '_self',
    'expanded' => 0,
    'active' => '/article/*.html
/find/*type:article*',
    'activation' => 'any',
    'status' => true,
    ],
    15 =>
    [
    'id' => 16,
    'menu_id' => 1,
    'lft' => 12,
    'rght' => 13,
    'parent_id' => 3,
    'url' => '/admin/content/manage/index',
    'description' => '',
    'title' => 'Contents List',
    'target' => '',
    'expanded' => 0,
    'active' => '',
    'activation' => 'auto',
    'status' => true,
    ],
    16 =>
    [
    'id' => 17,
    'menu_id' => 1,
    'lft' => 14,
    'rght' => 15,
    'parent_id' => 3,
    'url' => '/admin/content/manage/create',
    'description' => '',
    'title' => 'Create New Content',
    'target' => '',
    'expanded' => 0,
    'active' => '',
    'activation' => 'auto',
    'status' => true,
    ],
    17 =>
    [
    'id' => 18,
    'menu_id' => 1,
    'lft' => 16,
    'rght' => 17,
    'parent_id' => 3,
    'url' => '/admin/content/types',
    'description' => '',
    'title' => 'Content Types',
    'target' => '',
    'expanded' => 0,
    'active' => '/admin/content/types*
/admin/content/fields*',
    'activation' => 'any',
    'status' => true,
    ],
    18 =>
    [
    'id' => 19,
    'menu_id' => 1,
    'lft' => 18,
    'rght' => 19,
    'parent_id' => 3,
    'url' => '/admin/content/comments/',
    'description' => '',
    'title' => 'Comments',
    'target' => '',
    'expanded' => 0,
    'active' => '/admin/content/comments/*',
    'activation' => 'any',
    'status' => true,
    ],
    19 =>
    [
    'id' => 20,
    'menu_id' => 1,
    'lft' => 24,
    'rght' => 25,
    'parent_id' => 5,
    'url' => '/admin/system/themes/index',
    'description' => '',
    'title' => 'Themes',
    'target' => '',
    'expanded' => 0,
    'active' => '/admin/system/themes
/admin/system/themes/index',
    'activation' => 'any',
    'status' => true,
    ],
    20 =>
    [
    'id' => 21,
    'menu_id' => 1,
    'lft' => 26,
    'rght' => 27,
    'parent_id' => 5,
    'url' => '/admin/system/themes/install',
    'description' => '',
    'title' => 'Install New Theme',
    'target' => '',
    'expanded' => 0,
    'active' => '',
    'activation' => 'auto',
    'status' => true,
    ],
    21 =>
    [
    'id' => 22,
    'menu_id' => 1,
    'lft' => 30,
    'rght' => 31,
    'parent_id' => 6,
    'url' => '/admin/system/plugins/index',
    'description' => '',
    'title' => 'Plugins',
    'target' => '',
    'expanded' => 0,
    'active' => '',
    'activation' => 'auto',
    'status' => true,
    ],
    22 =>
    [
    'id' => 23,
    'menu_id' => 1,
    'lft' => 32,
    'rght' => 33,
    'parent_id' => 6,
    'url' => '/admin/system/plugins/install',
    'description' => '',
    'title' => 'Install New Plugin',
    'target' => '',
    'expanded' => 0,
    'active' => '',
    'activation' => 'auto',
    'status' => true,
    ],
    23 =>
    [
    'id' => 24,
    'menu_id' => 1,
    'lft' => 36,
    'rght' => 37,
    'parent_id' => 7,
    'url' => '/admin/user/manage/',
    'description' => '',
    'title' => 'Users List',
    'target' => '',
    'expanded' => 0,
    'active' => '/admin/user/manage/*',
    'activation' => 'any',
    'status' => true,
    ],
    24 =>
    [
    'id' => 25,
    'menu_id' => 1,
    'lft' => 38,
    'rght' => 39,
    'parent_id' => 7,
    'url' => '/admin/user/roles',
    'description' => '',
    'title' => 'User Roles',
    'target' => '',
    'expanded' => 0,
    'active' => '',
    'activation' => 'auto',
    'status' => true,
    ],
    25 =>
    [
    'id' => 26,
    'menu_id' => 1,
    'lft' => 40,
    'rght' => 41,
    'parent_id' => 7,
    'url' => '/admin/user/permissions',
    'description' => '',
    'title' => 'Permissions',
    'target' => '',
    'expanded' => 0,
    'active' => '',
    'activation' => 'auto',
    'status' => true,
    ],
    26 =>
    [
    'id' => 27,
    'menu_id' => 1,
    'lft' => 46,
    'rght' => 47,
    'parent_id' => 8,
    'url' => '/admin/locale/manage/index',
    'description' => '',
    'title' => 'Installed Languages',
    'target' => '',
    'expanded' => 0,
    'active' => '',
    'activation' => 'auto',
    'status' => true,
    ],
    27 =>
    [
    'id' => 28,
    'menu_id' => 1,
    'lft' => 48,
    'rght' => 49,
    'parent_id' => 8,
    'url' => '/admin/locale/manage/add',
    'description' => '',
    'title' => 'Add New Language',
    'target' => '',
    'expanded' => 0,
    'active' => '',
    'activation' => 'auto',
    'status' => true,
    ],
    28 =>
    [
    'id' => 29,
    'menu_id' => 1,
    'lft' => 42,
    'rght' => 43,
    'parent_id' => 7,
    'url' => '/admin/user/fields',
    'description' => '',
    'title' => 'Virtual Fields',
    'target' => '',
    'expanded' => 0,
    'active' => '/admin/user/fields*',
    'activation' => 'any',
    'status' => true,
    ],
    29 =>
    [
    'id' => 30,
    'menu_id' => 1,
    'lft' => 4,
    'rght' => 5,
    'parent_id' => 2,
    'url' => '/admin/block/manage',
    'description' => 'Configure what block content appears in your site\'s sidebars and other regions.',
    'title' => 'Blocks',
    'target' => '_self',
    'expanded' => 0,
    'active' => '/admin/block/*',
    'activation' => 'any',
    'status' => true,
    ],
    ];
}
