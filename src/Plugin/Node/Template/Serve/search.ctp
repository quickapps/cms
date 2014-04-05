<h1><?php echo __('Search Results'); ?></h1>
<?php foreach ($nodes as $node): ?>
	<?php echo $this->render($node); ?>
<?php endforeach; ?>