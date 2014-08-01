<?php $this->switchViewMode('teaser'); ?>
<?php foreach ($nodes as $node): ?>
	<?php echo $this->render($node); ?>
<?php endforeach; ?>

