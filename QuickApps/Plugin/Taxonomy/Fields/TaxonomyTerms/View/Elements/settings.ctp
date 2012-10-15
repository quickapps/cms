<?php
	echo $this->Form->input("Field.settings.vocabulary",
		array(
			'type' => 'select',
			'options' => ClassRegistry::init('Taxonomy.Vocabulary')->find('list'),
			'empty' => false,
			'label' => __t('Vocabulary *'),
			'helpBlock' => __t('The vocabulary which supplies the options for this field.')
		)
	);

	echo $this->Form->input("Field.settings.type",
		array(
			'type' => 'select',
			'options' => array(
				'checkbox' => __t('Check boxes/radio buttons'),
				'select' => __t('Select list'),
				'autocomplete' => __t('Autocomplete term (tagging)')
			),
			'empty' => false,
			'label' => __t('Element Type'),
			'helpBlock' => __t('The type of form element you would like to present to the user when creating this field.')
		)
	);

	echo $this->Form->input("Field.settings.max_values",
		array(
			'type' => 'select',
			'options' => array_merge(array(0 => __t('Unlimited')), Hash::combine(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10), '{n}', '{n}')),
			'empty' => false,
			'label' => __t('Number of values'),
			'helpBlock' => __t('Maximum number of values users can enter for this field.')
		)
	);