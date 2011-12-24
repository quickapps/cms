<?php
    $data['FieldData'] = !isset($data['FieldData']) ? array() : $data['FieldData'];
    $data['FieldData'] = array_merge(array('id' => null, 'field_id' => null, 'foreignKey' => null, 'belongsTo' => null, 'data' => ''), $data['FieldData']);
    $_options = $options = array();

    if (!empty($data['settings']['options'])) {
        $_options = explode("\n", $data['settings']['options']);

        foreach ($_options as $option) {
            $option = explode("|",$option);
            $value = $option[0];
            $label = isset($option[1]) ? $option[1] : $option[0];
            $options[$value] = $label;
        }
    }

    $data['settings']['type'] = empty($data['settings']['type']) ? 'checkbox' : $data['settings']['type'];

    if (isset($this->data['FieldData']['FieldList'][$data['id']]['data'])) {
        $selected = $this->data['FieldData']['FieldList'][$data['id']]['data'];
    } else {
        $selected = explode('|', (string)$data['FieldData']['data']);
    }

    if ($data['settings']['type'] === 'checkbox') {
        echo $this->Form->input("FieldData.FieldList.{$data['id']}.data", array('type' => 'select', 'label' => $data['label'], 'multiple' => 'checkbox', 'options' => $options, 'value' => $selected));
    } else {
        echo $this->Form->input("FieldData.FieldList.{$data['id']}.data", array('type' => 'radio', 'separator' => '<br/>', 'options' => $options, 'legend' => $data['label'], 'checked' => @$selected[0]));
    }

    echo $this->Form->hidden("FieldData.FieldList.{$data['id']}.id", array('value' => $data['FieldData']['id']));
?>

<?php if (!empty($data['description'])): ?>
    <em><?php echo $data['description']; ?></em>
<?php endif; ?>