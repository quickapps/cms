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
			<th><?php echo __d('user', 'Role name'); ?></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($roles as $role): ?>
			<tr>
				<td>
					<?php echo $role->name; ?><br />
					<small>(<?php echo $role->slug; ?>)</small>
				</td>
				<td>
					<div class="btn-group">
						<?php
							echo $this->Html->link('', [
								'plugin' => 'User',
								'controller' => 'roles',
								'action' => 'edit',
								$role->id,
							], [
								'title' => __d('user', 'Edit role'),
								'class' => 'btn btn-default btn-sm glyphicon glyphicon-pencil',
							]);
						?>

						<?php if (!in_array($role->id, [ROLE_ID_ADMINISTRATOR, ROLE_ID_AUTHENTICATED, ROLE_ID_ANONYMOUS])): ?>
						<?php
							echo $this->Html->link('', [
								'plugin' => 'User',
								'controller' => 'roles',
								'action' => 'delete',
								$role->id,
							], [
								'title' => __d('user', 'Delete'),
								'class' => 'btn btn-default btn-sm glyphicon glyphicon-trash',
								'confirm' => __d('user', 'You are about to delete: "{0}". Are you sure ?', $role->name),
							]);
						?>
						<?php endif; ?>
					</div>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>