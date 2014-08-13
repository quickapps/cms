<?php echo $this->Form->create(null); ?>
	<h2>
		<?php echo __d('taxonomy', "{0}: Terms Tree", $vocabulary->name); ?>
		<?php
			echo $this->Html->link('<span class="glyphicon glyphicon-plus"></span> ' . __d('taxonomy', 'add term'), [
				'plugin' => 'Taxonomy',
				'controller' => 'terms',
				'action' => 'add',
				$vocabulary->id
			], [
				'class' => 'btn btn-default',
				'escape' => false
			]);
		?>
	</h2>

	<?php if ($terms->count()): ?>
		<?php echo $this->Form->hidden('tree_order', ['id' => 'tree_order']); ?>
		<?php
			echo $this->Menu->render($terms, [
				'beautify' => false,
				'breadcrumbGuessing' => false,
				'id' => 'menu-links',
				'templates' => [
					'root' => '<ul class="sortable">{{content}}</ul>',
					'parent' => '<ul>{{content}}</ul>',
				],
				'formatter' => function ($term, $info) {
					return $this->element('Taxonomy.terms_tree_leaf', compact('term', 'info'));
				}
			]);
		?>
		<?php echo $this->Form->submit(__d('taxonomy', 'Save Order')); ?>
	<?php else: ?>
		<div class="alert alert-warning">
			<?php echo __d('taxonomy', 'There are not terms yet, use the "add term" button to start adding new terms to this vocabulary.'); ?>
		</div>
	<?php endif; ?>
<?php echo $this->Form->end(); ?>
<?php
	echo $this->Html->script([
		'System.jquery-ui.js',
		'System.jquery.json.js',
		'System.jquery.mjs.nestedSortable.js',
		'Taxonomy.terms.tree.js',
	]);
?>