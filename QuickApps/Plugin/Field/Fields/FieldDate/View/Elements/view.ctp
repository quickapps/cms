<?php
	switch ($data['display']['label']) {
		case 'inline':
			echo "<h4 class=\"field-label\" style=\"display:inline;\">{$data['field']['label']}</h4> ";
		break;

		case 'above':
			echo "<h4 class=\"field-label\">{$data['field']['label']}</h4> ";
		break;
	}

	$html = isset($data['field']['FieldData']['data']) ? $data['field']['FieldData']['data'] : '';

	echo $html;