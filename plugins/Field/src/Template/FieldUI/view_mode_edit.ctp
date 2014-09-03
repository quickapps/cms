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

<?php echo $this->fetch('beforeSubmenu'); ?>
<p><?php echo $this->element('Field.FieldUI/field_ui_submenu'); ?></p>
<?php echo $this->fetch('afterSubmenu'); ?>

<?php echo $this->fetch('beforeForm'); ?>
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

		<?php echo $this->fetch('beforeFormContent'); ?>
		<div class="field-view-mode-form" style="<?php echo $instance->view_modes[$viewMode]['hidden'] ? 'display:none;' : ''; ?>">
			<?php echo $this->hook("Field.{$instance->handler}.Instance.viewModeForm", $instance, [])->result; ?>
		</div>
		<?php echo $this->fetch('afterFormContent'); ?>

		<?php echo $this->Form->submit(__d('field', 'Save changes')); ?>
	</fieldset>
<?php echo $this->Form->end(); ?>
<?php echo $this->fetch('afterForm'); ?>