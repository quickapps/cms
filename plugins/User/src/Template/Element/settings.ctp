<fieldset>
	<legend><?php echo __d('user', 'e-Mails'); ?></legend>


	<strong><?php echo __d('user', 'Available variables are:'); ?></strong>
	<div>
		<code>[user:name]</code>
		<code>[user:username]</code>
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
			<div class="panel panel-default">
				<div class="panel-heading"><?php echo __d('user', 'Welcome'); ?></div>
				<div class="panel-body">
					<em class="help-block"><?php echo __d('user', 'Edit the welcome e-mail messages sent to new member accounts created by an administrator.'); ?></em>
					<hr />
					<?php echo $this->Form->input('message_welcome_subject', ['label' => __d('user', 'Subject')]); ?>
					<?php echo $this->Form->input('message_welcome_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading"><?php echo __d('user', 'Account Activation'); ?></div>
				<div class="panel-body">
					<em class="help-block"><?php echo __d('user', 'Enable and edit e-mail messages sent to users upon account activation (when an administrator activates an account of a user who has already registered).'); ?></em>
					<hr />
					<?php
						echo $this->Form->input('message_activation', [
							'type' => 'checkbox',
							'label' => __d('user', 'Notify user when account is activated'),
							'onclick' => 'toggleInputs()',
							'data-toggle' => 'message_activation_inputs',
						]);
					?>
					<div class="message_activation_inputs">
						<?php echo $this->Form->input('message_activation_subject', ['label' => __d('user', 'Subject')]); ?>
						<?php echo $this->Form->input('message_activation_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading"><?php echo __d('user', 'Account Blocked'); ?></div>
				<div class="panel-body">
					<em class="help-block"><?php echo __d('user', 'Enable and edit e-mail messages sent to users when their accounts are blocked.'); ?></em>
					<hr />
					<?php
						echo $this->Form->input('message_blocked', [
							'type' => 'checkbox',
							'label' => __d('user', 'Notify user when account is blocked'),
							'onclick' => 'toggleInputs()',
							'data-toggle' => 'message_blocked_inputs',
						]);
					?>
					<div class="message_blocked_inputs">
						<?php echo $this->Form->input('message_blocked_subject', ['label' => __d('user', 'Subject')]); ?>
						<?php echo $this->Form->input('message_blocked_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading"><?php echo __d('user', 'Password Recovery'); ?></div>
				<div class="panel-body">
					<em class="help-block"><?php echo __d('user', 'Edit the e-mail messages sent to users who request a new password.'); ?></em>
					<hr />
					<?php echo $this->Form->input('message_password_recovery_subject', ['label' => __d('user', 'Subject')]); ?>
					<?php echo $this->Form->input('message_password_recovery_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading"><?php echo __d('user', 'Account Cancellation Confirmation'); ?></div>
				<div class="panel-body">
					<em class="help-block"><?php echo __d('user', 'Edit the e-mail messages sent to users when they attempt to cancel their accounts.'); ?></em>
					<hr />
					<?php echo $this->Form->input('message_cancel_request_subject', ['label' => __d('user', 'Subject')]); ?>
					<?php echo $this->Form->input('message_cancel_request_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading"><?php echo __d('user', 'Account Canceled'); ?></div>
				<div class="panel-body">
					<em class="help-block"><?php echo __d('user', 'Enable and edit e-mail messages sent to users when their accounts are canceled.'); ?></em>
					<hr />
					<?php
						echo $this->Form->input('message_canceled', [
							'type' => 'checkbox',
							'label' => __d('user', 'Notify user when account is canceled'),
							'onclick' => 'toggleInputs()',
							'data-toggle' => 'message_canceled_inputs'
						]);
					?>
					<div class="message_canceled_inputs">
						<?php echo $this->Form->input('message_canceled_subject', ['label' => __d('user', 'Subject')]); ?>
						<?php echo $this->Form->input('message_canceled_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</fieldset>

<script>
	function toggleInputs() {
		$('input[type=checkbox]').each(function () {
			$cb = $(this);
			var className = $cb.data('toggle');
			if ($cb.is(':checked')) {
				$('div.' + className).show();
			} else {
				$('div.' + className).hide();
			}
		});
	}

	$(document).ready(function () {
		toggleInputs();
	});
</script>