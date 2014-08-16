<?php echo $this->Form->create(null); ?>
	<?php echo $this->Form->input('username'); ?>
	<?php echo $this->Form->input('password'); ?>
	<?php echo $this->Form->submit('login'); ?>
<?php echo $this->Form->end(); ?>