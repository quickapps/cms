<!-- View overwriting by Admin Theme-->
<?php
	$modules = Configure::read('Modules');
	$categories = array_unique(Hash::extract($modules, '{s}.yaml.category'));
?>

<div>
	<!-- from file -->
	<?php echo $this->Form->create('Package', array('class' => 'form-inline', 'url' => '/admin/system/modules/install', 'enctype' => 'multipart/form-data')); ?>
		<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Install New Module') . '</span>'); ?>	
			<div class="fieldset-toggle-container" style="display:none;">
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
								'id' => 'activate_from_zip',
								'type' => 'checkbox',
								'label' => __t('Activate after install')
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
			<?php echo $this->Form->create('Package', array('url' => '/admin/system/modules/install', 'enctype' => 'multipart/form-data')); ?>
				<?php echo $this->Html->useTag('fieldsetstart', __t('Install From URL')); ?>
					<?php
						echo $this->Form->input('Package.data',
							array(
								'type' => 'text',
								'label' => __t('Package'),
								'placeholder' => 'http://www.example.com/package.zip'
							)
						);

						echo $this->Form->input('Package.activate',
							array(
								'id' => 'activate_from_url',
								'type' => 'checkbox',
								'label' => __t('Activate after install')
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

<?php foreach ($categories as $category): ?>
<h2><?php echo __t($category); ?></h2>

<table width="100%" class="table table-bordered">
	<tbody>
		<?php foreach ($modules as $name => $data): ?>
		<?php if (strpos($name, 'Theme') === 0) continue; ?>
		<?php if (empty($data['yaml']) || $data['yaml']['category'] !== $category) continue; ?>
		<?php $class = $data['status'] ? 'btn-primary' : 'btn-danger'; ?>
		<tr id="module-<?php echo $name; ?>" class="<?php echo $data['status'] ? 'module-enabled' : 'module-disabled'; ?>">
			<td width="100%" align="left">
				<div class="btn-group">
					<a href="<?php echo Router::url("/admin/user/permissions/?expand=" . $name); ?>" class="btn <?php echo $class; ?>">
						<?php echo $data['yaml']['name']; ?> (<?php echo $data['yaml']['version']; ?>)
					</a>
					<button class="btn dropdown-toggle <?php echo $class; ?>" data-toggle="dropdown">
						<span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						<li><a class="help-btn" href="<?php echo $this->Html->url("/admin/user/permissions/?expand=" . $name); ?>"><?php echo __t('Permissions'); ?></a></li>

						<?php if ($this->Layout->elementExists("{$name}.help")): ?>
						<li><a href="<?php echo $this->Html->url("/admin/system/help/module/" . $name); ?>"><?php echo __t('Help'); ?></a></li>
						<?php endif; ?>

						<?php if ($this->Layout->elementExists("{$name}.settings") && Configure::read('Modules.' . $name)): ?>
						<li><a href="<?php echo $this->Html->url('/admin/system/modules/settings/' . $name); ?>"><?php echo __t('Settings'); ?></a></li>
						<?php endif; ?>

						<?php if (!in_array(Inflector::camelize($name), Configure::read('coreModules'))) : ?>
						<li><a href="<?php echo $this->Html->url('/admin/system/modules/toggle/' . $name); ?>"><?php echo $data['status'] == 1 ? __t('Disable') : __t('Enable'); ?></a></li>
						<li><a href="<?php echo $this->Html->url('/admin/system/modules/uninstall/' . $name); ?>" onclick="return confirm('<?php echo __t("Delete selected module ? This change cannot be undone!"); ?>');"><?php echo __t('Uninstall'); ?></a></li>
						<?php endif; ?>
					</ul>
				</div>				

				<em>
					<?php
						if (!QuickApps::is('module.core', $name)) {
							echo __d(Inflector::underscore($name), $data['yaml']['description']);
						} else {
							echo __t($data['yaml']['description']);
						}
					?>
				</em>

				<?php if (isset($data['yaml']['author'])): ?><div><b><?php echo __t('author')?>:</b> <?php echo htmlspecialchars($data['yaml']['author']); ?></div><?php endif; ?>
				<?php if (isset($data['yaml']['dependencies'])): ?><div><b><?php echo __t('Dependencies')?>:</b> <?php echo implode(', ', $data['yaml']['dependencies']); ?></div><?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<p>&nbsp;</p>
<?php endforeach; ?>