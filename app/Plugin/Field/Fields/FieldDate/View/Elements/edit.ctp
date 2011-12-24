<?php if (!isset($this->__dateFieldCount) || $this->__dateFieldCount < 1): ?>
    <?php echo $this->Html->script('/js/ui/jquery-ui.js'); ?>
    <?php echo $this->Html->css('/js/ui/css/ui-lightness/styles.css'); ?>
    <?php $this->__dateFieldCount++; ?>
<?php endif; ?>

<?php
    $data['FieldData'] = !isset($data['FieldData']) ? array() : $data['FieldData'];
    $data['FieldData'] = array_merge(array('id' => null, 'field_id' => null, 'foreignKey' => null, 'belongsTo' => null, 'data' => ''), $data['FieldData']);
    $selected = isset($data['FieldData']['data']) ? $data['FieldData']['data'] : '';

    echo $this->Form->input("FieldData.FieldDate.{$data['id']}.data", array('label' => $data['label'], 'value' => $selected, 'readonly'));
    echo $this->Form->hidden("FieldData.FieldDate.{$data['id']}.id", array('value' => $data['FieldData']['id']));
?>

<?php if (!empty($data['description'])): ?>
    <em><?php echo $data['description']; ?></em>
<?php endif; ?>

<?php
    $__data = array(
        'id' => $data['id'],
        'settings' => $data['settings']
    );

    echo $this->Layout->hook('field_date_js_init', $__data);
?>