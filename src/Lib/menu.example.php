<?php
include 'menu.class.php';
include 'tidy_menu.class.php';

$menu = menu::factory()
			->add('About Us', '/about-us/', menu::factory()
				->add('Who We Are', '/who-we-are/')
				->add('What We Do', '/what-we-do/')
				->add('Other Things', '/other-things/'))
			->add('Random', '/random/', menu::factory()
				->add('Link One', '/link-one/')
				->add('Link Two', '/link-two/', menu::factory()
					->add('Level Three', '/level-three/')));
					
$menu->attrs = array
(
	'id' => 'navigation',
	'class' => 'menu',
);

$menu->current = '/level-three/';

echo $menu;

echo "\n<hr />";

echo tidy_menu::factory($menu)->render();

echo "\n<hr />";

$menu2_items = array
(
	array
	(
		'title' => 'About Us',
		'url' => '/about-us/',
		'children' => array
		(
			array
			(
				'title' => 'Who We Are',
				'url' => '/who-we-are/',
			),

			array
			(
				'title' => 'What We Do',
				'url' => '/what-we-do/',
			),

			array
			(
				'title' => 'Other Things',
				'url' => '/other-things/',
			),
		),
	),
	array
	(
		'title' => 'Random',
		'url' => '/random/',
		'children' => array
		(
			array
			(
				'title' => 'Link One',
				'url' => '/link-one/',
			),

			array
			(
				'title' => 'Link Two',
				'url' => '/link-two/',
				'children' => array
				(
					array
					(
						'title' => 'Level Three',
						'url' => '/level-three/',
					),
				),
			),
		),
	),
);

$menu2_attrs = array
(
	'id' => 'menu2',
	'class' => 'navigation',
);

$menu2_current = '/random/';

echo menu::factory($menu2_items)->render($menu2_attrs, $menu2_current);

echo "\n<hr />\n";

$menu = array
(
	array
	(
		'title' => 'Item One',
		'url' => 'item_one.php',
	),
	array
	(
		'title' => 'Item Two',
		'url' => 'item_two.php',
		'children' => array
		(
			array
			(
				'title' => 'Item Three',
				'url' => 'item_three.php',
			),
			array
			(
				'title' => 'Item Four',
				'url' => 'item_four.php',
			)
		)
	)
);
			
$attrs = array('id' => 'menu');
$active = $_SERVER['REQUEST_URI'];

echo menu::factory($menu)->render($attrs, $active);