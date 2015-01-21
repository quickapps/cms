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
			'title' => '<span class="glyphicon glyphicon-file"></span> ' . __d('node', 'New Content'),
			'url' => '/admin/node/manage/create',
			'activation' => 'any',
			'active' => '/admin/node/manage/create*',
		],
		[
			'title' => '<span class="glyphicon glyphicon-comment"></span> ' . __d('node', 'Comments'),
			'url' => '/admin/node/comments/',
			'activation' => 'any',
			'active' => '/admin/node/comments*',
		],
	];

	echo $this->Menu->render($menuItems, ['class' => 'nav nav-pills']);