<?php
	$data['field']['settings']['type'] = isset($data['field']['settings']['type']) ? $data['field']['settings']['type'] : 'text';
	$options = array(
		'type' => $data['field']['settings']['type'],
		'label' => $data['field']['label'],
		'class' => $data['field']['settings']['text_processing']
	);

	if ($data['field']['required']) {
		$options['required'] = 'required';
	}

	if (!isset($data['field']['FieldData'])) {
		echo $this->Form->input("FieldData.FieldText.{$data['field']['id']}.data", $options);
		echo $this->Form->hidden("FieldData.FieldText.{$data['field']['id']}.id", array('value' => null));
	} else {
		if (isset($this->data['FieldData']['FieldText'][$data['field']['id']]['data'])) {
			$value = $this->data['FieldData']['FieldText'][$data['field']['id']]['data'];
		} else {
			$value = @$data['field']['FieldData']['data'];
		}

		$options['value'] = $value;
		$data['field']['FieldData'] = array_merge(array('id' => null, 'field_id' => null, 'foreignKey' => null, 'belongsTo' => null, 'data' => ''), $data['field']['FieldData']);

		echo $this->Form->input("FieldData.FieldText.{$data['field']['id']}.data", $options);
		echo $this->Form->hidden("FieldData.FieldText.{$data['field']['id']}.id", array('value' => $data['field']['FieldData']['id']));
	}
?>

<?php if (!empty($data['field']['description'])): ?>
	<?php echo $this->Form->helpBlock($data['field']['description']); ?>
<?php endif; ?>