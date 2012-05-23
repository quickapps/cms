<?php
	echo $this->Form->input('Block.settings.limit',
		array(
			'type' => 'select',
			'label' => __t('Number of users'),
			'options' => array(
				5 => 5,
				10 => 10,
				15 => 15
			)
		)
	);