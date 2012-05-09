<?php echo $this->Form->input('Module.settings.slider_folder', array('between' => QuickApps::strip_language_prefix($this->Html->url('/files/', true)), 'type' => 'text', 'label' => __t('Image slider folder'))); ?>
<em>
	<?php echo __t('Recommended images size:') ?> 974x302px<br/>
</em>
