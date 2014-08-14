<fieldset>
	<legend><?php echo __d('system', 'Install New Plugin'); ?></legend>

	<?php echo $this->Form->create(null, ['type' => 'file']); ?>
		<?php echo $this->Form->input('file', ['type' => 'file']); ?>
		<?php echo $this->Form->submit(__d('system', 'Upload package'), ['name' => 'upload']); ?>
	<?php echo $this->Form->end(); ?>

	<?php echo $this->Form->create(null); ?>
		<?php echo $this->Form->input('url', ['type' => 'text']); ?>
		<?php echo $this->Form->submit(__d('system', 'Install from URL'), ['name' => 'download']); ?>
	<?php echo $this->Form->end(); ?>
</fieldset>
