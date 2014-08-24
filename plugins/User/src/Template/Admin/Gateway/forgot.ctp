<?php echo $this->Form->create(null); ?>
	<?php echo $this->Form->input('username', ['label' => __d('user', 'Username or e-Mail address') . ' *']); ?>
	<?php echo $this->Form->submit(__d('user', 'e-Mail new password')); ?>
<?php echo $this->Form->end(); ?>