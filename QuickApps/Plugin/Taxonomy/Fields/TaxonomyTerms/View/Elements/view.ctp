<?php
	switch ($data['display']['label']) {
		case 'inline':
			echo "<h4 class=\"field-label\" style=\"display:inline;\">{$data['field']['label']}:</h4> ";
		break;

		case 'above':
			echo "<h4 class=\"field-label\">{$data['field']['label']}</h4> ";
		break;
	}

	$formatter_data = array(
		'content' => (isset($data['field']['FieldData']['data']) ? $data['field']['FieldData']['data'] : ''),
		'format' => $data['display']
	);
	$html = $this->Layout->hook('taxonomy_terms_formatter', $formatter_data, array('collectReturn' => false));

	echo $html;