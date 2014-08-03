<?php echo $this->Form->create($arrayContext); ?>
	<?php echo $this->element("{$plugin['name']}.settings"); ?>

	<?php echo $this->Form->submit(__d('system', 'Save all')); ?>
<?php echo $this->Form->end(); ?>