<?php
class QaMenu {
	public $table = 'menus';
	public $records = array(
		array(
			'id' => 'main-menu',
			'title' => 'Main menu',
			'description' => 'The <em>Main</em> menu is used on many sites to show the major sections of the site, often in a top navigation bar.',
			'module' => 'System'
		),
		array(
			'id' => 'management',
			'title' => 'Management',
			'description' => 'The <em>Management</em> menu contains links for administrative tasks.',
			'module' => 'System'
		),
		array(
			'id' => 'navigation',
			'title' => 'Navigation',
			'description' => 'The <em>Navigation</em> menu contains links intended for site visitors. Links are added to the <em>Navigation</em> menu automatically by some modules.',
			'module' => 'System'
		),
		array(
			'id' => 'user-menu',
			'title' => 'User menu',
			'description' => 'The <em>User</em> menu contains links related to the user\'s account, as well as the \'Log out\' link.',
			'module' => 'System'
		),
	);

}
