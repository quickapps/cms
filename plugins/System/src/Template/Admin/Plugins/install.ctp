<fieldset>
	<legend><?php echo __d('system', 'Install New Plugin'); ?></legend>

	<?php echo $this->Form->create(null, ['type' => 'file']); ?>
		<?php echo $this->Form->input('file', ['type' => 'file', 'label' => __d('system', 'Upload ZIP package')]); ?>
		<?php echo $this->Form->input('activate', ['type' => 'checkbox', 'label' => __d('system', 'Activate after installation')]); ?>
		<?php echo $this->Form->submit(__d('system', 'Upload package'), ['name' => 'upload']); ?>
	<?php echo $this->Form->end(); ?>

	<hr />

	<?php echo $this->Form->create(null); ?>
		<?php echo $this->Form->input('url', ['label' => __d('system', 'Download ZIP package from URL'), 'placeholder' => __d('system', 'http://example.com/my-plugin.zip')]); ?>
		<?php echo $this->Form->input('activate', ['type' => 'checkbox', 'label' => __d('system', 'Activate after installation')]); ?>
		<?php echo $this->Form->submit(__d('system', 'Install from URL'), ['name' => 'download']); ?>
	<?php echo $this->Form->end(); ?>
</fieldset>
