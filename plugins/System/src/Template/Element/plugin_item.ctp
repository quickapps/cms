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
	$classes = [];
	$classes[] = $plugin['status'] ? 'panel-success' : 'panel-danger';
	$classes[] = $plugin['status'] ? 'panel-enabled' : 'panel-disabled';
	$classes[] = $plugin['isCore'] ? 'panel-core' : 'panel-third-party';
?>
<div class="plugin-panel panel <?php echo implode(' ', $classes); ?>">
	<div class="panel-heading">
		<strong class="plugin-name"><?php echo $plugin['human_name']; ?></strong> (<?php echo $plugin['composer']['version']; ?>)
		<div class="btn-group pull-right">
			<?php
				echo $this->Html->link('', [
					'plugin' => 'User',
					'controller' => 'permissions',
					'action' => 'index',
					'prefix' => 'admin',
					'expand' => $plugin['name'],
				], [
					'title' => __d('system', 'Permissons'),
					'class' => 'btn btn-default btn-xs glyphicon glyphicon-lock',
				]);
			?>

			<?php if ($plugin['status'] && $plugin['hasHelp']): ?>
				<?php
					echo $this->Html->link('', [
						'plugin' => 'System',
						'controller' => 'help',
						'action' => 'about',
						'prefix' => 'admin',
						$plugin['name'],
					], [
						'title' => __d('system', 'Help'),
						'class' => 'btn btn-default btn-xs glyphicon glyphicon-question-sign',
					]);
				?>
			<?php endif; ?>

			<?php if ($plugin['status'] && $plugin['hasSettings']): ?>
				<?php
					echo $this->Html->link('', [
						'plugin' => 'System',
						'controller' => 'plugins',
						'action' => 'settings',
						'prefix' => 'admin',
						$plugin['name'],
					], [
						'title' => __d('system', 'Settings'),
						'class' => 'btn btn-default btn-xs glyphicon glyphicon-cog',
					]);
				?>
			<?php endif; ?>

			<?php if (!$plugin['isCore']): ?>
				<?php if (!$plugin['status']): ?>
					<?php
						echo $this->Html->link('', [
							'plugin' => 'System',
							'controller' => 'plugins',
							'action' => 'enable',
							'prefix' => 'admin',
							$plugin['name'],
						], [
							'title' => __d('system', 'Enable'),
							'class' => 'btn btn-default btn-xs glyphicon glyphicon-ok-circle',
						]);
					?>
				<?php else: ?>
					<?php
						echo $this->Html->link('', [
							'plugin' => 'System',
							'controller' => 'plugins',
							'action' => 'disable',
							'prefix' => 'admin',
							$plugin['name'],
						], [
							'title' => __d('system', 'Disable'),
							'confirm' => __d('system', 'Disable this this plugin?, are you sure?'),
							'class' => 'btn btn-default btn-xs glyphicon glyphicon-remove-circle',
						]);
					?>
				<?php endif; ?>

				<?php
					echo $this->Html->link('', [
						'plugin' => 'System',
						'controller' => 'plugins',
						'action' => 'delete',
						'prefix' => 'admin',
						$plugin['name'],
					], [
						'title' => __d('system', 'Delete'),
						'confirm' => __d('system', 'Delete this plugin? This operation cannot be undone!'),
						'class' => 'btn btn-default btn-xs glyphicon glyphicon-trash',
					]);
				?>
			<?php endif; ?>
		</div>
	</div>

	<div class="panel-body">
		<em class="help-block description"><?php echo $plugin['composer']['description']; ?></em>

		<div class="extended-info" style="display:none;">
			<?php echo $this->element('System.composer_details', ['composer' => $plugin['composer']]); ?>
		</div>

		<a href="" class="btn btn-default btn-xs glyphicon glyphicon-arrow-down toggler"></a>
	</div>
</div>