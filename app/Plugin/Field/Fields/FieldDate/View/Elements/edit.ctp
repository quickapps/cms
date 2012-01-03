<?php
    $field['FieldData'] = !isset($field['FieldData']) ? array() : $field['FieldData'];
    $field['FieldData'] = array_merge(array('id' => null, 'field_id' => null, 'foreignKey' => null, 'belongsTo' => null, 'data' => ''), $field['FieldData']);
    $selected = isset($field['FieldData']['data']) ? $field['FieldData']['data'] : '';

    echo $this->Form->input("FieldData.FieldDate.{$field['id']}.data", array('label' => $field['label'], 'value' => $selected, 'readonly'));
    echo $this->Form->hidden("FieldData.FieldDate.{$field['id']}.id", array('value' => $field['FieldData']['id']));
?>

<?php if (!empty($field['description'])): ?>
    <em><?php echo $field['description']; ?></em>
<?php endif; ?>

<?php
    $__data = array(
        'id' => $field['id'],
        'settings' => $field['settings']
    );

    echo $this->Layout->hook('field_date_js_init', $__data);
?>