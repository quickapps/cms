<?php if (!isset($preset)): ?>
	<p>
		<?php
			foreach ($acoPath as $node) {
				echo ' » ' . __t($acos_details[$node['Aco']['id']]['name']);
			}
		?>
	</p>

	<em>
		<?php
			$method = end($acoPath);
			echo __t($acos_details[$method['Aco']['id']]['description']);
		?>
	</em>

	<p>
		<table width="100%">
		<?php foreach ($aros as $roleName => $data): ?>
		<?php $data['allowed'] = intval($data['id']) === 1 ? 1 : $data['allowed']; ?>
			<tr>
				<td align="left"><?php echo $roleName; ?></td>
				<td align="right">
					<a href="" id="permission-<?php echo $acoPath[count($acoPath)-1]['Aco']['id'] . '-' . $data['id']; ?>" onClick="<?php if (intval($data['id']) !== 1): ?> toggle_permission(<?php echo $acoPath[count($acoPath)-1]['Aco']['id']; ?>, <?php echo $data['id']; ?>);<?php endif; ?> return false;">
						<i class="icon-<?php echo $data['allowed'] ? 'ok' : 'remove'; ?>"></i>
					</a>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	</p>
<?php else: ?>
	<p>
		<?php
			foreach ($acoPath as $node) {
				echo ' » ' . $node;
			}
		?>
	</p>

	<em><?php echo @__t($preset['description']); ?></em> 

	<p>
		<table width="100%">
		<?php foreach ($aros as $roleName => $data): ?>
		<?php $data['allowed'] = intval($data['id']) === 1 ? 1 : $data['allowed']; ?>
			<tr>
				<td align="left"><?php echo $roleName; ?></td>
				<td align="right">
					<a href="" id="permission-<?php echo str_replace('.', '_', $preset['id']) . '-' . $data['id']; ?>" onClick="<?php if (intval($data['id']) !== 1): ?> toggle_permission('<?php echo $preset['id']; ?>', <?php echo $data['id']; ?>);<?php endif; ?> return false;">
						<i class="icon-<?php echo $data['allowed'] ? 'ok' : 'remove'; ?>"></i>
					</a>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	</p>	
<?php endif; ?>