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

<p><?php echo $this->element('User.index_submenu'); ?></p>

<table class="table table-hover">
	<thead>
		<tr>
			<th><?php echo __d('user', 'Name'); ?></th>
			<th><?php echo __d('user', 'e-Mail'); ?></th>
			<th class="hidden-xs"><?php echo __d('user', 'Roles'); ?></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($users as $user): ?>
			<tr>
				<td><?php echo $user->name; ?> <small>(<?php echo $user->username; ?>)</small></td>
				<td><?php echo $user->email; ?></td>
				<td class="hidden-xs">
					<?php echo implode(', ', $user->role_names); ?>
				</td>
				<td>
					<div class="btn-group">
						<?php
							echo $this->Html->link('', [
								'plugin' => 'User',
								'controller' => 'manage',
								'action' => 'edit',
								$user->id,
							], [
								'title' => __d('user', 'Set as default'),
								'class' => 'btn btn-default btn-sm glyphicon glyphicon-pencil',
							]);
						?>
						<?php
							echo $this->Html->link('', [
								'plugin' => 'User',
								'controller' => 'manage',
								'action' => 'password_instructions',
								$user->id,
							], [
								'title' => __d('user', 'Send password recovery instructions'),
								'class' => 'btn btn-default btn-sm glyphicon glyphicon-qrcode',
								'confirm' => __d('user', 'You are about to send password recovery instructions to "{0}". Are you sure ?', $user->name),
							]);
						?>
						<?php if (!in_array(ROLE_ID_ADMINISTRATOR, $user->role_ids)): ?>
							<?php if ($user->status): ?>
								<?php
									echo $this->Html->link('', [
										'plugin' => 'User',
										'controller' => 'manage',
										'action' => 'block',
										$user->id,
									], [
										'title' => __d('user', 'Block account'),
										'class' => 'btn btn-default btn-sm glyphicon glyphicon-remove-circle',
										'confirm' => __d('user', 'You are about to block: "{0}". Are you sure ?', $user->name),
									]);
								?>
							<?php else: ?>
								<?php
									echo $this->Html->link('', [
										'plugin' => 'User',
										'controller' => 'manage',
										'action' => 'activate',
										$user->id,
									], [
										'title' => __d('user', 'Activate account'),
										'class' => 'btn btn-default btn-sm glyphicon glyphicon-ok-circle',
										'confirm' => __d('user', 'You are about to activate: "{0}". Are you sure ?', $user->name),
									]);
								?>
							<?php endif; ?>
							<?php
								echo $this->Html->link('', [
									'plugin' => 'User',
									'controller' => 'manage',
									'action' => 'delete',
									$user->id,
								], [
									'title' => __d('user', 'Delete'),
									'class' => 'btn btn-default btn-sm glyphicon glyphicon-trash',
									'confirm' => __d('user', 'You are about to delete: "{0}". Are you sure ?', $user->name),
								]);
							?>
						<?php endif; ?>
					</div>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<ul class="pagination">
	<?php echo $this->Paginator->prev(); ?>
	<?php echo $this->Paginator->numbers(); ?>
	<?php echo $this->Paginator->next(); ?>
</ul>