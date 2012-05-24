<?php
	if (isset($data['field']['settings']['options']) &&
		!empty($data['field']['settings']['options'])
	) {
		switch ($data['display']['label']) {
			case 'inline':
				echo "<h4 class=\"field-label\" style=\"display:inline;\">{$data['field']['label']}</h4> ";
			break;

			case 'above':
				echo "<h4 class=\"field-label\">{$data['field']['label']}</h4> ";
			break;
		}

		$formatter_data = array(
			'content' => (isset($data['field']['FieldData']['data']) ? $data['field']['FieldData']['data'] : ''),
			'options' => $data['field']['settings']['options'],
			'format' => $data['display']
		);
		$html = $this->Layout->hook('field_list_formatter', $formatter_data, array('collectReturn' => false));

		echo $html;
	}