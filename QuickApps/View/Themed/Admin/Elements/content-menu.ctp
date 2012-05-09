<?php foreach ($menu['MenuLink'] as $node): ?>
<p>
	<?php echo $this->Html->link($node['link_title'], $node['router_path']); ?><br/>
	<em><?php echo $node['description']; ?></em>
</p>
<?php endforeach; ?>