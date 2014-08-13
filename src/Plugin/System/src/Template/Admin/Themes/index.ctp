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

<div class="row">
	<div class="col-md-6">
		<div class="btn-group filters">
			<?php
				echo $this->Html->link(__d('system', 'Front Themes') . ' <span class="badge">' . $front_count . '</span>', '#show-front', [
					'class' => 'btn btn-info btn-sm',
					'escape' => false,
				]);
			?>
			<?php
				echo $this->Html->link(__d('system', 'Back Themes') . ' <span class="badge">' . $back_count . '</span>', '#show-back', [
					'class' => 'btn btn-warning btn-sm',
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

<hr />

<div class="row themes-container">
	<div class="col-md-12 front-themes themes-list">
		<?php foreach ($front_themes as $theme): ?>
			<?php echo $this->element('System.theme_item', ['theme' => $theme]); ?>
		<?php endforeach; ?>
	</div>

	<div class="col-md-12 back-themes themes-list">
		<?php foreach ($back_themes as $theme): ?>
			<?php echo $this->element('System.theme_item', ['theme' => $theme]); ?>
		<?php endforeach; ?>
	</div>
</div>

<?php echo $this->Html->script('System.themes.management.js'); ?>