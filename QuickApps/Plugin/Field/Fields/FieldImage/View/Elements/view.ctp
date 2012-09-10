<?php
	switch ($data['display']['label']) {
		case 'inline':
			echo "<h4 class=\"field-label\" style=\"display:inline;\">{$data['field']['label']}</h4> ";
		break;

		case 'above':
			echo "<h4 class=\"field-label\" style=\"display:block;\">{$data['field']['label']}</h4> ";
		break;
	}

	$formatter_data = array(
		'field_id' => $data['field']['id'],
		'content' => (isset($data['field']['FieldData']['data']['files']) ? $data['field']['FieldData']['data'] : array('files' => array())),
		'settings' => $data['field']['settings'],
		'format' => $data['display']
	);
	$html = $this->Layout->hook('field_image_formatter', $formatter_data, array('collectReturn' => false));

	echo $html;