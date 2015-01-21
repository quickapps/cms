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
?>

<?php
	$menuItems = [
		[
			'title' => '<span class="glyphicon glyphicon-th-list"></span> ' . __d('node', 'User List'),
			'url' => '/admin/user/manage/',
			'activation' => 'any',
			'active' => '/admin/user/manage
/admin/user/manage/
/admin/user
/admin/user/',
		],
		[
			'title' => '<span class="glyphicon glyphicon-plus"></span> ' . __d('node', 'New User'),
			'url' => ['plugin' => 'User', 'controller' => 'manage', 'action' => 'add'],
			'activation' => 'any',
			'active' => '/admin/user/manage/add',
		],
		[
			'title' => '<span class="glyphicon glyphicon-user"></span> ' . __d('node', 'User Roles'),
			'url' => ['plugin' => 'User', 'controller' => 'roles', 'action' => 'index'],
			'activation' => 'any',
			'active' => '/admin/user/roles*',
		],
		[
			'title' => '<span class="glyphicon glyphicon-lock"></span> ' . __d('node', 'Permissions'),
			'url' => ['plugin' => 'User', 'controller' => 'permissions', 'action' => 'index'],
			'activation' => 'any',
			'active' => '/admin/user/permissions*',
		],
		[
			'title' => '<span class="glyphicon glyphicon-wrench"></span> ' . __d('node', "User's Fields"),
			'url' => ['plugin' => 'User', 'controller' => 'fields', 'action' => 'index'],
			'activation' => 'any',
			'active' => '/admin/user/fields*',
		],
	];

	echo $this->Menu->render($menuItems, ['class' => 'nav nav-pills']);