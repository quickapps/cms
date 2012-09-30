<?php echo $this->Form->create('Package', array('enctype' => 'multipart/form-data')); ?>
	<?php echo $this->Html->useTag('fieldsetstart', __t('Import entries')); ?>
		<?php
			echo $this->Form->input('Package.data',
				array(
					'type' => 'file',
					'label' => __t('Package (.pot)')
				)
			);
		?>

		<?php echo $this->Form->input('Package.language',
				array(
					'type' => 'select',
					'options' => $languages,
					'label' => __t('Language')
				)
			);
		?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<?php echo $this->Form->submit(__t('Import entries')); ?>
<?php echo $this->Form->end(); ?>