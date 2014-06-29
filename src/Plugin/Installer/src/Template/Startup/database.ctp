<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

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
