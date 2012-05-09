<?php echo $this->Form->create(); ?>
	<?php echo $this->Html->useTag('fieldsetstart', __t('Editing menu')); ?>
		<?php echo $this->Form->input('Menu.title', array('required' => 'required', 'type' => 'text', 'label' => __t('Title *'))); ?>
		<?php echo $this->Form->input('Menu.description', array('type' => 'textarea', 'label' => __t('Description'))); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Form->submit(__t('Save')); ?>
<?php echo $this->Form->end(); ?>