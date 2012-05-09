<h1><?php echo __t('Installation'); ?>: <?php echo __t('User Account'); ?></h1>
<p>
	<p><?php echo __t('Please enter the administrative username and password to use when signing into this installation.'); ?></p>
	<form id="theForm" action="" method="post">
		<?php if ($error = $this->Layout->sessionFlash()): ?>
		<div class="content-box content-box-error">
			<?php echo $error; ?>
		</div>
		<?php endif; ?>

		<table cellspacing="0" cellpadding="0" style="width:100%">
			<tr>
				<td width="60%">
					<fieldset>
						<label class="twin-top"><?php echo __t('Real Name'); ?>:</label>
						<label class="sub"><?php echo __t('Your real name.'); ?></label>
						<div class="input-wrap">
							<?php echo $this->Form->text('User.name', array('class' => 'wide')); ?>
						</div>
					</fieldset>
				</td>
			  <td>&nbsp;</td>
		  </tr>
			<tr>
				<td>
					<fieldset>
						<label class="twin-top"><?php echo __t('Username'); ?>:</label>
						<label class="sub"><?php echo __t('The username you will use to login to QuickApps CMS.'); ?></label>
						<div class="input-wrap"><?php echo $this->Form->text('User.username', array('class' => 'wide')); ?></div>
					</fieldset>
				</td>

				<td>
					<fieldset>
						<label class="twin-top"><?php echo __t('Email'); ?>:</label>
						<label class="sub"><?php echo __t('In case you forget your login details.'); ?></label>
						<div class="input-wrap"><?php echo $this->Form->text('User.email', array('class' => 'wide')); ?></div>
					</fieldset>
				</td>
			</tr>

			<tr>
				<td>
					<fieldset>
						<label class="twin-top"><?php echo __t('Password'); ?>:</label>
						<label class="sub"><?php echo __t('The password you will use to login to QuickApps CMS.'); ?></label>
						<div class="input-wrap"><?php echo $this->Form->password('User.password', array('class' => 'wide')); ?></div>
					</fieldset>
				</td>

				<td>
					<fieldset>
						<label class="twin-top"><?php echo __t('Password again'); ?>:</label>
						<label class="sub"><?php echo __t('Confirm your password.'); ?></label>
						<div class="input-wrap"><?php echo $this->Form->password('User.password2', array('class' => 'wide')); ?></div>
					</fieldset>
				</td>
			</tr>
		</table>
		<fieldset>
			<input class="submit" type="submit" value="<?php echo __t('Install QuickApps CMS'); ?>" />
		</fieldset>
	</form>
</p>
