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

<div class="text-right">
	<?php
		echo $this->Html->link(__d('menu', 'Create new menu'), [
			'plugin' => 'Menu',
			'controller' => 'manage',
			'action' => 'add'
		], [
			'class' => 'btn btn-primary'
		]);
	?>
</div>

<p>
	<?php if ($menus->count() > 0): ?>
	<?php foreach ($menus as $menu): ?>
		<div class="clearfix">
			<p>
				<div class="btn-group pull-right">
					<?php
						echo $this->Html->link('', [
							'plugin' => 'Menu',
							'controller' => 'manage',
							'action' => 'edit',
							$menu->id
						], [
							'title' => __d('node', 'Edit menu information'),
							'class' => 'btn btn-default glyphicon glyphicon-pencil',
						]);
					?>
					<?php
						echo $this->Html->link('', [
							'plugin' => 'Menu',
							'controller' => 'links',
							'action' => 'menu',
							$menu->id
						], [
							'title' => __d('node', 'Manage links'),
							'class' => 'btn btn-default glyphicon glyphicon-link',
						]);
					?>
					<?php
						echo $this->Html->link('', [
							'plugin' => 'Menu',
							'controller' => 'links',
							'action' => 'add',
							$menu->id
						], [
							'title' => __d('node', 'Add link'),
							'class' => 'btn btn-default glyphicon glyphicon-plus',
						]);
					?>
					<?php if ($menu->handler === 'Menu'): ?>
					<?php
						echo $this->Html->link('', [
							'plugin' => 'Menu',
							'controller' => 'manage',
							'action' => 'delete',
							$menu->id
						], [
							'title' => __d('node', 'Delete this menu'),
							'confirm' => __d('node', 'Delete this menu ? All links within this menu will be lost.'),
							'class' => 'btn btn-default glyphicon glyphicon-trash',
						]);
					?>
					<?php endif; ?>
				</div>
				<h4><?php echo $menu->title; ?></h4>
				<p class="list-group-item-text"><em><?php echo $menu->brief_description; ?></em></p>
			</p>
		</div>
	<?php endforeach; ?>
	<?php else: ?>
		<div class="alert alert-warning">
			<?php echo __d('taxonomy', 'There are no menus yet, click on "Create new menu" button to add one.'); ?>
		</div>
	<?php endif; ?>
</p>
