<!-- Text Formatter Form -->
<?php
	$actualType = @$this->data['Field']['settings']['display'][$display]['type'];

	echo $this->Form->input("Field.settings.display.{$display}.type",
		array(
			'label' => false,
			'type' => 'select',
			'options' => array(
				'full' => __t('Full'),
				'plain' => __t('Plain'),
				'trimmed' => __t('Trimmed')
			),
			'empty' => false,
			'escape' => false,
			'onchange' => "if (this.value == 'trimmed') { $('#trimmed').show(); } else { $('#trimmed').hide(); };"
		)
	);
?>

<div id="trimmed" style="<?php echo $actualType !== 'trimmed' ? 'display:none;' : ''; ?>">
	<?php
		echo $this->Form->input("Field.settings.display.{$display}.trim_length",
			array(
				'type' => 'text',
				'label' => __t('Trim length or read-more-cutter')
			)
		);
	?>

	<ul>
		<li><?php echo $this->Form->helpBlock(__t('Numeric value will convert content to plain text and then trim it to the specified number of chars. e.g.: 400')); ?></li>
		<li><?php echo $this->Form->helpBlock(__t('String value will cut the content in two by the specified string, the first part will be displayed. e.g.: &lt;!-- readmore --&gt;')); ?></li>
	</ul>
</div>