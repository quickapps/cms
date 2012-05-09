<?php if (!empty($links)): ?>
	<?php echo $this->Html->useTag('fieldsetstart', __t('Links')); ?>
		<?php echo $this->element('menu_edit'); ?>
	<?php echo $this->Html->useTag('fieldsetend', __t('Links')); ?>
<?php endif; ?>
&nbsp;