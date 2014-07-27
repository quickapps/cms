<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<p><?php echo $this->element('Field.FieldUI/field_ui_submenu'); ?></p>

<?php echo $this->Form->create($instance, ['role' => 'form']); ?>
	<fieldset>
		<legend><?php echo __d('field', 'Basic Information'); ?></legend>
		<div class="form-group"><?php echo $this->Form->input('label'); ?></div>
		<div class="form-group"><?php echo $this->Form->input('required', ['type' => 'checkbox']); ?></div>
		<div class="form-group">
			<?php echo $this->Form->textarea('description'); ?>
			<span class="help-block"><?php echo __d('field', 'Instructions to present to the user below this field on the editing form.'); ?></span>
		</div>
	</fieldset>

	<hr />

	<?php if ($advanced = $this->invoke("Field.{$instance->handler}.Instance.settings", $this, $instance)): ?>
		<fieldset>
			<legend><?php echo __d('field', 'Advanced'); ?></legend>
			<?php echo $advanced->result; ?>
		</fieldset>
	<?php endif; ?>

	<?php echo $this->Form->submit(__d('field', 'Save All')); ?>
<?php echo $this->Form->end(); ?>