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

<div class="row">
	<div class="col-md-6">
		<div class="btn-group filters">
			<?php
				echo $this->Html->link(__d('system', 'Front Themes') . ' <span class="badge">' . $frontCount . '</span>', '#show-front', [
					'class' => 'btn btn-primary btn-sm btn-front',
					'escape' => false,
				]);
			?>
			<?php
				echo $this->Html->link(__d('system', 'Back Themes') . ' <span class="badge">' . $backCount . '</span>', '#show-back', [
					'class' => 'btn btn-info btn-sm btn-back',
					'escape' => false,
				]);
			?>
		</div>
	</div>

	<div class="col-md-6 text-right">
		<?php
			echo $this->Html->link(__d('system', 'Install theme'), [
				'plugin' => 'System',
				'controller' => 'themes',
				'action' => 'install',
			], [
				'class' => 'btn btn-primary'
			]);
		?>
	</div>
</div>

<div class="row">
	<div class="col-md-3 pull-right">
		<p>
			<div class="input-group">
				<span class="input-group-addon"><?php echo __d('system', 'Filter by'); ?></span>
				<?php echo $this->Form->input('filter-input', ['class' => 'filter-input', 'label' => false]) ?>
			</div>
		</p>
	</div>
</div>

<div class="row themes-container">
	<div class="col-md-12 front-themes themes-list">
		<?php foreach ($frontThemes as $theme): ?>
			<?php echo $this->element('System.theme_item', ['theme' => $theme]); ?>
		<?php endforeach; ?>
	</div>

	<div class="col-md-12 back-themes themes-list">
		<?php foreach ($backThemes as $theme): ?>
			<?php echo $this->element('System.theme_item', ['theme' => $theme]); ?>
		<?php endforeach; ?>
	</div>
</div>

<?php echo $this->Html->script('System.themes.management.js'); ?>