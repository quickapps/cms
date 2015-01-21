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

<?php $type = $theme['composer']['extra']['admin'] ? 'back_theme' : 'front_theme'; ?>
<div class="theme-box col-sm-4 col-md-3">
	<div class="thumbnail">
		<?php
			echo $this->Html->image([
				'plugin' => 'System',
				'controller' => 'themes',
				'action' => 'screenshot',
				$theme['name']
			], [
				'style' => 'width:100%;'
			]);
		?>
		<div class="caption">
			<h4>
				<?php if ($theme['name'] === option($type)): ?>
					<strong><?php echo __d('system', 'Active'); ?>:</strong>
				<?php endif; ?>
				<?php echo $theme['human_name']; ?>
			</h4>
			<p><small><em><?php echo $this->Text->truncate($theme['composer']['description'], 80); ?></em></small></p>
			<p>
				<div class="btn-group">
					<?php
						echo $this->Html->link(__d('system', 'Details'), [
							'plugin' => 'System',
							'controller' => 'themes',
							'action' => 'details',
							$theme['name'],
						], [
							'class' => 'btn btn-info btn-xs'
						]);
					?>
					<?php if ($theme['name'] === option($type) && $theme['hasSettings']): ?>
						<?php
							echo $this->Html->link(__d('system', 'Customize'), [
								'plugin' => 'System',
								'controller' => 'themes',
								'action' => 'settings',
								$theme['name'],
							], [
								'class' => 'btn btn-default btn-xs',
							]);
						?>
					<?php endif; ?>
					<?php if ($theme['name'] !== option($type)): ?>
						<?php
							echo $this->Html->link(__d('system', 'Activate'), [
								'plugin' => 'System',
								'controller' => 'themes',
								'action' => 'activate',
								$theme['name'],
							], [
								'class' => 'btn btn-default btn-xs'
							]);
						?>
						<?php if (!$theme['isCore']): ?>
							<?php
								echo $this->Html->link(__d('system', 'Uninstall'), [
									'plugin' => 'System',
									'controller' => 'themes',
									'action' => 'uninstall',
									$theme['name'],
								], [
								'confirm' => __d('system', 'Delete this theme? This operation cannot be undone!'),
									'class' => 'btn btn-default btn-xs',
								]);
							?>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</p>
		</div>
	</div>
</div>