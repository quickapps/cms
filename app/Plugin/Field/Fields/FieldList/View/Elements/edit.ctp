<?php
    $field['FieldData'] = !isset($field['FieldData']) ? array() : $field['FieldData'];
    $field['FieldData'] = array_merge(array('id' => null, 'field_id' => null, 'foreignKey' => null, 'belongsTo' => null, 'data' => ''), $field['FieldData']);
    $_options = $options = array();

    if (!empty($field['settings']['options'])) {
        $_options = explode("\n", $field['settings']['options']);

        foreach ($_options as $option) {
            $option = explode("|",$option);
            $value = $option[0];
            $label = isset($option[1]) ? $option[1] : $option[0];
            $options[$value] = $label;
        }
    }

    $field['settings']['type'] = empty($field['settings']['type']) ? 'checkbox' : $field['settings']['type'];

    if (isset($this->data['FieldData']['FieldList'][$field['id']]['data'])) {
        $selected = $this->data['FieldData']['FieldList'][$field['id']]['data'];
    } else {
        $selected = explode('|', (string)$field['FieldData']['data']);
    }

    if ($field['settings']['type'] === 'checkbox') {
        echo $this->Form->input("FieldData.FieldList.{$field['id']}.data", array('type' => 'select', 'label' => $field['label'], 'multiple' => 'checkbox', 'options' => $options, 'value' => $selected));
    } else {
        echo $this->Form->input("FieldData.FieldList.{$field['id']}.data", array('type' => 'radio', 'separator' => '<br/>', 'options' => $options, 'legend' => $field['label'], 'checked' => @$selected[0]));
    }

    echo $this->Form->hidden("FieldData.FieldList.{$field['id']}.id", array('value' => $field['FieldData']['id']));
?>

<?php if (!empty($field['description'])): ?>
    <em><?php echo $field['description']; ?></em>
<?php endif; ?>