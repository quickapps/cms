<?php echo $this->Flash->render('register'); ?>

<?php if (!$registered): ?>
	<?php echo $this->Form->create($user, ['id' => 'user-form']); ?>
		<fieldset>
			<legend><?php echo __d('user', 'Creating New Account'); ?></legend>
			<?php echo $this->Form->input('name', ['label' => __d('user', 'Real name')]); ?>
			<em class="help-block"><?php echo __d('user', 'Your real name, e.g. John Locke'); ?></em>

			<?php echo $this->Form->input('username', ['label' => __d('user', 'User Name')]); ?>
			<em class="help-block"><?php echo __d('user', 'Alphanumeric characters only and "_" symbol. e.g. My_user_Email'); ?></em>

			<?php echo $this->Form->input('email', ['label' => __d('user', 'e-Mail')]); ?>
			<em class="help-block"><?php echo __d('user', 'Must be unique.'); ?></em>

			<?php echo $this->Form->input('web', ['label' => __d('user', 'Website')]); ?>
			<em class="help-block"><?php echo __d('user', 'Your website, e.g. http://john-locke.about.me'); ?></em>

			<?php echo $this->Form->input('locale', ['type' => 'select', 'options' => $languages, 'label' => __d('user', 'Preferred Language'), 'empty' => __d('user', 'Default')]); ?>
			<em class="help-block"><?php echo __d('user', 'Preferred language'); ?></em>

			<?php echo $this->Form->input('password', ['type' => 'password', 'label' => __d('user', 'Password')]); ?>
			<em class="help-block"><?php echo __d('user', 'At least six characters long.'); ?></em>

			<?php echo $this->Form->input('password2', ['type' => 'password', 'label' => __d('user', 'Confirm Password')]); ?>

			<?php if (isset($user->_fields) && $user->_fields->count()): ?>
			<hr />

			<fieldset>
				<legend><?php echo __d('user', 'Additional Information'); ?></legend>
				<?php foreach ($user->_fields as $field): ?>
					<?php echo $this->Form->input($field); ?>
				<?php endforeach; ?>
			</fieldset>
			<?php endif; ?>

			<?php echo $this->Form->submit(__d('user', 'Register')); ?>
		</fieldset>
	<?php echo $this->Form->end(); ?>
<?php endif; ?>