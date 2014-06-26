<?php echo $this->Form->create('Database', ['class' => 'form-vertical']); ?>
<fieldset>
    <legend><?php echo __('Database Connection'); ?></legend>
    <small><em><?php echo __('Enter connection data for your database. Note: your database must already exist before completing this step.'); ?></em></small>
    <hr />
	<?php echo $this->alerts(); ?>
	<div class="col-lg-6">
		<div class="form-group">
			<?php echo $this->Form->input('host', ['label' => __('Host'), 'value' => 'localhost', 'placeholder' => __('ex. localhost')]); ?>
			<p class="help-block"><?php echo __('ex. mysql.server.com or localhost'); ?></p>
		</div>
		<div class="form-group">
			<?php echo $this->Form->input('name', ['label' => __('Database Name'), 'placeholder' => __('ex. quickappscms_db')]); ?>
			<p class="help-block"><?php echo __('Database must already exist!'); ?></p>
		</div>
		<div class="form-group">
			<?php
				echo $this->Form->input('driver',
					[
						'options' => [
							'Mysql' => 'MySQL',
							'Sqlite' => 'SQLite',
							'Postgres' => 'Postgres'
						],
						'value' => 'Mysql',
						'label' => __('Database Type'),
						'class' => 'form-control'
					]
				);
			?>
			<p class="help-block"><?php echo __('The type of database your QuickAppsCMS data will be stored in.'); ?></p>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="form-group">
			<?php echo $this->Form->input('username', ['label' => __('Username')]); ?>
			<p class="help-block"><?php echo __('Username used to log into this database.'); ?></p>
		</div>
		<div class="form-group">
			<?php echo $this->Form->input('password', ['label' => __('Password')]); ?>
			<p class="help-block"><?php echo __('Password used to log into this database.'); ?></p>
		</div>
		<div class="form-group">
			<?php echo $this->Form->input('prefix', ['value' => 'qa_', 'label' => __('Table Prefix')]); ?>
			<p class="help-block"><?php echo __('Only change if "qa_" conflicts with existing tables. Otherwise, leave this alone.'); ?></p>
		</div>
		<p><?php echo $this->Form->submit(__('Continue'), ['class' => 'pull-right']); ?></p>
	</div>
</fieldset>
<?php echo $this->Form->end(); ?>
