<?php echo $this->Form->create(false, array('class' => 'form-inline', 'url' => '/admin/locale/packages/install', 'enctype' => 'multipart/form-data', 'onsubmit' => 'return checkPackage();')); ?>
	<!-- Filter -->
	<?php echo $this->Html->useTag('fieldsetstart', '<span class="fieldset-toggle">' . __t('Upload Translation Package') . '</span>'); ?>
		<div class="fieldset-toggle-container" style="display:none;">
			<?php
				echo $this->Form->input('po',
					array(
						'type' => 'file',
						'label' => __t('Package (.po)'),
						'helpBlock' => __t('Select a translation package.')
					)
				);

				echo $this->Form->input('module',
					array(
						'type' => 'select',
						'options' => $modules,
						'label' => __t('Translation for'),
						'helpBlock' => __t('What would you like to translate?')
					)
				);

				echo $this->Form->input('language',
					array(
						'type' => 'select',
						'options' => $languages,
						'label' => __t('Language'),
						'helpBlock' => __t('What is the language of this package? You can register more languages on the <a href="%s">Languages</a> section.', $this->Html->url('/admin/locale/languages'))
					)
				);
			?>

			<p>
				<?php echo $this->Form->submit(__t('Upload')); ?>
			</p>
		</div>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>

<?php if (!empty($packages)): ?>
	<table class="table table-bordered" width="100%">
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
				<h5><?php echo $Name; ?></h5>
				<?php
					$li = array();

					foreach ($langs as $code => $po) {
						if (!isset($languages[$code])) {
							continue;
						}

						$li[] =
							$languages[$code] .
							' ' .
							' ' . $this->Html->link(__t('download'), "/admin/locale/packages/download_package/{$plugin}/{$code}", array('target' => '_blank')) .
							' ' . $this->Html->link(__t('uninstall'), "/admin/locale/packages/uninstall/{$plugin}/{$code}", array('target' => '_blank', 'onclick' => "return confirm('" . __t('Delete the selected package ?') . "');")); 
					}

					echo $this->Html->nestedList($li, array('id' => 'translation-packages-list'));
				?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
<?php endif; ?>

<script type="text/javascript">
	function checkPackage() {
		var ext = $('#po').val().substr(($('#po').val().lastIndexOf('.') +1));

		if (ext != 'po') {
			alert('<?php echo __t('Invalid package'); ?>');
			return false;
		}

		return true;
	}
</script>