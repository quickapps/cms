<?php echo $this->Form->create($arrayContext); ?>
	<fieldset>
		<legend><?php echo __d('system', 'Plugin Settings'); ?></legend>

		<?php echo $this->element("{$plugin['name']}.settings"); ?>
		<?php echo $this->Form->submit(__d('system', 'Save all')); ?>
	</fieldset>
<?php echo $this->Form->end(); ?>