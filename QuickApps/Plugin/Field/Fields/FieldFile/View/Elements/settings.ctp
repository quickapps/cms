<?php
	echo $this->Form->input('Field.settings.extensions',
		array(
			'type' => 'text',
			'label' => __t('Allowed extensions'),
			'helpBlock' => __t('Comma separated. e.g. jpg,gif,png')
		)
	);
?>

<?php
	$ranges = Hash::combine(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10), '{n}', '{n}');
	$ranges['custom'] = __t('Custom');

	echo $this->Form->input('Field.settings.multi',
		array(
			'type' => 'select',
			'options' => $ranges,
			'label' => __t('Number of files'),
			'onchange' => "if (isNaN(this.value)) { $('.custom-multi').show(); } else { $('.custom-multi').hide(); }"
		)
	);

	echo '<div class="custom-multi" style="' . ($this->data['Field']['settings']['multi'] == 'custom' ? '' : 'display:none') . '">' . 
		$this->Form->input('Field.settings.multi_custom',
			array(
				'type' => 'text',
				'label' => __t('Customized number of files'),
				'onkeyup' => "if (/\D/g.test(this.value)) { this.value = this.value.replace(/\D/g,'') }",
				'helpBlock' => __t('Maximum number of files users can upload for this field.')
			)
		) . '</div>';
?>

<?php
	echo $this->Form->input('Field.settings.upload_folder',
		array(
			'type' => 'text',
			'label' => __t('Upload folder'),
			'helpBlock' => __t('Optional subdirectory within the upload destination where files will be stored. Do not include preceding or trailing slashes.')
		)
	);
?>

<?php
	echo $this->Form->input('Field.settings.description',
		array(
			'type' => 'checkbox',
			'label' => __t('Enable Description field'),
			'helpBlock' => __t('The description field allows users to enter a description about the uploaded file.')
		)
	);
?>