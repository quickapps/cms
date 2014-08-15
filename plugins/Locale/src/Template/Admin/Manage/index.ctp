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

<table class="table table-hover">
	<thead>
		<tr>
			<th><?php echo __d('locale', 'Name'); ?></th>
			<th class="hidden-xs"><?php echo __d('locale', 'Path'); ?></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($languages as $language): ?>
			<tr>
				<td>
					<?php if ($language->icon): ?>
						<?php echo $this->Html->image("Locale.flags/{$language->icon}"); ?>
					<?php endif; ?>
					<?php echo $language->name; ?>
				</td>
				<td class="hidden-xs">/<?php echo $language->code; ?></td>
				<td>
					<div class="btn-group">
						<?php if (option('default_language') !== $language->code): ?>
						<?php
							echo $this->Html->link('', [
								'plugin' => 'Locale',
								'controller' => 'manage',
								'action' => 'default',
								$language->id,
							], [
								'title' => __d('locale', 'Set as default'),
								'class' => 'btn btn-default btn-sm glyphicon glyphicon-star',
							]);
						?>
						<?php endif; ?>
						<?php
							echo $this->Html->link('', [
								'plugin' => 'Locale',
								'controller' => 'manage',
								'action' => 'delete',
								$language->id,
							], [
								'title' => __d('locale', 'Delete'),
								'class' => 'btn btn-default btn-sm glyphicon glyphicon-trash',
								'confirm' => __d('locale', 'You are about to delete: "{0}". Are you sure ?', $language->name),
							]);
						?>
					</div>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>