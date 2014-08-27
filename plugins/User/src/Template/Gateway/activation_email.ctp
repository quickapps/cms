<?php echo $this->Flash->render('activation_email'); ?>

<?php if (!$sent): ?>
	<?php echo $this->Form->create(null); ?>
		<?php echo $this->Form->input('username', ['label' => __d('user', 'Username or e-mail')]); ?>
		<?php echo $this->Form->submit(__d('user', 'Send Instructions')); ?>
	<?php echo $this->Form->end(); ?>
<?php endif; ?>