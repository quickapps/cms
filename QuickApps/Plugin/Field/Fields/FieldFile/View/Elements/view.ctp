<?php
	switch($display['label']) {
		case 'inline':
			echo "<h4 class=\"field-label\" style=\"display:inline;\">{$field['label']}</h4> ";
		break;

		case 'above':
			echo "<h4 class=\"field-label\" style=\"display:block;\">{$field['label']}</h4> ";
		break;
	}

	$formatter_data = array(
		'content' => (isset($field['FieldData']['data']['files']) ? $field['FieldData']['data'] : array('files' => array())),
		'settings' => $field['settings'],
		'format' => $display
	);
	$html = $this->Layout->hook('field_file_formatter', $formatter_data, array('collectReturn' => false));

	echo $html;