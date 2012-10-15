<?php echo $this->Form->create('Module', array('url' => "/admin/system/themes/settings/{$theme_name}")); ?>
	<?php echo $this->Form->input('Module.name', array('type' => 'hidden', 'value' => 'Theme' . $theme_name)); ?>

	<?php if ($this->Layout->elementExists(Inflector::camelize("theme_{$theme_name}") . '.settings')): ?>
		<?php echo $this->Html->useTag('fieldsetstart', __t('"%s" Theme', $theme_name)); ?>
			<?php echo $this->element(Inflector::camelize("theme_{$theme_name}") . '.settings'); ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php endif; ?>

	<?php if ($ThemeCustomizer = $this->ThemeCustomizer->generate($theme_name)): ?>
		<?php echo $this->Html->useTag('fieldsetstart', __t('Customize Appearance')); ?>
			<?php echo $ThemeCustomizer; ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php endif; ?>

	<?php echo $this->Html->useTag('fieldsetstart', __t('Toggle display')); ?>
		<?php echo $this->Form->input('Module.settings.site_logo', array('type' => 'checkbox', 'label' => __t('Logo'))); ?>
		<?php echo $this->Form->input('Module.settings.site_name', array('type' => 'checkbox', 'label' => __t('Site name'))); ?>
		<?php echo $this->Form->input('Module.settings.site_slogan', array('type' => 'checkbox', 'label' => __t('Site slogan'))); ?>
		<?php echo $this->Form->input('Module.settings.site_favicon', array('type' => 'checkbox', 'label' => __t('Shortcut icon'))); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<?php echo $this->Html->useTag('fieldsetstart', __t('Logo')); ?>
		<?php echo $this->Form->input('Module.settings.site_logo_url', array('type' => 'text', 'label' => __t('Logo image URL'), 'helpBlock' => __t('Leave blank to use default logo.'))); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<?php echo $this->Html->useTag('fieldsetstart', __t('Shortcut icon')); ?>
		<?php echo $this->Form->input('Module.settings.site_favicon_url', array('type' => 'text', 'label' => __t('Shortcut icon URL'), 'helpBlock' => __t('Leave blank to use default favicon.'))); ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<?php echo $this->Form->submit(__t('Save')); ?>
<?php echo $this->Form->end(); ?>