<?php echo $this->Form->create(false, array('url' => '/admin/locale/packages/install', 'enctype' => 'multipart/form-data', 'onsubmit' => 'return checkPackage();')); ?>
	<!-- Filter -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Upload Translation Package') . '</span>'); ?>
		<div class="fieldset-toggle-container horizontalLayout" style="display:none;">
			<?php echo $this->Form->input('po',
					array(
						'type' => 'file',
						'label' => __t('Package (.po)')
					)
				);
			?>
			<em><?php echo __t('Select a translation package.'); ?></em>

			<?php echo $this->Form->input('module',
					array(
						'type' => 'select',
						'options' => $modules,
						'label' => __t('Translation for')
					)
				);
			?>
			<em><?php echo __t('What would you like to translate?'); ?></em>

			<?php echo $this->Form->input('language',
					array(
						'type' => 'select',
						'options' => $languages,
						'label' => __t('Language')
					)
				);
			?>
			<em><?php echo __t('What is the language of this package? You can register more languages on the <a href="%s">Languages</a> section.', $this->Html->url('/admin/locale/languages')); ?></em>

			<p>
				<?php echo $this->Form->input(__t('Upload'), array('type' => 'submit', 'label' => false)); ?>
			</p>
		</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>

<table width="100%">
	<?php foreach ($packages as $plugin => $langs): ?>
	<?php
		if ($plugin != 'Default') {
			if (strpos($plugin, 'Theme') !== false) {
				$Name = __t('Theme: %s', Configure::read('Modules.' . $plugin . '.yaml.info.name'));
			} elseif (QuickApps::is('module.field', $plugin)) {
				$Name = __t('Field: %s', $field_modules[$plugin]['name']);
			} else {
				$Name = __t('Module: %s', Configure::read('Modules.' . $plugin . '.yaml.name'));
			}
		} else {
			$Name = '<b>' . __t('ALL') . '</b>';
		}
	?>
	<tr>
		<td>
			<?php echo $Name; ?><br/>
			<?php
				$li = array();

				foreach ($langs as $code => $po) {
					if (!isset($languages[$code])) {
						continue;
					}

					$li[] =
						$languages[$code] . '&nbsp;' .
						'&nbsp;' . $this->Html->link(__t('download'), "/admin/locale/packages/download_package/{$plugin}/{$code}", array('target' => '_blank')) .
						'&nbsp;' . $this->Html->link(__t('uninstall'), "/admin/locale/packages/uninstall/{$plugin}/{$code}", array('target' => '_blank', 'onclick' => "return confirm('" . __t('Delete the selected package ?') . "');")); 
				}

				echo $this->Html->nestedList($li, array('id' => 'translation-packages-list'));
			?>
		</td>
		<td></td>
	</tr>
	<?php endforeach; ?>
</table>

<script>
	function checkPackage() {
		var ext = $('#PackageData').val().substr(($('#PackageData').val().lastIndexOf('.') +1));

		if (ext != 'po') {
			alert('<?php echo __t('Invalid package'); ?>');
			return false;
		}

		return true;
	}
</script>