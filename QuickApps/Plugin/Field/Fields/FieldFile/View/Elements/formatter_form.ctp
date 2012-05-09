<!-- File Formatter Form -->
<?php
	$viewMode = $this->data['Field']['viewMode'];
	$formats = array(
		'link' => __t('Link to file'),
		'table' => __t('Table of Files'),
		'url' => __t('File URL')
	);

	$formatOptions = (array)$this->Layout->hook('field_file_formats_alter', $formats, array('collectReturn' => true));

	echo $this->Form->input("Field.settings.display.{$viewMode}.type",
		array(
			'label' => false,
			'type' => 'select',
			'options' => $formats,
			'empty' => false
		)
	);

	foreach ($formatOptions as $options) {
		echo "<div class=\"format-options\" style=\"display:none;\">{$options}</div>\n";
	}

	$script = "
		$('#" . Inflector::camelize("Field_settings_display_{$viewMode}_type") . "').change(function () {
			$('.format-options').hide();

			try {
				$('.format-options .' + $(this).val()).show();
				$('.format-options .' + $(this).val()).parent().show();
			} catch (e) { }
		});
	";

	if (!empty($this->data['Field']['settings']['display'][$viewMode]['type'])) {
		$script .= "
			try {
				$('.format-options ." . $this->data['Field']['settings']['display'][$viewMode]['type'] . "').show();
				$('.format-options ." . $this->data['Field']['settings']['display'][$viewMode]['type'] . "').parent().show();
			} catch(e) { }
		";
	}

	$this->Layout->script('$(document).ready(function () {' . $script . '});', 'inline');