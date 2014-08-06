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

<?php echo $this->element('Node.index_submenu'); ?>

<table class="table table-hover">
	<thead>
		<tr>
			<th><?php echo __d('node', 'Title'); ?></th>
			<th><?php echo __d('node', 'Type'); ?></th>
			<th><?php echo __d('node', 'Author'); ?></th>
			<th><?php echo __d('node', 'Language'); ?></th>
			<th><?php echo __d('node', 'Created on'); ?></th>
			<th><?php echo __d('node', 'Actions'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($nodes as $node): ?>
			<tr class="<?php echo $node->status == 0 ? 'warning' : ''; ?> ">
				<td>
					<?php echo $this->Html->link($node->title, $node->url, ['target' => '_blank']); ?>

					<?php if ($node->promote): ?>
					<span class="glyphicon glyphicon-home" title="<?php echo __d('node', 'Promote to front page'); ?>"></span>
					<?php endif; ?>

					<?php if ($node->sticky): ?>
					<span class="glyphicon glyphicon-pushpin" title="<?php echo __d('node', 'Sticky at top of lists'); ?>"></span>
					<?php endif; ?>

					<?php if ((int)$node->comment_status === 1): ?>
					<span class="glyphicon glyphicon-comment" title="<?php echo __d('node', 'Comments open'); ?>"></span>
					<?php endif; ?>
				</td>
				<td><?php echo $node->type; ?></td>
				<td><?php echo $node->author->name; ?></td>
				<td><?php echo $node->language ? $node->language : __d('node', '---'); ?></td>
				<td><?php echo $node->created->format(__d('node', 'Y-m-d H:i:s')); ?></td>
				<td>
					<?php echo $this->Html->link('', ['prefix' => 'admin', 'plugin' => 'Node', 'controller' => 'manage', 'action' => 'edit', $node->id], ['class' => 'btn btn-default glyphicon glyphicon-pencil', 'title' => __d('node', 'Edit')]); ?>
					<?php echo $this->Html->link('', ['prefix' => 'admin', 'plugin' => 'Node', 'controller' => 'translate', 'action' => 'edit', $node->id], ['class' => 'btn btn-default glyphicon glyphicon-globe', 'title' => __d('node', 'Translate')]); ?>
					<?php echo $this->Html->link('', ['prefix' => 'admin', 'plugin' => 'Node', 'controller' => 'manage', 'action' => 'delete', $node->id], ['class' => 'btn btn-default glyphicon glyphicon-trash', 'title' => __d('node', 'Delete'), 'confirm' => __d('node', 'You are about to delete: "%s". Are you sure ?', $node->title)]); ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>