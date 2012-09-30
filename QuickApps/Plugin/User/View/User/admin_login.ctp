<h2><?php echo __t('Login'); ?></h2>

<?php echo $this->Form->create('User', array('url' => '/admin/user/login'));?>
	<?php
		echo $this->Form->input('User.username', array('label' => false, 'placeholder' => __t('Username...')));
		echo $this->Form->input('User.password', array('label' => false, 'placeholder' => __t('Password...')));
		echo $this->Form->input('User.remember', array('type' => 'checkbox', 'label' => __t('Remember')));
		echo $this->Form->submit(__t('Login'));
	?>
<?php echo $this->Form->end();?>