<div class="users form password-recovery">
	<?php echo $this->Form->create('User');?>
		<?php echo $this->Html->useTag('fieldsetstart', __t('Request new password')); ?>
			<?php echo $this->Form->input('email'); ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Form->end(__t('E-mail new password'));?>
</div>