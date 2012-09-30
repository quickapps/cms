<?php echo $this->Form->create('Vocabulary', array('url' => "/admin/taxonomy/vocabularies/edit/{$this->data['Vocabulary']['slug']}")); ?>
	<!-- Content -->
	<?php echo $this->Html->useTag('fieldsetstart', __t('Add Vocabulary')); ?>
		<?php echo $this->Form->hidden('id'); ?>
		<?php echo $this->Form->input('title', array('required' => 'required', 'label' => __t('Title *'), 'type' => 'text')); ?>
		<?php echo $this->Form->input('description', array('label' => __t('Description'), 'type' => 'textarea')); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- Submit -->
	<?php echo $this->Form->submit(__t('Save vocabulary')); ?>
<?php echo $this->Form->end(); ?>