<tr class="<?php echo $node->status == 0 ? 'warning' : ''; ?> ">
	<td>
		<?php echo str_repeat('&nbsp;&nbsp;', 2 * $deph); ?>
		<?php echo $this->Html->link($node->title, $node->getUrl(), ['target' => '_blank']); ?>

		<?php if ($node->promote): ?>
		<span class="glyphicon glyphicon-home" title="<?php echo __('Promote to front page'); ?>"></span>
		<?php endif; ?>

		<?php if ($node->sticky): ?>
		<span class="glyphicon glyphicon-pushpin" title="<?php echo __('Sticky at top of lists'); ?>"></span>
		<?php endif; ?>

		<?php if ((int)$node->comment_status === 1): ?>
		<span class="glyphicon glyphicon-comment" title="<?php echo __('Comments open'); ?>"></span>
		<?php endif; ?>
	</td>
	<td><?php echo $node->type; ?></td>
	<td><?php echo $node->author_name; ?></td>
	<td><?php echo $node->language ? $node->language : __('--any--'); ?></td>
	<td><?php echo $node->created->format(__('Y-m-d H:i:s')); ?></td>
	<td>
		<div class="dropdown">
			<button class="btn" type="button" data-toggle="dropdown"><?php echo __('Actions'); ?><span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
				<li role="presentation"><?php echo $this->Html->link(__('Edit'), '/admin/node/manage/edit/' . $node->id, ['tabindex' => -1]); ?></li>
				<li role="presentation"><?php echo $this->Html->link(__('Translate'), '/admin/node/manage/translate/' . $node->id, ['tabindex' => -1]); ?></li>
				<li role="presentation"><?php echo $this->Html->link(__('Append New Content'), '/admin/node/manage/add/' . $node->node_type_slug . '/?parent_id=' . $node->id, ['tabindex' => -1]); ?></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation"><?php echo $this->Html->link(__('Delete'), '/admin/node/manage/delete/' . $node->id, ['tabindex' => -1, 'confirm' => __('You are about to delete: "%s". Are you sure ?', $node->title)]); ?></li>
			</ul>
		</div>
	</td>
</tr>

<?php if ($node->children): ?>
	<?php foreach ($node->children as $child): ?>
		<?php echo renderNodeRow($this, $child, $deph + 1); ?>
	<?php endforeach; ?>
<?php endif; ?>