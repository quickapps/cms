<?php foreach ($menu['MenuLink'] as $node): ?>
	<?php echo $this->Html->link($node['link_title'], $node['router_path']); ?><br/>
	<?php echo $this->Form->helpBlock($node['description']); ?>
<?php endforeach; ?>