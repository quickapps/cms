<?php echo $this->Form->create(null); ?>
	<?php echo $this->Form->input('username', ['label' => __d('user', 'Username')]); ?>
	<?php echo $this->Form->input('password', ['label' => __d('user', 'Password')]); ?>
	<?php echo $this->Form->submit(__d('user', 'Login')); ?>
<?php echo $this->Form->end(); ?>