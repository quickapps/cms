<table width="100%" class="table table-bordered">
	<tbody>
		<?php
		$i = 0;

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
			$currentTheme = $doAdmin ? Configure::read('Variable.admin_theme') : Configure::read('Variable.site_theme');
			$confirmMsg = $doAdmin ? __t('Change administrator theme, are you sure ?\n') : __t('Change site theme, are you sure ?');

			if ($doAdmin && (!isset($data['info']['admin']) || !$data['info']['admin'])) {
				continue;
			}

			if (!$doAdmin && isset($data['info']['admin']) && $data['info']['admin']) {
				continue;
			}
		?>
			<tr class="<?php echo $i%2 ? 'odd' : 'even'; ?>">
				<td width="210">
					<img src=" <?php echo $this->Html->url('/admin/system/themes/theme_tn/' . $name) ?> " class="theme_tn img-rounded" />
				</td>
				<td valign="top">
					<p>
						<b><?php echo $data['info']['name']; ?></b><br/>
						<?php echo QuickApps::is('theme.core', $name) ? __t($data['info']['description']) : __d("Theme{$name}", $data['info']['description']); ?>
					</p>

					<p>
						<b><?php echo __t('version'); ?>:</b> <?php echo $data['info']['version']; ?><br />
						<b><?php echo __t('author'); ?>:</b> <?php echo htmlspecialchars($data['info']['author']); ?>
					</p>

					<p>
						<?php echo __t('<b>regions:</b> <em>%s</em>', implode(', ', array_values($data['regions']))); ?>
					</p>

					<?php if ($currentTheme != $name): ?>
						<a href="<?php echo $this->Html->url('/admin/system/themes/set_theme/' . $name); ?>" style="float:right;" onclick="return confirm('<?php echo $confirmMsg; ?>');"><?php echo __t('Set as default'); ?></a>
						<?php if (!in_array($name, Configure::read('coreThemes'))): ?>
						<a href="<?php echo $this->Html->url('/admin/system/themes/uninstall/' . $name); ?>" style="float:right;" onclick="return confirm('<?php echo __t('Delete selected theme ?\nThis operation cannot be undone!'); ?>');"><?php echo __t('Uninstall'); ?>&nbsp;</a>
						<?php endif; ?>
					<?php else: ?>
						<a href="<?php echo $this->Html->url('/admin/system/themes/settings/' . $name); ?>" style="float:right;"><?php echo __t('Configure'); ?></a>
					<?php endif; ?>
				</td>
			</tr>
		<?php $i++; ?>
		<?php endforeach; ?>
	</tbody>
</table>