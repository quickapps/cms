<?php
	echo $this->Form->input('vocabulary', [
		'type' => 'select',
		'label' => __d('taxonomy', 'Vocabulary *'),
		'options' => $vocabularies,
	]);
?>
<em class="help-block"><?php echo __d('taxonomy', 'The vocabulary which supplies the options for this field.'); ?></em>

<?php
	echo $this->Form->input('type', [
		'type' => 'select',
		'label' => __d('taxonomy', 'Element Type *'),
		'options' => [
			'checkbox' => __d('taxonomy', 'Check boxes/radio buttons'),
			'select' => __d('taxonomy', 'Select list'),
			'autocomplete' => __d('taxonomy', 'Auto-complete terms (tagging)'),
		],
	]);
?>
<em class="help-block"><?php echo __d('taxonomy', 'The type of form element you would like to present to the user when creating new contents.'); ?></em>

<?php
	echo $this->Form->input('max_values', [
		'type' => 'select',
		'label' => __d('taxonomy', 'Number of values *'),
		'options' => [
			0 => __d('taxonomy', 'Unlimited'),
			1 => '1',
			2 => '2',
			3 => '3',
			4 => '4',
			5 => '5',
			6 => '6',
			7 => '7',
			8 => '8',
			9 => '9',
			10 => '10',
		],
	]);
?>
<em class="help-block"><?php echo __d('taxonomy', 'Maximum number of values users can enter for this field.'); ?></em>
<em class="help-block"><?php echo __d('taxonomy', 'If you choose "Check boxes/radio buttons" as element type and you set "Number of values" to 1, radio buttons will be used.'); ?></em>

<?php echo $this->Form->input('error_message', ['label' => __d('taxonomy', 'Error message')]); ?>
<em class="help-block"><?php echo __d('taxonomy', 'Error message that is shown to the user when he/she exceeds the "Number of values".'); ?></em>