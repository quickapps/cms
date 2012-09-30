<?php
	$display = isset($this->request->params['named']['display']) ? $this->request->params['named']['display'] : 'default';

	$hidden = (
		isset($this->data['Field']['settings']['display'][$display]['type']) &&
		$this->data['Field']['settings']['display'][$display]['type'] == 'hidden'
	);

	$hooktags = (
		!isset($this->data['Field']['settings']['display'][$display]['hooktags']) ||
		$this->data['Field']['settings']['display'][$display]['hooktags']
	);
?>
<?php echo $this->Form->create('Field'); ?>
	<?php echo $this->Html->useTag('fieldsetstart', __t('Field display format (display mode: %s)', Inflector::camelize($display))); ?>
		<?php echo $this->Form->hidden('Field.id'); ?>
		<?php echo $this->Form->hidden('Field.display', array('value' => $display)); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Label')); ?>
			<?php
				echo $this->Form->input("Field.settings.display.{$display}.label",
					array(
						'type' => 'select',
						'label' => false,
						'options' => array('hidden' => __t('Hidden'), 'above' => __t('Above'), 'inline' => __t('Inline')),
						'empty' => false
					)
				);
			?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', 'Hooktags'); ?>
			<?php
				echo $this->Form->input("Field.settings.display.{$display}.hooktags",
					array(
						'type' => 'checkbox',
						'checked' => $hooktags,
						'label' => __t('Allow hooktags'),
						'options' => array(1 => __t('Yes'), 0 => __t('No')),
					)
				);
			?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __t('Format')); ?>
			<?php
				echo $this->Form->input('Field.display_hidden',
					array(
						'type' => 'checkbox',
						'label' => __t('Hidden'),
						'onClick' => "$('#field-formatter-form').toggle();",
						'value' => 1,
						'checked' => $hidden
					)
				);
			?>
			<div id="field-formatter-form" style="<?php echo $hidden ? 'display:none;' : ''; ?>">
			<?php
				echo $this->element(Inflector::camelize($this->data['Field']['field_module']) . '.formatter');
			?>
			</div>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Form->submit(__t('Save field')); ?>
<?php echo $this->Form->end(); ?>