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
                <legend><?= __d('field', 'View Mode Settings For "{0}" [{1}]', $instance->label, $viewModeInfo['name']); ?></legend>

                <?=
                    $this->Form->input('label_visibility', [
                        'label' => __d('field', 'Label field visibility'),
                        'options' => [
                            'hidden' => __d('field', 'Hidden'),
                            'above' => __d('field', 'Above'),
                            'inline' => __d('field', 'Inline'),
                        ],
                    ]);
                ?>
                <em class="help-block"><?= __d('field', 'Position of the field label. Set to "Hidden" for no label.'); ?></em>

                <?= $this->Form->input('shortcodes', ['type' => 'checkbox', 'label' => __d('field', 'Shortcodes')]); ?>
                <em class="help-block"><?= __d('field', 'Whether to parse shortcodes in field content or not.'); ?></em>

                <?= $this->Form->input('hidden', ['type' => 'checkbox', 'label' => __d('field', 'Hidden Field'), 'onclick' => '$("div.field-view-mode-form").toggle();']); ?>
                <em class="help-block"><?= __d('field', 'Whether to render this field or not on "{0}" view mode.', $viewModeInfo['name']); ?></em>

                <?= $this->fetch('beforeFormContent'); ?>
                <div class="field-view-mode-form" style="<?= $instance->view_modes[$viewMode]['hidden'] ? 'display:none;' : ''; ?>">
                    <?= $instance->viewModeSettings($this, $viewMode); ?>
                </div>
                <?= $this->fetch('afterFormContent'); ?>

                <?= $this->Form->submit(__d('field', 'Save changes')); ?>
            </fieldset>
        <?= $this->Form->end(); ?>
    </div>
</div>
<?= $this->fetch('afterForm'); ?>