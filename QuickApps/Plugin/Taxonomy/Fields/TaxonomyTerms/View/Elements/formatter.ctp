<!-- List Formatter Form -->
<?php
	echo $this->Form->input("Field.settings.display.{$display}.type",
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

	echo $this->Form->input("Field.settings.display.{$display}.url_prefix",
		array(
			'label' => __t('URL prefix'),
			'type' => 'text',
			'helpBlock' => __t('Valid only when format is "Link (localized)". Adds a prefix to each term link url.')
		)
	);