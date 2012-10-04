<?php
/**
 * Default Node rendering.
 * This element is rendered by NodeHookHelper::node_render().
 *
 * @package	 QuickApps.View.Elements
 * @author	 Christopher Castro <chris@quickapps.es>
 */
?>

<?php
if (!QuickApps::is('view.feed')) {
	if (in_array($Layout['display'], array('full', 'print'))) {
		echo $this->Html->tag('h2', $node['Node']['title'], array('class' => 'node-title'));
	} else {
		echo $this->Html->link($node['Node']['title'],
			"/{$node['Node']['node_type_id']}/{$node['Node']['slug']}.html",
			array('class' => 'node-title', 'escape' => false)
		);
	}
?>
	<!-- Submitter -->
	<?php
		if ($node['NodeType']['node_show_author'] ||
			$node['NodeType']['node_show_date'] ||
			$node['Node']['comment']
		) {
	?>
	<div class="meta submitter">
		<span>
			<?php echo $node['NodeType']['node_show_author'] ? __t('published by <a href="%s">%s</a>', $this->Html->url("/user/profile/{$node['CreatedBy']['username']}"), $node['CreatedBy']['username']) : ''; ?>
			<?php echo $node['NodeType']['node_show_date'] ? ' ' . __t('on %s',  $this->Time->format(__t('M d, Y H:i'), $node['Node']['created'], null, Configure::read('Variable.timezone'))) : ''; ?>
			<?php echo $node['Node']['comment'] && ($node['NodeType']['node_show_author'] || $node['NodeType']['node_show_date']) ? ' | ' : ''; ?>
			<?php echo $node['Node']['comment'] ? __t('%d comments',  $node['Node']['comment_count']) : ''; ?>
		</span>
	</div>
	<?php } ?>
	<!-- /Submitter -->

	<!-- Fields -->
	<?php
		foreach ($node['Field'] as $field) {
			echo $this->Node->renderField($field);
		}
	?>
	<!-- /Fields -->

	<!-- Readmore -->
	<?php if (!in_array($Layout['display'], array('full', 'print', 'rss'))) { ?>
		<div class="link-wrapper display-mode-<?php echo $Layout['display']; ?>">
			<?php echo $this->Html->link('<span>' . __t('Read More') . ' »</span>', "/{$node['Node']['node_type_id']}/{$node['Node']['slug']}.html", array('class' => 'read-more', 'escape' => false)); ?>
		</div>
	<?php } ?>
	<!-- /Readmore -->

<?php
} else {
	foreach ($node['Field'] as $field) {
		echo $this->Layout->renderField($field);
	}
}
?>