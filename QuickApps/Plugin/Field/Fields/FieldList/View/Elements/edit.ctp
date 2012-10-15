<?php
	$data['field']['FieldData'] = !isset($data['field']['FieldData']) ? array() : $data['field']['FieldData'];
	$data['field']['FieldData'] = array_merge(array('id' => null, 'field_id' => null, 'foreignKey' => null, 'belongsTo' => null, 'data' => ''), $data['field']['FieldData']);
	$_options = $options = array();

	if (!empty($data['field']['settings']['options'])) {
		$_options = explode("\n", $data['field']['settings']['options']);

		foreach ($_options as $option) {
			$option = explode("|",$option);
			$value = $option[0];
			$label = isset($option[1]) ? $option[1] : $option[0];
			$options[$value] = $label;
		}
	}

	$data['field']['settings']['type'] = empty($data['field']['settings']['type']) ? 'checkbox' : $data['field']['settings']['type'];

	if (isset($this->data['FieldData']['FieldList'][$data['field']['id']]['data'])) {
		$selected = $this->data['FieldData']['FieldList'][$data['field']['id']]['data'];
	} else {
		$selected = explode('|', (string)$data['field']['FieldData']['data']);
	}

	if ($data['field']['settings']['type'] === 'checkbox') {
		echo $this->Form->input("FieldData.FieldList.{$data['field']['id']}.data", array('type' => 'select', 'label' => $data['field']['label'], 'multiple' => 'checkbox', 'options' => $options, 'value' => $selected));
	} else {
		echo $this->Form->input("FieldData.FieldList.{$data['field']['id']}.data", array('type' => 'radio', 'separator' => '<br/>', 'options' => $options, 'legend' => $data['field']['label'], 'value' => @$selected[0]));
	}

	echo $this->Form->hidden("FieldData.FieldList.{$data['field']['id']}.id", array('value' => $data['field']['FieldData']['id']));
?>

<?php if (!empty($data['field']['description'])): ?>
	<?php echo $this->Form->helpBlock($data['field']['description']); ?>
<?php endif; ?>