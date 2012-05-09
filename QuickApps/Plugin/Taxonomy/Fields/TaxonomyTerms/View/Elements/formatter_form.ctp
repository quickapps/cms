<!-- List Formatter Form -->
<?php
	$viewMode = $this->data['Field']['viewMode'];

	echo $this->Form->input("Field.settings.display.{$viewMode}.type",
		array(
			'label' => false,
			'type' => 'select',
			'options' => array(
				'plain' => __t('Plain'),
				'link-localized' => __t('Link (localized)'),
				'plain-localized' => __t('Plain (localized)')
			),
			'empty' => false
		)
	);

	echo $this->Form->input("Field.settings.display.{$viewMode}.url_prefix",
		array(
			'label' => __t('URL prefix'),
			'type' => 'text'
		)
	);
?>
<em><?php echo __t('Valid only when format is "Link (localized)". Adds a prefix to each term link url.'); ?></em>