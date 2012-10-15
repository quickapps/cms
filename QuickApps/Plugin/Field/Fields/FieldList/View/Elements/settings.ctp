<?php
	echo $this->Form->input("Field.settings.type",
		array(
			'type' => 'select',
			'options' => array('radio' => __t('Radio buttons'), 'checkbox' => __t('Checkboxes')),
			'empty' => false,
			'label' => __t('List Type')
		)
	);

	echo $this->Form->input("Field.settings.options",
		array(
			'type' => 'textarea',
			'label' => __t('Options')
		)
	);
?>

<ul>
	<li><?php echo $this->Form->helpBlock(__t('The possible values this field can contain. Enter one value per line, in the format <b>key|label</b>.')); ?></li>
	<li><?php echo $this->Form->helpBlock(__t('The key is the stored value. The label will be used in displayed values and edit forms.')); ?></span>
	<li><?php echo $this->Form->helpBlock(__t('The label is optional: if a line contains a single string, it will be used as key and label.')); ?></span>
</ul>