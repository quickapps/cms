<div>
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Install New Theme') . '</span>'); ?>
		<div class="fieldset-toggle-container horizontalLayout" style="display:none;">
			<!-- from file -->
			<?php echo $this->Form->create('Package', array('url' => '/admin/system/themes/install', 'enctype' => 'multipart/form-data')); ?>
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
						<?php echo $this->Form->input(__t('Install'), array('type' => 'submit', 'label' => false)); ?>
					</p>
				<?php echo $this->Html->useTag('fieldsetend'); ?>
			<?php echo $this->Form->end(); ?>

			<!-- from url -->
			<?php echo $this->Form->create('Package', array('url' => '/admin/system/themes/install', 'enctype' => 'multipart/form-data')); ?>
				<?php echo $this->Html->useTag('fieldsetstart', __t('Install From URL')); ?>
					<?php echo $this->Form->input('Package.data',
							array(
								'type' => 'text',
								'label' => __t('Package')
							)
						);
					?>
					<p>
						<?php echo $this->Form->input(__t('Install'), array('type' => 'submit', 'label' => false)); ?>
					</p>
				<?php echo $this->Html->useTag('fieldsetend'); ?>
			<?php echo $this->Form->end(); ?>
		</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<p>&nbsp;</p>
</div>

<?php echo $this->Html->useTag('fieldsetstart', __t('Frontend themes')); ?>
<table width="100%">
	<tbody>
	<?php
	foreach ($themes as $name => $_data):
		$data = array(
			'info' => array(
				'name' => '',
				'description' => '',
				'version' => '--',
				'core' => '',
				'author' => '--',
				'dependencies' => array()
			)
		);

		$data = Hash::merge($data, $_data);

		if (isset($data['info']['admin']) && $data['info']['admin']) {
			continue;
		}
	?>
		<tr>
			<td width="210">
				<img src=" <?php echo $this->Html->url('/admin/system/themes/theme_tn/' . $name) ?> " class="theme_tn" />
			</td>

			<td valign="top">
				<p>
					<b><?php echo $data['info']['name']; ?></b><br/>
					<?php echo QuickApps::is('theme.core', $name) ? __t($data['info']['description']) : __d("Theme{$name}", $data['info']['description']); ?>
				</p>

				<p>
					<?php echo __t('<b>version:</b> %s', $data['info']['version']); ?><br/>
					<em><?php echo __t('author: %s', htmlspecialchars($data['info']['author'])); ?></em>
				</p>

				<?php if (Configure::read('Variable.site_theme') != $name): ?>
					<a href="<?php echo $this->Html->url('/admin/system/themes/set_theme/' . $name); ?>" style="float:right;" onclick="return confirm('<?php echo __t('Change site theme, are you sure ?'); ?>');"><?php echo __t('Set as default'); ?></a>
					<?php if (!in_array($name, Configure::read('coreThemes'))): ?>
					<a href="<?php echo $this->Html->url('/admin/system/themes/uninstall/' . $name); ?>" style="float:right;" onclick="return confirm('<?php echo __t('Delete selected theme ?\nThis operation cannot be undone!'); ?>');"><?php echo __t('Uninstall'); ?>&nbsp;</a>
					<?php endif; ?>
				<?php else: ?>
					<a href="<?php echo $this->Html->url('/admin/system/themes/settings/' . $name); ?>" style="float:right;"><?php echo __t('Configure'); ?></a>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php echo $this->Html->useTag('fieldsetend'); ?>

<p>&nbsp;</p>

<?php echo $this->Html->useTag('fieldsetstart', __t('Backend themes')); ?>
<table width="100%">
	<tbody>
	<?php
	foreach ($themes as $name => $_data):
		$data = array(
			'info' => array(
				'name' => '',
				'description' => '',
				'version' => '--',
				'core' => '',
				'author' => '--',
				'dependencies' => array()
			)
		);

		$data = Hash::merge($data, $_data);

		if (!isset($data['info']['admin']) || !$data['info']['admin']) {
			continue;
		}
	?>
		<tr>
			<td width="210">
				<img src=" <?php echo $this->Html->url('/admin/system/themes/theme_tn/' . $name) ?> " class="theme_tn" />
			</td>
			<td valign="top">
				<p>
					<b><?php echo $data['info']['name']; ?></b><br/>
					<?php echo QuickApps::is('theme.core', $name) ? __t($data['info']['description']) : __d("Theme{$name}", $data['info']['description']); ?>
				</p>

				<p>
					<?php echo __t('<b>version:</b> %s', $data['info']['version']); ?><br/>
					<em><?php echo __t('author: %s', htmlspecialchars($data['info']['author'])); ?></em>
				</p>

				<?php if (Configure::read('Variable.admin_theme') != $name): ?>
					<a href="<?php echo $this->Html->url('/admin/system/themes/set_theme/' . $name); ?>" style="float:right;" onclick="return confirm('<?php echo __t('Change administrator theme, are you sure ?\n'); ?>');"><?php echo __t('Set as default'); ?></a>
					<?php if (!in_array($name, Configure::read('coreThemes'))): ?>
					<a href="<?php echo $this->Html->url('/admin/system/themes/uninstall/' . $name); ?>" style="float:right;" onclick="return confirm('<?php echo __t('Delete selected theme ?\nThis operation cannot be undone!'); ?>');"><?php echo __t('Uninstall'); ?>&nbsp;</a>
					<?php endif; ?>
				<?php else: ?>
					<a href="<?php echo $this->Html->url('/admin/system/themes/settings/' . $name); ?>" style="float:right;"><?php echo __t('Configure'); ?></a>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<?php echo $this->Html->useTag('fieldsetend'); ?>