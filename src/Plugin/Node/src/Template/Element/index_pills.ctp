<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<?php
$menu = [
	[
		'title' => __('New Content'),
		'url' => '/admin/node/manage/create',
		'selected_on_type' => 'reg',
		'selected_on' => '/admin/node/manage/create*',
	],
	[
		'title' => __('Comments'),
		'url' => '/admin/comment/manage/published',
		'selected_on_type' => 'reg',
		'selected_on' => '/admin/comment/manage/published*',
	],
];

echo $this->Menu->render($menu, ['class' => 'nav nav-pills']);