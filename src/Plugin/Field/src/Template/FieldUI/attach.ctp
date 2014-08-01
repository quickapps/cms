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

<?php echo $this->Form->create($fieldInstance); ?>
	<fieldset>
		<legend><?php echo __d('field', 'Attach new field'); ?></legend>

		<?php echo $this->Form->input('label', ['label' => __d('field', 'Label *'), 'placeholder' => 'e.g. User Age', 'required']); ?>
		<em class="help-block"><?php echo __d('field', 'Human readable name for this field.'); ?></em>

		<?php echo $this->Form->input('slug', ['label' => __d('field', 'Machine Name *'), 'placeholder' => 'e.g. user-age', 'required']); ?>
		<em class="help-block"><?php echo __d('field', 'Lowercase letters, numbers and "-" symbol (a-z, 0-9, -).'); ?></em>

		<?php echo $this->Form->input('handler', ['label' => __d('field', 'Field Type *'), 'type' => 'select', 'options' => $fieldsList, 'empty' => true, 'onchange' => 'showFieldInfo();', 'required']); ?>
		<em class="help-block">
			<?php foreach ($fieldsInfo as $info): ?>
				<span style="display:none;" class="field-info" data-handler="<?php echo $info['handler']; ?>">
					<?php echo $info['description']; ?>
				</span>
			<?php endforeach; ?>
		</em>

		<?php echo $this->Form->input('required', ['label' => __d('field', 'Required'), 'type' => 'checkbox']); ?>
		<em class="help-block"><?php echo __d('field', 'Is this field required?'); ?></em>

		<?php echo $this->Form->input('description', ['label' => __d('field', 'Help Text'), 'type' => 'textarea']); ?>
		<em class="help-block"><?php echo __d('field', 'Instructions to present to the user below this field on the editing form. (hooktags are allowed)'); ?></em>

		<?php echo $this->Form->submit(__d('field', 'Attach')); ?>
	</fieldset>
<?php echo $this->Form->end(); ?>

<script language="javascript">
	function showFieldInfo() {
		$select = $('select[name=handler]');
		$('span.field-info').hide();
		$('span.field-info[data-handler=' + $select.val() + ']').show();
	}
</script>