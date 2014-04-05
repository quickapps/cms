<p>
	<ul class="nav nav-pills startup-menu">
		<?php foreach ($menu as $label => $link): ?>
		<li class="<?php echo $link['active'] ? 'active' : 'disabled'; ?>"><?php echo $this->Html->link($label, ($link['active'] ? $link['url'] : '#')); ?></li>
		<?php endforeach; ?>
	</ul>
</p>

<hr />