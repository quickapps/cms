<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<?php echo $this->fetch('beforeSubmenu'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo $this->element('Field.FieldUI/field_ui_submenu'); ?>
    </div>
</div>
<?php echo $this->fetch('afterSubmenu'); ?>

<?php echo $this->fetch('beforeForm'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo $this->Form->create($arrayContext, ['role' => 'form']); ?>
            <fieldset>
                <legend><?php echo __d('field', 'Basic Information'); ?></legend>

                <div class="form-group"><?php echo $this->Form->input('_label', ['value' => $instance->label]); ?></div>
                <div class="form-group"><?php echo $this->Form->input('_required', ['checked' => $instance->required, 'type' => 'checkbox']); ?></div>
                <div class="form-group">
                    <?php echo $this->Form->textarea('_description', ['value' => $instance->description]); ?>
                    <span class="help-block"><?php echo __d('field', 'Instructions to present to the user below this field on the editing form.'); ?></span>
                </div>
            </fieldset>

            <hr />

            <?php echo $this->fetch('beforeFormContent'); ?>
            <?php echo $instance->settings($this); ?>
            <?php echo $this->fetch('afterFormContent'); ?>

            <?php echo $this->Form->submit(__d('field', 'Save All')); ?>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
<?php echo $this->fetch('afterForm'); ?>
