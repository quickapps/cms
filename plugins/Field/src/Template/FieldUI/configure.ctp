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

<?= $this->fetch('beforeSubmenu'); ?>
<div class="row">
    <div class="col-md-12">
        <?= $this->element('Field.FieldUI/field_ui_submenu'); ?>
    </div>
</div>
<?= $this->fetch('afterSubmenu'); ?>

<?= $this->fetch('beforeForm'); ?>
<div class="row">
    <div class="col-md-12">
        <?= $this->Form->create($arrayContext, ['role' => 'form']); ?>
            <fieldset>
                <legend><?= __d('field', 'Basic Information'); ?></legend>

                <div class="form-group"><?= $this->Form->input('_label', ['value' => $instance->label]); ?></div>
                <div class="form-group"><?= $this->Form->input('_required', ['checked' => $instance->required, 'type' => 'checkbox']); ?></div>
                <div class="form-group">
                    <?= $this->Form->textarea('_description', ['value' => $instance->description]); ?>
                    <span class="help-block"><?= __d('field', 'Instructions to present to the user below this field on the editing form.'); ?></span>
                </div>
            </fieldset>

            <hr />

            <?= $this->fetch('beforeFormContent'); ?>
            <?= $instance->settings($this); ?>
            <?= $this->fetch('afterFormContent'); ?>

            <?= $this->Form->submit(__d('field', 'Save All')); ?>
        <?= $this->Form->end(); ?>
    </div>
</div>
<?= $this->fetch('afterForm'); ?>
