<?php echo $this->Form->create($arrayContext, ['role' => 'form']); ?>
	<fieldset>
		<legend><?php echo __d('field', 'View Mode Settings For "{0}" [{1}]', $instance->label, $viewModeInfo['name']); ?></legend>
		<?php
			echo $this->Form->input('label_visibility', [
				'label' => __d('field', 'Label field visibility'),
				'options' => [
					'hidden' => __d('field', 'Hidden'),
					'above' => __d('field', 'Above'),
					'inline' => __d('field', 'Inline'),
				],
			]);
		?>
		<em class="help-block"><?php echo __d('field', 'Position of the field label. Set to "Hidden" for no label.'); ?></em>

		<?php echo $this->Form->input('hooktags', ['type' => 'checkbox', 'label' => __d('field', 'Hooktags')]); ?>
		<em class="help-block"><?php echo __d('field', 'Whether to parse hooktags in field content or not.'); ?></em>

		<?php echo $this->Form->input('hidden', ['type' => 'checkbox', 'label' => __d('field', 'Hidden Field'), 'onclick' => '$("div.field-view-mode-form").toggle();']); ?>
		<em class="help-block"><?php echo __d('field', 'Whether to render this field or not on "{0}" view mode.', $viewModeInfo['name']); ?></em>

		<div class="field-view-mode-form" style="<?php echo $instance->view_modes[$viewMode]['hidden'] ? 'display:none;' : ''; ?>">
			<?php echo $this->invoke("Field.{$instance->handler}.Instance.viewModeForm", $this, $instance, [])->result; ?>
		</div>

		<?php echo $this->Form->submit(__d('field', 'Save changes')); ?>
	</fieldset>
<?php echo $this->Form->end(); ?>