<div>
	<!-- from file -->
	<?php echo $this->Form->create('Package', array('class' => 'form-inline', 'url' => '/admin/system/themes/install', 'enctype' => 'multipart/form-data')); ?>
		<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Install New Theme') . '</span>'); ?>
			<div class="fieldset-toggle-container" style="display:none;">
				<?php echo $this->Html->useTag('fieldsetstart', __t('Upload Package File')); ?>
					<?php echo $this->Form->input('Package.data',
							array(
								'type' => 'file',
								'label' => __t('Package')
							)
						);
					?>
					<em><?php echo __t('Files must be less than <b>%sB</b>.', ini_get('upload_max_filesize')) ; ?></em>
					<p>
						<?php echo $this->Form->submit(__t('Install')); ?>
					</p>
				<?php echo $this->Html->useTag('fieldsetend'); ?>
			<?php echo $this->Form->end(); ?>

			<!-- from url -->
			<?php echo $this->Form->create('Package', array('url' => '/admin/system/themes/install', 'enctype' => 'multipart/form-data')); ?>
				<?php echo $this->Html->useTag('fieldsetstart', __t('Install From URL')); ?>
					<?php echo $this->Form->input('Package.data',
							array(
								'type' => 'text',
								'label' => __t('Package'),
								'placeholder' => 'http://www.example.com/package.zip'
							)
						);
					?>
					<p>
						<?php echo $this->Form->submit(__t('Install')); ?>
					</p>
				<?php echo $this->Html->useTag('fieldsetend'); ?>
			</div>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Form->end(); ?>

	<p>&nbsp;</p>
</div>

<?php echo $this->Html->useTag('fieldsetstart', __t('Frontend themes')); ?>
	<?php echo $this->element('themes_table_list', array('doAdmin' => false)); ?>
<?php echo $this->Html->useTag('fieldsetend'); ?>

<p>&nbsp;</p>

<?php echo $this->Html->useTag('fieldsetstart', __t('Backend themes')); ?>
	<?php echo $this->element('themes_table_list', array('doAdmin' => true)); ?>
<?php echo $this->Html->useTag('fieldsetend'); ?>