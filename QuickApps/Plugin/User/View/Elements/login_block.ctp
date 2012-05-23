<div class="user-login-form">
	<?php echo $this->Form->create('User', array('url' => '/user/login'));?>
		<?php echo $this->Html->useTag('fieldsetstart', null); ?>
			<?php
				echo $this->Form->input('username', array('label' => __t('Username')));
				echo $this->Form->input('password', array('label' => __t('Password')));
			?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Form->end(__t('Login'));?>

	<?php
		echo $this->Html->nestedList(
			array(
				$this->Html->link(__t('Create new account'), '/user/register'),
				$this->Html->link(__t('Request new password'), '/user/password_recovery')
			),
			array('id' => 'user-login-form-links')
		);
	?>
</div>