<?php
    $field['settings']['type'] = isset($field['settings']['type']) ? $field['settings']['type'] : 'text';

    if (!isset($field['FieldData'])) {
        $options = array(
            'type' => $field['settings']['type'],
            'label' => $field['label'],
            'class' => $field['settings']['text_processing']
        );

        if ($field['required']) {
            $options['required'] = 'required';
        }

        echo $this->Form->input("FieldData.FieldText.{$field['id']}.data", $options);
        echo $this->Form->hidden("FieldData.FieldText.{$field['id']}.id", array('value' => null));
    } else {
        if (isset($this->data['FieldData']['FieldText'][$field['id']]['data'])) {
            $value = $this->data['FieldData']['FieldText'][$field['id']]['data'];
        } else {
            $value = @$field['FieldData']['data'];
        }

        $options = array(
            'type' => $field['settings']['type'],
            'label' => $field['label'],
            'value' => @$value,
            'class' => @$field['settings']['text_processing']
        );

        if ($field['required']) {
            $options['required'] = 'required';
        }

        $field['FieldData'] = array_merge(array('id' => null, 'field_id' => null, 'foreignKey' => null, 'belongsTo' => null, 'data' => ''), $field['FieldData']);
        echo $this->Form->input("FieldData.FieldText.{$field['id']}.data", $options);
        echo $this->Form->hidden("FieldData.FieldText.{$field['id']}.id", array('value' => $field['FieldData']['id']));
    }

?>

<?php if (!empty($field['description'])): ?>
    <em><?php echo $field['description']; ?></em>
<?php endif; ?>