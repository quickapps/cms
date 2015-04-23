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

    public $table = 'menu_links';

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
    'comment' => 'php code, or regular expression. based on active_on_type',
    'precision' => null,
    ],
    'activation' =>
    [
    'type' => 'string',
    'length' => 5,
    'null' => true,
    'default' => 'auto',
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
    'description' => null,
    'title' => 'Structure',
    'target' => '_self',
    'expanded' => 0,
    'active' => null,
    'activation' => 'auto',
    'status' => true,
    ],
    2 =>
    [
    'id' => 3,
    'menu_id' => 1,
    'lft' => 11,
    'rght' => 12,
    'parent_id' => 0,
    'url' => '/admin/node/manage',
    'description' => null,
    'title' => 'Content',
    'target' => '_self',
    'expanded' => 0,
    'active' => '/admin/node/manage*
/admin/node/comments*
/admin/node/types*
/admin/node/fields*',
    'activation' => 'any',
    'status' => true,
    ],
    3 =>
    [
    'id' => 4,
    'menu_id' => 1,
    'lft' => 13,
    'rght' => 14,
    'parent_id' => 0,
    'url' => '/admin/system/themes',
    'description' => null,
    'title' => 'Themes',
    'target' => '_self',
    'expanded' => 0,
    'active' => null,
    'activation' => null,
    'status' => true,
    ],
    4 =>
    [
    'id' => 5,
    'menu_id' => 1,
    'lft' => 15,
    'rght' => 16,
    'parent_id' => 0,
    'url' => '/admin/system/plugins',
    'description' => null,
    'title' => 'Plugins',
    'target' => '_self',
    'expanded' => 0,
    'active' => null,
    'activation' => null,
    'status' => true,
    ],
    5 =>
    [
    'id' => 6,
    'menu_id' => 1,
    'lft' => 17,
    'rght' => 18,
    'parent_id' => 0,
    'url' => '/admin/user/manage',
    'description' => null,
    'title' => 'Users',
    'target' => '_self',
    'expanded' => 0,
    'active' => null,
    'activation' => null,
    'status' => true,
    ],
    6 =>
    [
    'id' => 7,
    'menu_id' => 1,
    'lft' => 21,
    'rght' => 22,
    'parent_id' => 0,
    'url' => '/admin/system/configuration',
    'description' => null,
    'title' => 'Configuration',
    'target' => '_self',
    'expanded' => 0,
    'active' => null,
    'activation' => null,
    'status' => true,
    ],
    7 =>
    [
    'id' => 8,
    'menu_id' => 1,
    'lft' => 23,
    'rght' => 24,
    'parent_id' => 0,
    'url' => '/admin/system/help',
    'description' => null,
    'title' => 'Help',
    'target' => '_self',
    'expanded' => 0,
    'active' => null,
    'activation' => null,
    'status' => true,
    ],
    8 =>
    [
    'id' => 9,
    'menu_id' => 1,
    'lft' => 4,
    'rght' => 5,
    'parent_id' => 2,
    'url' => '/admin/block/manage',
    'description' => 'Configure what block content appears in your site\'s sidebars and other regions.',
    'title' => 'Blocks',
    'target' => '_self',
    'expanded' => 0,
    'active' => null,
    'activation' => null,
    'status' => true,
    ],
    9 =>
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
    10 =>
    [
    'id' => 12,
    'menu_id' => 1,
    'lft' => 8,
    'rght' => 9,
    'parent_id' => 2,
    'url' => '/admin/taxonomy/manage',
    'description' => 'Manage tagging, categorization, and classification of your content.',
    'title' => 'Taxonomy',
    'target' => '_self',
    'expanded' => 0,
    'active' => null,
    'activation' => null,
    'status' => true,
    ],
    11 =>
    [
    'id' => 13,
    'menu_id' => 1,
    'lft' => 19,
    'rght' => 20,
    'parent_id' => 0,
    'url' => '/admin/locale',
    'description' => '',
    'title' => 'Languages',
    'target' => '_self',
    'expanded' => 0,
    'active' => null,
    'activation' => null,
    'status' => true,
    ],
    12 =>
    [
    'id' => 14,
    'menu_id' => 2,
    'lft' => 3,
    'rght' => 4,
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
    'id' => 16,
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
    'id' => 17,
    'menu_id' => 2,
    'lft' => 5,
    'rght' => 6,
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
    ];
}
