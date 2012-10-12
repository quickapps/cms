<h1><?php echo __t('Installation'); ?>: <?php echo __t('Data Base'); ?></h1>
<?php if ($config_exists): ?>
	<p>
		<?php if ($error = $this->Layout->sessionFlash()): ?>
		<div class="content-box content-box-error">
			<?php echo $error; ?>
		</div>
		<?php endif; ?>

		<b><?php echo __t('Configuration exists'); ?></b><br />
		<em><?php echo __t('Your configuration file already exists. If it contains the right values, you may <a href="%s">continue with installation</a>. If you would like to use this part of the installer to recreate the file, please delete it first.', Router::url('/install/database/skip')); ?></em>
	</p>
<?php else: ?>
	<p><em><?php echo __t('Enter connection data for your MySQL database. Note: your database must already exist before completing this step.'); ?></em></p>
	<p>
		<form action="" method="post" >
			<?php if ($error = $this->Layout->sessionFlash()): ?>
			<div class="content-box content-box-error">
				<?php echo $error; ?>
			</div>
			<?php endif; ?>

			<table cellspacing="0" cellpadding="0" style="width:100%">
				<tr>
					<td width="60%">
						<label class="twin-top"><?php echo __t('MySQL server hostname'); ?>:</label>
						<label class="sub"><?php echo __t('ex. mysql.server.com or localhost'); ?></label>
						<div class="input-wrap"><?php echo $this->Form->text('host', array('class' => 'wide', 'value' => 'localhost')); ?></div>
					</td>

					<td>
						<label class="twin-top"><?php echo __t('Database name'); ?>:</label>
						<label class="sub"><?php echo __t('Database must already exist!'); ?></label>
						<div class="input-wrap"><?php echo $this->Form->text('database', array('class' => 'wide')); ?></div>
					</td>
				</tr>

				<tr>
					<td>
						<label class="twin-top"><?php echo __t('Database username'); ?>:</label>
						<label class="sub"><?php echo __t('Username used to log into this database.'); ?></label>
						<div class="input-wrap"><?php echo $this->Form->text('login', array('class' => 'wide')); ?></div>
					</td>

					<td>
						<label class="twin-top"><?php echo __t('Database password'); ?>:</label>
						<label class="sub"><?php echo __t('Password used to log into this database.'); ?></label>
						<div class="input-wrap"><?php echo $this->Form->password('password', array('class' => 'wide')); ?></div>
					</td>
				</tr>

				<tr>
					<td>
						<label class="twin-top"><?php echo __t('Database table prefix'); ?>:</label>
						<label class="sub"><?php echo __t('Only change if "qa_" conflicts with existing tables. Otherwise, leave this alone.'); ?></label>
						<?php echo $this->Form->input('prefix', array('value' => 'qa_')); ?>
					</td>

					<td>&nbsp;</td>
				</tr>
			</table>
			<input type="submit" value="<?php echo __t('Continue'); ?>" class="submit" />
		</form>
	</p>
<?php endif; ?>