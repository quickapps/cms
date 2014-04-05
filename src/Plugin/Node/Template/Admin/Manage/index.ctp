<?php
	function renderNode($view, $node, $deph = 0) {
		return $view->element('node_row', ['node' => $node, 'deph' => $deph]);
	}
?>
<table class="table table-hover">
	<thead>
		<tr>
			<th><?php echo __('Title'); ?></th>
			<th><?php echo __('Type'); ?></th>
			<th><?php echo __('Author'); ?></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($nodes as $node): ?>
			<?php echo renderNode($this, $node); ?>
		<?php endforeach; ?>
	</tbody>
</table>