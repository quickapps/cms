<?php echo $this->Form->create($instance, ['role' => 'form']); ?>
<fieldset>
	<legend><?php echo __('Basic Information'); ?></legend>
	<div class="form-group"><?php echo $this->Form->input('label'); ?></div>
	<div class="form-group"><?php echo $this->Form->checkbox('required'); ?></div>
	<div class="form-group">
		<?php echo $this->Form->textarea('description'); ?>
		<span class="help-block"><?php echo __('Instructions to present to the user below this field on the editing form.'); ?></span>
	</div>
</fieldset>
<hr />
<?php if ($advanced = $this->hook("Field.{$instance->handler}.Instance.settings", $instance)->result): ?>
<fieldset>
	<legend><?php echo __('Advanced'); ?></legend>
	<?php echo $advanced; ?>
</fieldset>
<?php endif; ?>
<?php echo $this->Form->submit(__('Save All')); ?>
<?php echo $this->Form->end(); ?>