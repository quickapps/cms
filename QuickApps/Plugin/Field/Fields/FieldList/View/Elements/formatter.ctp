<!-- List Formatter Form -->
<?php
	echo $this->Form->input("Field.settings.display.{$display}.type",
		array(
			'label' => false,
			'type' => 'select',
			'options' => array(
				'default' => __t('Default'),
				'key' => __t('Key')
			),
			'empty' => false
		)
	);