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

<?php echo $this->fetch('beforeSubmenu'); ?>
<?php echo $this->element('Comment.CommentUI/index_submenu'); ?>
<?php echo $this->fetch('afterSubmenu'); ?>

<div class="clearfix">
	<?php echo $this->Form->create(null, ['type' => 'get', 'class' => 'form-inline pull-right']); ?>
		<div class="input-group">
			<?php echo $this->Form->input('search', ['type' => 'text', 'label' => false, 'value' => $search]); ?>
			<span class="input-group-btn">
				<?php echo $this->Form->submit(__d('comment', 'Search Comments')); ?>
			</span>
		</div>
	<?php echo $this->Form->end(); ?>
</div>

<?php echo $this->fetch('beforeTable'); ?>
<table class="table table-hover">
	<thead>
		<tr>
			<th width="230"><?php echo __d('comment', 'Author'); ?></th>
			<th><?php echo __d('node', 'Comment'); ?></th>
			<th><?php echo __d('node', 'In Response To'); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php if ($comments->count()): ?>
		<?php foreach ($comments as $comment): ?>
			<tr class="<?php echo $comment->status === 'pending' && $filterBy !== 'pending' ? 'warning' : ''?>">
				<td>
					<div class="media">
						<?php echo $this->Html->image($comment->author->avatar, ['width' => 30, 'class' => 'media-object pull-left']); ?>
						<div class="media-body">
							<strong><?php echo $comment->author->name; ?></strong><br />
							email: <?php echo $comment->author->email; ?><br />
							web: <?php echo $comment->author->web; ?><br />
							ip: <?php echo $comment->author->ip; ?>
						</div>
					</div>	
				</td>
				<td>
					<h4><?php echo $this->Html->link($comment->subject, ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => 'edit', $comment->id]); ?></h4>
					<p><?php echo $comment->body; ?></p>
					<em class="help-block"><?php echo __d('comment', 'Submitted on {0}', $comment->created->format('Y/m/d \a\t H:i a')); ?></em>
					<span><?php echo __d('comment', 'Move to'); ?>: </span>
					<div class="btn-group btn-group-xs">
						<?php if ($comment->status !== 'approved'): ?>
							<?php echo $this->Html->link(__d('comment', 'Approved'), ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => 'status', $comment->id, 'approved'], ['class' => 'btn btn-success']); ?>
						<?php endif; ?>

						<?php if ($comment->status !== 'pending'): ?>
							<?php echo $this->Html->link(__d('comment', 'Pending'), ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => 'status', $comment->id, 'pending'], ['class' => 'btn btn-info']); ?>
						<?php endif; ?>

						<?php if ($comment->status !== 'spam'): ?>
							<?php echo $this->Html->link(__d('comment', 'Spam'), ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => 'status', $comment->id, 'spam'], ['class' => 'btn btn-warning']); ?>
						<?php endif; ?>

						<?php if ($comment->status !== 'trash'): ?>
							<?php echo $this->Html->link(__d('comment', 'Trash'), ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => 'status', $comment->id, 'trash'], ['class' => 'btn btn-danger']); ?>
						<?php else: ?>
							<?php echo $this->Html->link(__d('comment', 'Delete Permanently'), ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => 'delete', $comment->id, ], ['class' => 'btn btn-danger', 'confirm' => __d('comment', 'Delete this comment?')]); ?>
						<?php endif; ?>
					</div>
				</td>
				<td>
					<?php echo $comment->entity; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php else: ?>
			<tr>
				<td colspan="3">
					<?php echo $filterBy === 'pending' ? __d('comment', 'No comments awaiting moderation.') : __d('comment', 'No comments found.'); ?>
				</td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>
<?php echo $this->fetch('afterTable'); ?>

<?php if ($filterBy === 'trash'): ?>
	<p><?php echo $this->Html->link(__d('comment', 'Empty Trash'), ['plugin' => $this->request->plugin, 'controller' => $this->request->controller, 'action' => 'empty_trash'], ['class' => 'btn btn-default btn-sm', 'confirm' => __d('comment', 'Delete all comments in the trash? This operation can not be undone.')]); ?></p>
<?php endif; ?>

<?php echo $this->fetch('beforePagination'); ?>
<ul class="pagination">
	<?php echo $this->Paginator->prev(); ?>
	<?php echo $this->Paginator->numbers(); ?>
	<?php echo $this->Paginator->next(); ?>
</ul>
<?php echo $this->fetch('afterPagination'); ?>

<p class="text-center help-block">
	<?php
		echo $this->Paginator->counter(
			__d('comment', 'Page {{page}} of {{pages}}, showing {{current}} comments out of {{count}} total.')
		);
	?>
</p>