<?php echo $this->Form->create('User'); ?>
	<!-- Settings -->
	<?php echo $this->Html->useTag('fieldsetstart', $this->data['User']['username']); ?>
		<?php echo $this->Form->input('name', array('required' => 'required', 'type' => 'text', 'label' => __t('Real name *'))); ?>
		<em><?php echo __t('Your real name, it is used only for identification purposes. i.e: John Locke'); ?></em>
		<?php echo $this->Form->input('username', array('required' => 'required', 'disabled' => 'disabled', 'type' => 'text', 'label' => __t('User name *'))); ?>
		<em><?php echo __t('Usernames cannot be changed'); ?></em>
		<?php echo $this->Form->input('avatar', array('type' => 'text', 'label' => __t('Avatar'))); ?>
		<em><?php echo __t('Full url to avatar image file. i.e: http://www.example.com/my-avatar.jpg'); ?></em>
		<?php echo $this->Form->input('email', array('required' => 'required', 'type' => 'email', 'label' => __t('E-mail *'))); ?>
		<?php echo $this->Form->input('public_email', array('type' => 'checkbox', 'label' => __t('Public email'))); ?>
		<?php echo $this->Form->input('language', array('type' => 'select', 'options' => $languages, 'label' => __t('Language'))); ?>
		<?php App::import('Lib', 'Locale.QALocale'); ?>
		<?php echo $this->Form->input('timezone', array('type' => 'select', 'options' => QALocale::time_zones(), 'label' => __t('Time zone'))); ?>
		<?php echo $this->Form->input('password', array('type' => 'password', 'label' => __t('New password'), 'value' => '')); ?>
		<?php echo $this->Form->input('password2', array('type' => 'password', 'label' => __t('Confirm password'))); ?>
		<em><?php echo __t('If you would like to change the password type a new one. Otherwise leave this blank.'); ?></em>

		<?php foreach ($this->data['Field'] as $field): ?>
			<?php echo $this->Node->renderField($field, true); ?>
		<?php endforeach; ?>

	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- Submit -->
	<?php echo $this->Form->input(__t('Update'), array('type' => 'submit')); ?>
<?php echo $this->Form->end(); ?>
