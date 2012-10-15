<?php echo $this->Form->create('NodeType', array('url' => '/admin/node/types/edit/' . $this->data['NodeType']['id'])); ?>
	<?php echo $this->Html->useTag('fieldsetstart', __t('Type')); ?>
		<?php echo $this->Form->hidden('NodeType.id'); ?>
		<?php echo $this->Form->input('NodeType.name', array('required' => 'required', 'type' => 'text', 'label' => __t('Name *'), 'helpBlock' => __t('This text will be displayed as part of the list on the Add new content page'))); ?>
		<?php echo $this->Form->input('NodeType.new_id', array('type' => 'text', 'label' => __t('New ID *'), 'helpBlock' => __t('A unique name for this content type. It must only contain lowercase letters, numbers, and underscores.'))); ?>
		<?php echo $this->Form->input('NodeType.description', array('type' => 'textarea', 'label' => __t('Description'), 'helpBlock' => __t('Describe this content type. The text will be displayed on the Add new content page.'))); ?>
		<?php echo $this->Form->input('NodeType.title_label', array('required' => 'required', 'type' => 'text', 'label' => __t('Title field label *'))); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<?php echo $this->Html->useTag('fieldsetstart', __t('Display format')); ?>
		<?php echo $this->Form->input('NodeType.node_show_author', array('type' => 'checkbox', 'label' => __t("Show author's name"), 'helpBlock' => __t('Author username will be displayed'))); ?>
		<?php echo $this->Form->input('NodeType.node_show_date', array('type' => 'checkbox', 'label' => __t('Show date'), 'helpBlock' => __t('Publish date will be displayed'))); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<?php echo $this->Html->useTag('fieldsetstart', __t('Comments')); ?>
		<?php echo $this->Form->input('NodeType.comments_approve', array('type' => 'checkbox', 'label' => __t('Auto approve comments'))); ?>
		<?php echo $this->Form->input('NodeType.comments_per_page', array('type' => 'select', 'options' => Hash::combine(array(10, 30, 50, 70, 90, 150, 200, 250, 300), '{n}', '{n}'), 'label' => __t('Comments per page'))); ?>
		<?php echo $this->Form->input('NodeType.comments_anonymous',
				array('type' => 'select',
					'options' => array(
						0 => __t('Anonymous posters may not enter their contact information'),
						1 => __t('Anonymous posters may leave their contact information'),
						2 => __t('Anonymous posters must leave their contact information')
					), 'label' => __t('Anonymous commenting')
				)
			);
		?>
		<?php echo $this->Form->input('NodeType.comments_subject_field', array('type' => 'checkbox', 'label' => __t('Allow comment title'))); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<?php echo $this->Html->useTag('fieldsetstart', __t('Default options')); ?>
		<?php echo $this->Html->useTag('fieldsetstart', __t('Comments')); ?>
			<?php echo $this->Form->input('default_comment', array('type' => 'radio', 'legend' => false, 'separator' => '<br>', 'options' => array(2 => __t('Open'), 0 => __t('Closed'), 1 => __t('Read Only')), 'helpBlock' => __t('Default comment setting for new content'))); ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Language')); ?>
			<?php echo $this->Form->input('default_language', array('empty' => __t('-- Any --'), 'type' => 'select', 'label' => __t('Language'), 'options' => $languages)); ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Publishing')); ?>
			<?php echo $this->Form->input('default_status', array('type' => 'checkbox', 'label' => __t('Published'), 'value' => 1)); ?>
			<?php echo $this->Form->input('default_promote', array('type' => 'checkbox', 'label' => __t('Promoted to front page'), 'value' => 1)); ?>
			<?php echo $this->Form->input('default_sticky', array('type' => 'checkbox', 'label' => __t('Sticky at top of lists'), 'value' => 1)); ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

<?php echo $this->Form->end(__t('Save')); ?>