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

<p><?php echo $this->element('Field.FieldUI/field_ui_submenu'); ?></p>

<table class="table table-hover table-bordered table-responsive">
	<thead>
		<tr>
			<th><?php echo __d('field', 'Field label'); ?></th>
			<th><?php echo __d('field', 'Machine name'); ?></th>
			<th><?php echo __d('field', 'Label visibility'); ?></th>
			<th><?php echo __d('field', 'Formatter'); ?></th>
			<th><?php echo __d('field', 'Actions'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php $count = count($instances); ?>
		<?php $k = 0; ?>
		<?php foreach ($instances as $instance): ?>
		<tr>
			<td><?php echo $instance->label; ?></td>
			<td><?php echo $instance->slug; ?></td>
			<td><?php echo $instance->view_modes[$viewMode]['label_visibility']; ?></td>
			<td><?php echo $instance->view_modes[$viewMode]['formatter']; ?></td>
			<td>
				<?php if ($k > 0): ?>
				<?php echo $this->Html->link('', ['plugin' => $this->request->params['plugin'], 'controller' => $this->request->params['controller'], 'action' => 'view_mode_move', $viewMode, $instance->id, 'up'], ['title' => __d('field', 'Move up'), 'class' => 'btn btn-default glyphicon glyphicon-arrow-up']); ?>
				<?php endif; ?>

				<?php if ($k < $count - 1): ?>
				<?php echo $this->Html->link('', ['plugin' => $this->request->params['plugin'], 'controller' => $this->request->params['controller'], 'action' => 'view_mode_move', $viewMode, $instance->id, 'down'], ['title' => __d('field', 'Move down'), 'class' => 'btn btn-default glyphicon glyphicon-arrow-down']); ?>
				<?php endif; ?>

				<?php echo $this->Html->link('', ['plugin' => $this->request->params['plugin'], 'controller' => $this->request->params['controller'], 'action' => 'view_mode_edit', $viewMode, $instance->id], ['title' => __d('field', 'View mode settings'), 'class' => 'btn btn-default glyphicon glyphicon-eye-open']); ?>
			</td>
		</tr>
		<?php $k++; ?>
		<?php endforeach; ?>
	</tbody>
</table>