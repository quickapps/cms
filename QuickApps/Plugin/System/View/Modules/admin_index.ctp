<?php
	$modules = Configure::read('Modules');
	$categories = array_unique(Hash::extract($modules, '{s}.yaml.category'));
?>

<div>
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Install New Module') . '</span>'); ?>
		<div class="fieldset-toggle-container horizontalLayout" style="display:none;">
			<!-- from file -->
			<?php echo $this->Form->create('Package', array('url' => '/admin/system/modules/install', 'enctype' => 'multipart/form-data')); ?>
				<?php echo $this->Html->useTag('fieldsetstart', __t('Upload Package File')); ?>
					<?php
						echo $this->Form->input('Package.data',
							array(
								'type' => 'file',
								'label' => __t('Package')
							)
						);

						echo $this->Form->input('Package.activate',
							array(
								'type' => 'checkbox',
								'label' => __t('Activate after install')
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
			<?php echo $this->Form->create('Package', array('url' => '/admin/system/modules/install', 'enctype' => 'multipart/form-data')); ?>
				<?php echo $this->Html->useTag('fieldsetstart', __t('Install From URL')); ?>
					<?php
						echo $this->Form->input('Package.data',
							array(
								'type' => 'text',
								'label' => __t('Package')
							)
						);

						echo $this->Form->input('Package.activate',
							array(
								'type' => 'checkbox',
								'label' => __t('Activate after install')
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

<?php foreach ($categories as $category): ?>
<h2><?php echo __t($category); ?></h2>

<table width="100%">
	<?php foreach ($modules as $name => $data): ?>
	<?php if (strpos($name, 'Theme') === 0) continue; ?>
	<?php if (empty($data['yaml']) || $data['yaml']['category'] !== $category) continue; ?>
	<tr id="module-<?php echo $name; ?>" class="<?php echo $data['status'] ? 'module-enabled' : 'module-disabled'; ?>">
		<td width="100%" align="left">
			<b><?php echo $data['yaml']['name']; ?></b> <?php echo $data['yaml']['version']; ?><br />
			<em>
				<?php
					if (!QuickApps::is('module.core', $name)) {
						echo __d(Inflector::underscore($name), $data['yaml']['description']);
					} else {
						echo __t($data['yaml']['description']);
					}
				?>
			</em>
			<br />
			<em><?php echo isset($data['yaml']['author']) ? __t('author: %s', htmlspecialchars($data['yaml']['author'])) : ''; ?></em><br />
			<?php echo isset($data['yaml']['dependencies']) ?  __t('Dependencies') . ': ' . implode(', ', $data['yaml']['dependencies']) : ''; ?>

			<div align="right">
				<a class="help-btn" href="<?php echo $this->Html->url("/admin/user/permissions/?expand=" . $name); ?>"><?php echo __t('Permissions'); ?></a>

				<?php if ($this->Layout->elementExists("{$name}.help")): ?>
				<a class="help-btn" href="<?php echo $this->Html->url("/admin/system/help/module/" . $name); ?>"><?php echo __t('Help'); ?></a>
				<?php endif; ?>

				<?php if ($this->Layout->elementExists("{$name}.settings") && Configure::read('Modules.' . $name)): ?>
				<a class="settings-btn" href="<?php echo $this->Html->url('/admin/system/modules/settings/' . $name); ?>"><?php echo __t('Settings'); ?></a>
				<?php endif; ?>

				<?php if (!in_array(Inflector::camelize($name), Configure::read('coreModules'))) : ?>
				<a class="toggle-btn" href="<?php echo $this->Html->url('/admin/system/modules/toggle/' . $name); ?>"><?php echo $data['status'] == 1 ? __t('Disable') : __t('Enable'); ?></a>
				<a class="delete-btn" href="<?php echo $this->Html->url('/admin/system/modules/uninstall/' . $name); ?>" onclick="return confirm('<?php echo __t("Delete selected module ? This change cannot be undone!"); ?>');"><?php echo __t('Uninstall'); ?></a>
				<?php endif; ?>
			</div>
		</td>
	</tr>
	<?php endforeach; ?>
</table>

<p>&nbsp;</p>

<?php endforeach; ?>
