<div class="clearfix">
	<p>
		<div class="btn-group pull-right">
			<?php echo $this->Html->link('<span class="glyphicon glyphicon-pencil"></span>', ['plugin' => 'node', 'controller' => 'types', 'action' => 'edit', $item->slug], ['title' => __('Edit information'), 'class' => 'btn btn-default', 'escape' => false]); ?>
			<?php echo $this->Html->link('<span class="glyphicon glyphicon-eye-open"></span>', ['plugin' => 'node', 'controller' => 'types', 'action' => 'index'], ['title' => __('Display settings'), 'class' => 'btn btn-default', 'escape' => false]); ?>
			<?php echo $this->Html->link('<span class="glyphicon glyphicon-list-alt"></span>', ['plugin' => 'node', 'controller' => 'fields', 'action' => 'index', 'type' => $item->slug], ['title' => __('Edit fields'), 'class' => 'btn btn-default', 'escape' => false]); ?>
			<?php echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', ['plugin' => 'node', 'controller' => 'types', 'action' => 'delete', $item->slug], ['title' => __('Delete'), 'class' => 'btn btn-default', 'escape' => false]); ?>
		</div>
		<h4><?php echo $item->name; ?> (id: <?php echo $item->slug; ?>)</h4>
		<p class="list-group-item-text"><em><?php echo $item->description; ?></em></p>
	</p>
</div>