<?php echo $this->Form->create('Role'); ?>
	<!-- Settings -->
	<?php echo $this->Html->useTag('fieldsetstart', __t('Editing role')); ?>
		<?php echo $this->Form->input('name', array('type' => 'text', 'label' => __t('Role name'))); ?>
		<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- Submit -->
	<?php echo $this->Form->submit(__t('Update Role')); ?>
<?php echo $this->Form->end(); ?>