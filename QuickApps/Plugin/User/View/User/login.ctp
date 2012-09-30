<div class="users form">
	<h2><?php echo __t('Login'); ?></h2>
	<?php echo $this->Form->create('User', array('url' => '/user/login'));?>
		<?php echo $this->Html->useTag('fieldsetstart', null); ?>
			<?php
				echo $this->Form->input('username');
				echo $this->Form->input('password');
				echo $this->Form->input('remember', array('type' => 'checkbox', 'label' => __t('Remember')));
				echo $this->Form->submit(__t('Login'));
			?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Form->end();?>
</div>