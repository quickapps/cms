<?php
	$options = ClassRegistry::init('Taxonomy.Vocabulary')->find('list', array('recursive' => -1));

	echo $this->Form->input('Block.settings.vocabularies',
		array(
			'type' => 'select',
			'multiple' => 'checkbox',
			'options' => $options,
			'label' => __t('Vocabularies')
		)
	);
?>

<p>&nbsp;</p>

<?php
	echo $this->Form->input('Block.settings.content_counter',
		array(
			'type' => 'checkbox',
			'options' => array(0 => __t('No'), 1 => __t('Yes')),
			'label' => __t('Show content count')
		)
	);

	echo $this->Form->input('Block.settings.show_vocabulary',
		array(
			'type' => 'checkbox',
			'options' => array(0 => __t('No'), 1 => __t('Yes')),
			'label' => __t('Show vocabulary and its terms as tree')
		)
	);

	echo $this->Form->input('Block.settings.terms_cache_duration',
		array(
			'type' => 'select',
			'label' => __t('Cache terms counters for'),
			'options' => array(
				'+10 minutes' => __t('%s Minutes', 10),
				'+20 minutes' => __t('%s Minutes', 20),
				'+40 minutes' => __t('%s Minutes', 40),
				'+1 hour' => __t('%s Hour', 1),
				'+2 hours' => __t('%s Hours', 2),
				'+4 hours' => __t('%s Hours', 3),
				'+7 hours' => __t('%s Hours', 7),
				'+11 hours' => __t('%s Hours', 11),
				'+16 hours' => __t('%s Hours', 16),
				'+22 hours' => __t('%s Hours', 22),
				'+1 day' => __t('%s Days', 1),
				'+3 day' => __t('%s Days', 3),
				'+5 day' => __t('%s Days', 5),
				'+1 week' => __t('%s Weeks', 1)
			)
		)
	);

	echo $this->Form->input('Block.settings.url_prefix',
		array(
			'between' => $this->Html->url('/', true) . 'search/',
			'after' => ' term:my-term-slug',
			'type' => 'text',
			'label' => __t('URL prefix')
		)
	);