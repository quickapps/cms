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
	$menu = [];
	foreach ($plugins as $plugin) {
		$menu[] = [
			'title' => $plugin,
			'url' => "/admin/system/help/about/{$plugin}",
		];
	}

	echo $this->Menu->render($menu, [
		'split' => 3,
		'templates' => [
			'parent' => '<ul class="col-md-4">{{content}}</ul>',
		]
	]);
