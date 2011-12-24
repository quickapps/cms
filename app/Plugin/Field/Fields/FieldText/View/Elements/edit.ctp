<?php
    $data['settings']['type'] = isset($data['settings']['type']) ? $data['settings']['type'] : 'text';

    if (!isset($data['FieldData'])) {
        $options = array(
            'type' => $data['settings']['type'],
            'label' => $data['label'],
            'class' => $data['settings']['text_processing']
        );

        if ($data['required']) {
            $options['required'] = 'required';
        }

        echo $this->Form->input("FieldData.FieldText.{$data['id']}.data", $options);
        echo $this->Form->hidden("FieldData.FieldText.{$data['id']}.id", array('value' => null));
    } else {
        if (isset($this->data['FieldData']['FieldText'][$data['id']]['data'])) {
            $value = $this->data['FieldData']['FieldText'][$data['id']]['data'];
        } else {
            $value = @$data['FieldData']['data'];
        }

        $options = array(
            'type' => $data['settings']['type'],
            'label' => $data['label'],
            'value' => @$value,
            'class' => @$data['settings']['text_processing']
        );

        if ($data['required']) {
            $options['required'] = 'required';
        }

        $data['FieldData'] = array_merge(array('id' => null, 'field_id' => null, 'foreignKey' => null, 'belongsTo' => null, 'data' => ''), $data['FieldData']);
        echo $this->Form->input("FieldData.FieldText.{$data['id']}.data", $options);
        echo $this->Form->hidden("FieldData.FieldText.{$data['id']}.id", array('value' => $data['FieldData']['id']));
    }

?>

<?php if (!empty($data['description'])): ?>
    <em><?php echo $data['description']; ?></em>
<?php endif; ?>