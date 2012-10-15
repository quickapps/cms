<?php echo $this->Form->create('Term', array('url' => "/admin/taxonomy/vocabularies/edit_term/{$this->data['Term']['slug']}")); ?>
	<!-- Content -->
	<?php echo $this->Html->useTag('fieldsetstart', __t('Term')); ?>
		<?php echo $this->Form->hidden('id'); ?>
		<?php echo $this->Form->input('name', array('type' => 'text', 'label' => __t('Name *'))); ?>
		<?php echo $this->Form->input('description', array('type' => 'textarea', 'label' => __t('Description'))); ?>
		<?php echo $this->Form->helpBlock(__t('Go to <a href="%s">terms list</a> to reparent this term', $this->Html->url("/admin/taxonomy/vocabularies/terms/{$this->data['Vocabulary']['slug']}"))); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- Submit -->
	<?php echo $this->Form->submit(__t('Save term')); ?>
<?php echo $this->Form->end(); ?>