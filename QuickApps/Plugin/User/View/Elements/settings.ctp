<?php
	$variables = array();
	$_variables = ClassRegistry::init('System.Variable')->find('all',
		array(
			'conditions' => array(
				'Variable.name LIKE' => 'user_mail_%'
			)
		)
	);

	foreach ($_variables as $v) {
		$variables[$v['Variable']['name']] = $v['Variable']['value'];
	}
?>

<?php echo $this->Html->useTag('fieldsetstart', __t('Mailing notifications')); ?>
	<!-- Welcome -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Welcome') . '</span>'); ?>
	<div class="fieldset-toggle-container" style="display:none;">
		<?php
			echo $this->Form->input('Variable.user_mail_welcome_subject',
				array(
					'type' => 'text',
					'label' => __t('Subject'),
					'value' => @$variables['user_mail_welcome_subject']
				)
			);
			echo $this->Form->input('Variable.user_mail_welcome_body',
				array(
					'type' => 'textarea',
					'value' => @$variables['user_mail_welcome_body'],
					'label' => __t('Body')
				)
			);
		?>
	</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- User activation -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('User activation') . '</span>'); ?>
	<div class="fieldset-toggle-container" style="display:none;">
		<?php
			echo $this->Form->input('Variable.user_mail_activation_notify', array('checked' => @$variables['user_mail_activation_notify'], 'label' => __t('Notify user when account is activated.'), 'type' => 'checkbox'));
			echo $this->Form->input('Variable.user_mail_activation_subject',
				array(
					'type' => 'text',
					'label' => __t('Subject'),
					'value' => @$variables['user_mail_activation_subject']
				)
			);
			echo $this->Form->input('Variable.user_mail_activation_body',
				array(
					'type' => 'textarea',
					'value' => @$variables['user_mail_activation_body'],
					'label' => __t('Body')
				)
			);
		?>
	</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- Account blocked -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Account blocked') . '</span>'); ?>
	<div class="fieldset-toggle-container" style="display:none;">
		<?php
			echo $this->Form->input('Variable.user_mail_blocked_notify', array('checked' => @$variables['user_mail_blocked_notify'], 'label' => __t('Notify user when account is blocked.'), 'type' => 'checkbox'));
			echo $this->Form->input('Variable.user_mail_blocked_subject',
				array(
					'type' => 'text',
					'value' => @$variables['user_mail_blocked_subject'],
					'label' => __t('Body')
				)
			);
			echo $this->Form->input('Variable.user_mail_blocked_body',
				array(
					'type' => 'textarea',
					'value' => @$variables['user_mail_blocked_body'],
					'label' => __t('Body')
				)
			);
		?>
	</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- Password recovery -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Password recovery') . '</span>'); ?>
	<div class="fieldset-toggle-container" style="display:none;">
	<?php
		echo $this->Form->input('Variable.user_mail_password_recovery_subject',
			array(
				'type' => 'text',
				'label' => __t('Subject'),
				'value' => @$variables['user_mail_password_recovery_subject']
			)
		);
		echo $this->Form->input('Variable.user_mail_password_recovery_body',
			array(
				'type' => 'textarea',
				'value' => @$variables['user_mail_password_recovery_body'],
				'label' => __t('Body')
			)
		);
	?>
	</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<!-- Account canceled -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Account canceled') . '</span>'); ?>
	<div class="fieldset-toggle-container" style="display:none;">
	<?php
		echo $this->Form->input('Variable.user_mail_canceled_notify', array('checked' => @$variables['user_mail_canceled_notify'], 'label' => __t('Notify user when account is canceled.'), 'type' => 'checkbox'));
		echo $this->Form->input('Variable.user_mail_canceled_subject',
			array(
				'type' => 'text',
				'label' => __t('Subject'),
				'value' => @$variables['user_mail_canceled_subject']
			)
		);
		echo $this->Form->input('Variable.user_mail_canceled_body',
			array(
				'type' => 'textarea',
				'value' => @$variables['user_mail_canceled_body'],
				'label' => __t('Body')
			)
		);
	?>
	</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Html->useTag('fieldsetend'); ?>