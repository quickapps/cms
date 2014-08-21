<?php
	echo $this->Form->input('formatter', [
		'label' => false,
		'type' => 'select',
		'options' => [
			'default' => __d('field', 'Default'),
			'key' => __d('field', 'Key')
		],
		'empty' => false
	]);
?>
