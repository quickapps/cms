<fieldset>
	<legend><?php echo __d('user', 'e-Mails'); ?></legend>


	<strong><?php echo __d('user', 'Available variables are:'); ?></strong>
	<div>
		<code>[user:name]</code>
		<code>[user:email]</code>
		<code>[user:activation-url]</code>
		<code>[user:one-time-login-url]</code>
		<code>[user:cancel-url]</code>
		<code>[site:name]</code>
		<code>[site:url]</code>
		<code>[site:description]</code>
		<code>[site:slogan]</code>
		<code>[site:login-url]</code>
	</div>

	<hr />

	<div class="row">
		<div class="col-md-6">
			<div class="form-group form-group-sm">
				<h3><?php echo __d('user', 'Welcome'); ?></h3>
				<em class="help-block"><?php echo __d('user', 'Edit the welcome e-mail messages sent to new member accounts created by an administrator.'); ?></em>
				<?php echo $this->Form->input('message_welcome_subject', ['label' => __d('user', 'Subject')]); ?>
				<?php echo $this->Form->input('message_welcome_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
			</div>

			<div class="form-group">
				<h3><?php echo __d('user', 'Account Activation'); ?></h3>
				<em class="help-block"><?php echo __d('user', 'Enable and edit e-mail messages sent to users upon account activation (when an administrator activates an account of a user who has already registered).'); ?></em>
				<?php echo $this->Form->input('message_activation_subject', ['label' => __d('user', 'Subject')]); ?>
				<?php echo $this->Form->input('message_activation_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
			</div>

			<div class="form-group">
				<h3><?php echo __d('user', 'Account Blocked'); ?></h3>
				<em class="help-block"><?php echo __d('user', 'Enable and edit e-mail messages sent to users when their accounts are blocked.'); ?></em>
				<?php echo $this->Form->input('message_blocked_subject', ['label' => __d('user', 'Subject')]); ?>
				<?php echo $this->Form->input('message_blocked_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				<h3><?php echo __d('user', 'Password Recovery'); ?></h3>
				<em class="help-block"><?php echo __d('user', 'Edit the e-mail messages sent to users who request a new password.'); ?></em>
				<?php echo $this->Form->input('message_password_recovery_subject', ['label' => __d('user', 'Subject')]); ?>
				<?php echo $this->Form->input('message_password_recovery_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
			</div>

			<div class="form-group">
				<h3><?php echo __d('user', 'Account Canceled'); ?></h3>
				<em class="help-block"><?php echo __d('user', 'Enable and edit e-mail messages sent to users when their accounts are canceled.'); ?></em>
				<?php echo $this->Form->input('message_canceled_subject', ['label' => __d('user', 'Subject')]); ?>
				<?php echo $this->Form->input('message_canceled_recovery_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
			</div>
		</div>
	</div>
</fieldset>