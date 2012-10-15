<?php
	$data['field']['FieldData'] = !isset($data['field']['FieldData']) ? array() : $data['field']['FieldData'];
	$data['field']['FieldData'] = array_merge(array('id' => null, 'field_id' => null, 'foreignKey' => null, 'belongsTo' => null, 'data' => ''), $data['field']['FieldData']);
	$selected = isset($data['field']['FieldData']['data']) ? $data['field']['FieldData']['data'] : '';
	$helpBlock = !empty($data['field']['description']) ? $data['field']['description'] : false;

	echo $this->Form->hidden("FieldData.FieldDate.{$data['field']['id']}.id", array('value' => $data['field']['FieldData']['id']));
	echo $this->Form->input("FieldData.FieldDate.{$data['field']['id']}.data", array('label' => $data['field']['label'], 'value' => $selected, 'readonly', 'helpBlock' => $helpBlock));
?>


<?php
	$__data = array(
		'id' => $data['field']['id'],
		'settings' => $data['field']['settings']
	);

	echo $this->Layout->hook('field_date_js_init', $__data);
?>