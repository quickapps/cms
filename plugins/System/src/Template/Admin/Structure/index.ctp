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
echo $this->Menu->render($links, [
	'class' => 'list-group',
	'formatter' => function ($item, $info) {
		$content = '<h4 class="list-group-item-heading">' . $item->title . '</h4>';
		$content .= '<p class="list-group-item-text">' . $item->description . '</p>';
		return	$this->Html->link($content, $item->url, [
			'class' => 'list-group-item',
			'escape' => false
		]);
	}
]);