<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    1.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace QuickApps\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

class MenuLinksFixture extends TestFixture {

	public $fields = array (
  '_constraints' => 
  array (
    'primary' => 
    array (
      'type' => 'primary',
      'columns' => 
      array (
        0 => 'id',
      ),
      'length' => 
      array (
      ),
    ),
  ),
  'id' => 
  array (
    'type' => 'integer',
    'length' => 10,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'autoIncrement' => true,
    'precision' => NULL,
  ),
  'menu_id' => 
  array (
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => 'All links with the same menu ID are part of the same menu.',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'lft' => 
  array (
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'rght' => 
  array (
    'type' => 'integer',
    'length' => 11,
    'unsigned' => false,
    'null' => false,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'parent_id' => 
  array (
    'type' => 'integer',
    'length' => 10,
    'unsigned' => false,
    'null' => false,
    'default' => '0',
    'comment' => 'The parent link ID (plid) is the mlid of the link above in the hierarchy, or zero if the link is at the top level in its menu.',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'url' => 
  array (
    'type' => 'string',
    'length' => 255,
    'null' => true,
    'default' => NULL,
    'comment' => 'the url',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'description' => 
  array (
    'type' => 'string',
    'length' => 200,
    'null' => true,
    'default' => NULL,
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'title' => 
  array (
    'type' => 'string',
    'length' => 255,
    'null' => true,
    'default' => NULL,
    'comment' => 'The text displayed for the link, which may be modified by a title callback stored in menu_router.',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'target' => 
  array (
    'type' => 'string',
    'length' => 15,
    'null' => false,
    'default' => '_self',
    'comment' => '',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'expanded' => 
  array (
    'type' => 'integer',
    'length' => 1,
    'unsigned' => false,
    'null' => false,
    'default' => '1',
    'comment' => 'Flag for whether this link should be rendered as expanded in menus - expanded links always have their child links displayed, instead of only when the link is in the active trail (1 = expanded, 0 = not expanded)',
    'precision' => NULL,
    'autoIncrement' => NULL,
  ),
  'active' => 
  array (
    'type' => 'text',
    'length' => NULL,
    'null' => true,
    'default' => NULL,
    'comment' => 'php code, or regular expression. based on active_on_type',
    'precision' => NULL,
  ),
  'activation' => 
  array (
    'type' => 'string',
    'length' => 5,
    'null' => true,
    'default' => 'auto',
    'comment' => 'php: on php return TRUE. auto: auto-detect; any: request\'s URL matches any in "active" column; none: request\'s URL matches none of listed in "active" column',
    'precision' => NULL,
    'fixed' => NULL,
  ),
  'status' => 
  array (
    'type' => 'boolean',
    'length' => NULL,
    'null' => false,
    'default' => '1',
    'comment' => '',
    'precision' => NULL,
  ),
);

	public $records = array (
  0 => 
  array (
    'id' => 1,
    'menu_id' => 1,
    'lft' => 1,
    'rght' => 2,
    'parent_id' => 0,
    'url' => '/admin/system/dashboard',
    'description' => NULL,
    'title' => 'Dashboard',
    'target' => '_self',
    'expanded' => 1,
    'active' => '/admin/system/dashboard
/admin
/admin/',
    'activation' => 'any',
    'status' => true,
  ),
  1 => 
  array (
    'id' => 2,
    'menu_id' => 1,
    'lft' => 3,
    'rght' => 12,
    'parent_id' => 0,
    'url' => '/admin/system/structure',
    'description' => NULL,
    'title' => 'Structure',
    'target' => '_self',
    'expanded' => 0,
    'active' => NULL,
    'activation' => 'auto',
    'status' => true,
  ),
  2 => 
  array (
    'id' => 3,
    'menu_id' => 1,
    'lft' => 13,
    'rght' => 14,
    'parent_id' => 0,
    'url' => '/admin/node/manage',
    'description' => NULL,
    'title' => 'Content',
    'target' => '_self',
    'expanded' => 0,
    'active' => '/admin/node/manage*
/admin/node/comments*',
    'activation' => 'any',
    'status' => true,
  ),
  3 => 
  array (
    'id' => 4,
    'menu_id' => 1,
    'lft' => 15,
    'rght' => 16,
    'parent_id' => 0,
    'url' => '/admin/system/themes',
    'description' => NULL,
    'title' => 'Themes',
    'target' => '_self',
    'expanded' => 0,
    'active' => NULL,
    'activation' => NULL,
    'status' => true,
  ),
  4 => 
  array (
    'id' => 5,
    'menu_id' => 1,
    'lft' => 17,
    'rght' => 18,
    'parent_id' => 0,
    'url' => '/admin/system/plugins',
    'description' => NULL,
    'title' => 'Plugins',
    'target' => '_self',
    'expanded' => 0,
    'active' => NULL,
    'activation' => NULL,
    'status' => true,
  ),
  5 => 
  array (
    'id' => 6,
    'menu_id' => 1,
    'lft' => 19,
    'rght' => 20,
    'parent_id' => 0,
    'url' => '/admin/user/manage',
    'description' => NULL,
    'title' => 'Users',
    'target' => '_self',
    'expanded' => 0,
    'active' => NULL,
    'activation' => NULL,
    'status' => true,
  ),
  6 => 
  array (
    'id' => 7,
    'menu_id' => 1,
    'lft' => 23,
    'rght' => 24,
    'parent_id' => 0,
    'url' => '/admin/system/configuration',
    'description' => NULL,
    'title' => 'Configuration',
    'target' => '_self',
    'expanded' => 0,
    'active' => NULL,
    'activation' => NULL,
    'status' => true,
  ),
  7 => 
  array (
    'id' => 8,
    'menu_id' => 1,
    'lft' => 25,
    'rght' => 26,
    'parent_id' => 0,
    'url' => '/admin/system/help',
    'description' => NULL,
    'title' => 'Help',
    'target' => '_self',
    'expanded' => 0,
    'active' => NULL,
    'activation' => NULL,
    'status' => true,
  ),
  8 => 
  array (
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
    'active' => NULL,
    'activation' => NULL,
    'status' => true,
  ),
  9 => 
  array (
    'id' => 10,
    'menu_id' => 1,
    'lft' => 6,
    'rght' => 7,
    'parent_id' => 2,
    'url' => '/admin/node/types',
    'description' => 'Manage content types.',
    'title' => 'Content Types',
    'target' => '_self',
    'expanded' => 0,
    'active' => NULL,
    'activation' => NULL,
    'status' => true,
  ),
  10 => 
  array (
    'id' => 11,
    'menu_id' => 1,
    'lft' => 8,
    'rght' => 9,
    'parent_id' => 2,
    'url' => '/admin/menu/manage',
    'description' => 'Add new menus to your site, edit existing menus, and rename and reorganize menu links.',
    'title' => 'Menus',
    'target' => '_self',
    'expanded' => 0,
    'active' => NULL,
    'activation' => NULL,
    'status' => true,
  ),
  11 => 
  array (
    'id' => 12,
    'menu_id' => 1,
    'lft' => 10,
    'rght' => 11,
    'parent_id' => 2,
    'url' => '/admin/taxonomy/manage',
    'description' => 'Manage tagging, categorization, and classification of your content.',
    'title' => 'Taxonomy',
    'target' => '_self',
    'expanded' => 0,
    'active' => NULL,
    'activation' => NULL,
    'status' => true,
  ),
  12 => 
  array (
    'id' => 13,
    'menu_id' => 1,
    'lft' => 21,
    'rght' => 22,
    'parent_id' => 0,
    'url' => '/admin/locale',
    'description' => '',
    'title' => 'Languages',
    'target' => '_self',
    'expanded' => 0,
    'active' => NULL,
    'activation' => NULL,
    'status' => true,
  ),
  13 => 
  array (
    'id' => 14,
    'menu_id' => 2,
    'lft' => 5,
    'rght' => 6,
    'parent_id' => 0,
    'url' => '/article/about.html',
    'description' => '',
    'title' => 'About',
    'target' => '_self',
    'expanded' => 0,
    'active' => NULL,
    'activation' => NULL,
    'status' => true,
  ),
  14 => 
  array (
    'id' => 15,
    'menu_id' => 2,
    'lft' => 3,
    'rght' => 4,
    'parent_id' => 0,
    'url' => '/article/hooktags.html',
    'description' => '',
    'title' => 'Hooktags',
    'target' => '_self',
    'expanded' => 0,
    'active' => NULL,
    'activation' => NULL,
    'status' => true,
  ),
  15 => 
  array (
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
    'active' => NULL,
    'activation' => NULL,
    'status' => true,
  ),
  16 => 
  array (
    'id' => 17,
    'menu_id' => 2,
    'lft' => 7,
    'rght' => 8,
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
  ),
);

}

