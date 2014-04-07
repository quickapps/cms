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