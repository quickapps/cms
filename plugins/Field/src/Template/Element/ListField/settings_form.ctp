<?php
	echo $this->Form->input('type', [
		'type' => 'select',
		'options' => [
			'radio' => __d('field', 'Radio buttons'),
			'checkbox' => __d('field', 'Check boxes')
		],
		'empty' => false,
		'label' => __d('field', 'List Type')
	]);
?>
<?php
	echo $this->Form->input('options', [
		'type' => 'textarea',
		'label' => __d('field', 'Options')
	]);
?>

<em class="help-block"><?php echo __d('field', 'The possible values this field can contain. Enter one value per line, in the format <code>key|label</code>.'); ?></em>
<em class="help-block"><?php echo __d('field', 'The key is the stored value. The label will be used in displayed values and edit forms.'); ?></em>
<em class="help-block"><?php echo __d('field', 'The label is optional: if a line contains a single string, it will be used as key and label.'); ?></em>
