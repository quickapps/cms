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
        <?= $this->Form->create($fieldInstance); ?>
            <fieldset>
                <legend><?= __d('field', 'Attach new field'); ?></legend>

                <?= $this->Form->input('label', ['label' => __d('field', 'Label *'), 'placeholder' => 'e.g. User Age', 'required']); ?>
                <em class="help-block"><?= __d('field', 'Human readable name for this field.'); ?></em>

                <?= $this->Form->input('eav_attribute.name', ['label' => __d('field', 'Machine Name *'), 'placeholder' => 'e.g. user-age', 'required']); ?>
                <em class="help-block"><?= __d('field', 'Lowercase letters, numbers and "-" symbol (a-z, 0-9, -).'); ?></em>

                <?= $this->Form->input('handler', ['label' => __d('field', 'Field Type *'), 'type' => 'select', 'options' => $fieldsList, 'empty' => true, 'onchange' => 'showFieldInfo();', 'required']); ?>
                <em class="help-block">
                    <?php foreach ($fieldsInfo as $info): ?>
                        <span style="display:none;" class="field-info" data-handler="<?= str_replace('\\', '-', strtolower($info['handler'])); ?>">
                            <?= $info['description']; ?>
                        </span>
                    <?php endforeach; ?>
                </em>

                <?= $this->Form->input('required', ['label' => __d('field', 'Required'), 'type' => 'checkbox']); ?>
                <em class="help-block"><?= __d('field', 'Is this field required?'); ?></em>

                <?= $this->Form->input('description', ['label' => __d('field', 'Help Text'), 'type' => 'textarea']); ?>
                <em class="help-block"><?= __d('field', 'Instructions to present to the user below this field on the editing form.'); ?></em>

                <?= $this->Form->submit(__d('field', 'Attach')); ?>
            </fieldset>
        <?= $this->Form->end(); ?>
    </div>
</div>
<?= $this->fetch('afterForm'); ?>

<script language="javascript">
    function showFieldInfo() {
        var $select = $('select[name=handler]');
        var handler = $select.val().toLowerCase().replace(/\\/g, '-');
        $('span.field-info').hide();
        $('span.field-info[data-handler="' + handler + '"]').show();
        console.log(handler);
    }
</script>