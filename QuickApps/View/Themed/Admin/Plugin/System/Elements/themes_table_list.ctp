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
					<div class="btn-group">
						<?php
							$linkLabel = $data['info']['name'];
							$linkLabel .= $currentTheme == $name ? ' <i class="icon-star" title="' . __t('Theme in use') . '"></i>' : '';
							$linkUrl = $currentTheme == $name ? '/admin/system/themes/settings/' . $name : '';

							echo $this->Html->link($linkLabel, $linkUrl, array('class' => 'btn btn-primary', 'escape' => false, 'onclick' => (!$linkUrl ? 'return false;' : '')));
						?>
						<button class="btn dropdown-toggle btn-primary" data-toggle="dropdown">
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<?php if ($currentTheme != $name): ?>
								<li><a href="<?php echo $this->Html->url('/admin/system/themes/set_theme/' . $name); ?>" onclick="return confirm('<?php echo $confirmMsg; ?>');"><?php echo __t('Set as default'); ?></a></li>
								<?php if (!in_array($name, Configure::read('coreThemes'))): ?>
								<li><a href="<?php echo $this->Html->url('/admin/system/themes/uninstall/' . $name); ?>" onclick="return confirm('<?php echo __t('Delete selected theme ?\nThis operation cannot be undone!'); ?>');"><?php echo __t('Uninstall'); ?></a></li>
								<?php endif; ?>
							<?php else: ?>
								<li><a href="<?php echo $this->Html->url('/admin/system/themes/settings/' . $name); ?>"><?php echo __t('Configure'); ?></a></li>
							<?php endif; ?>
						</ul>
					</div>

					<div><?php echo QuickApps::is('theme.core', $name) ? __t($data['info']['description']) : __d("Theme{$name}", $data['info']['description']); ?></div>

					<hr />

					<div>
						<b><?php echo __t('version'); ?>:</b> <?php echo $data['info']['version']; ?>
						<?php if($data['info']['author']): ?><br /><b><?php echo __t('author'); ?>:</b> <?php echo htmlspecialchars($data['info']['author']); ?><?php endif; ?>
					</div>

					<div>
						<?php echo __t('<b>regions:</b> <em>%s</em>', implode(', ', array_values($data['regions']))); ?>
					</div>
				</td>
			</tr>
		<?php $i++; ?>
		<?php endforeach; ?>
	</tbody>
</table>