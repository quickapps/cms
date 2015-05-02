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

$menuItems = [[
    'title' => '<span class="glyphicon glyphicon-th-list"></span> ' . __d('user', 'Users'),
    'url' => '/admin/user/manage/',
    'activation' => 'any',
    'active' => "/admin/user/manage\n/admin/user/manage/\n/admin/user\n/admin/user/\n/admin/user/manage/add\n/admin/user/manage/add/",
], [
    'title' => '<span class="glyphicon glyphicon-user"></span> ' . __d('user', 'Roles'),
    'url' => ['plugin' => 'User', 'controller' => 'roles', 'action' => 'index'],
    'activation' => 'any',
    'active' => '/admin/user/roles*',
], [
    'title' => '<span class="glyphicon glyphicon-lock"></span> ' . __d('user', 'Permissions'),
    'url' => ['plugin' => 'User', 'controller' => 'permissions', 'action' => 'index'],
    'activation' => 'any',
    'active' => '/admin/user/permissions*',
], [
    'title' => '<span class="glyphicon glyphicon-wrench"></span> ' . __d('user', 'Virtual Fields'),
    'url' => ['plugin' => 'User', 'controller' => 'fields', 'action' => 'index'],
    'activation' => 'any',
    'active' => '/admin/user/fields*',
]];
?>

<p><?php echo $this->Menu->render($menuItems, ['class' => 'nav nav-pills']); ?></p>