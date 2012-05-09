<?php
	$render_type = @$block['Block']['settings']['list_type'] != 'select' ? 'html' : 'select';

	echo $this->Form->input('Block.settings.list_type',
		array(
			'type' => 'radio',
			'legend' => __t('Display language as'),
			'options' => array('html' => __t('HTML List'), 'select' => __t('Selectbox')),
			'separator' => '<br />',
			'onclick' => 'if (this.value == "html") { $("#list-options").show(); } else { $("#list-options").hide(); }',
			'value' => $render_type
		)
	);
?>
<div id="list-options" style="<?php echo $render_type == 'select' ? 'display:none;' : ''; ?>">
	<?php echo $this->Form->input('Block.settings.flags', array('type' => 'checkbox',  'label' => __t('Display language flag icon'), 'checked' => @$block['Block']['settings']['flags'])); ?>
	<?php echo $this->Form->input('Block.settings.name', array('type' => 'checkbox',  'label' => __t('Display language name'), 'checked' => @$block['Block']['settings']['name'])); ?>
</div>