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

<h2>About</h2>
<p>
	The Menu plugin provides an interface for managing menus. A menu is a hierarchical collection of links, which can be within or
	external to the site, generally used for navigation. Each menu is rendered in a block that can be enabled and positioned through
	the <?php echo $this->Html->link('Blocks administration page', ['plugin' => 'Block', 'controller' => 'manage', 'prefix' => 'admin']); ?>.
	You can view and manage menus on the <?php echo $this->Html->link('Menus administration page', ['plugin' => 'Menu', 'controller' => 'manage', 'prefix' => 'admin']); ?>
</p>

<h2>Uses</h2>
<dl>
	<dt>Managing menus</dt>
	<dd>
		Users with the proper permissions can add, edit and delete custom menus on the <?php echo $this->Html->link('Menus administration page', ['plugin' => 'Menu', 'controller' => 'manage', 'prefix' => 'admin']); ?>.
		Custom menus can be special site menus, menus of external links, or any combination of internal and external links.
		You may create an unlimited number of additional menus, each of which will automatically have an associated block.
		By selecting <em>links</em>, you edit, sort or delete links for a given menu. The links listing page provides a
		drag-and-drop interface for controlling the order of links, and creating a hierarchy within the menu.
	</dd>

	<dt>Displaying menus</dt>
	<dd>
		After you have created a menu, you must enable and position the associated block on the
		<?php echo $this->Html->link('Blocks administration page', ['plugin' => 'Block', 'controller' => 'manage', 'prefix' => 'admin']); ?>.
	</dd>
</dl>