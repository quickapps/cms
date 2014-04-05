<ul>
<?php foreach ($plugins as $plugin): ?>
	<li>
	<?php
		echo $this->Html->link($plugin, [
			'plugin' => 'system',
			'controller' => 'help',
			'action' => 'about', $plugin
		]);
	?>
</li>
<?php endforeach; ?>
</ul>