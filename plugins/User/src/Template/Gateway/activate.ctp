<?php echo $this->Flash->render('activate'); ?>

<?php if ($activated): ?>
	<?php
		echo __d('user', 'Congratulations, your account has been successfully activated. You can now login in click <a href="{0}">here</a>.',
			$this->Url->build([
				'plugin' => 'User',
				'controller' => 'gateway',
				'action' => 'login',
			])
		);
	?>
<?php endif; ?>