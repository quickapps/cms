<?php
$menu = [];

foreach ($plugins as $plugin) {
	$menu[] = [
		'title' => $plugin,
		'url' => "/admin/system/help/about/{$plugin}",
	];
}

echo $this->Menu->render($menu,
	[
		'split' => 3,
		'templates' => [
			'parent' => '<ul class="col-md-4">{{content}}</ul>',
		]
	]
);