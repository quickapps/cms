<!-- Image Formatter Form -->
<?php
	echo $this->Form->input("Field.settings.display.{$display}.image_style",
		array(
			'label' => __t('Image style'),
			'type' => 'select',
			'options' => (array)FieldImage::previewsOptions(),
			'empty' => __t('None (original image)')
		)
	);

	$formats = array(
		'content' => __t('Content'),
		'file' => __t('File')
	);

	$formatOptions = (array)$this->Layout->hook('field_image_formats_alter', $formats, array('collectReturn' => true));

	echo $this->Form->input("Field.settings.display.{$display}.type",
		array(
			'label' => __t('Link image to'),
			'type' => 'select',
			'options' => $formats,
			'empty' => __t('Nothing')
		)
	);

	foreach ($formatOptions as $options) {
		echo "<div class=\"format-options\" style=\"display:none;\">{$options}</div>\n";
	}

	$script = "
		$('#" . Inflector::camelize("Field_settings_display_{$display}_type") . "').change(function () {
			$('.format-options').hide();

			try {
				$('.format-options .' + $(this).val()).show();
				$('.format-options .' + $(this).val()).parent().show();
			} catch (e) { }
		});
	";

	if (!empty($this->data['Field']['settings']['display'][$display]['type'])) {
		$script .= "
			try {
				$('.format-options ." . $this->data['Field']['settings']['display'][$display]['type'] . "').show();
				$('.format-options ." . $this->data['Field']['settings']['display'][$display]['type'] . "').parent().show();
			} catch(e) { }
		";
	}

	$this->Layout->script('$(document).ready(function () {' . $script . '});', 'inline');