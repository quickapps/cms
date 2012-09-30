<table width="100%" class="table table-bordered">
	<tbody>
		<?php foreach ($nodes as $node): ?>
		<tr>
			<td align="left" <?php if ($this->Layout->is('user.admin')): ?>width="90%"<?php endif; ?>><a href="<?php echo $this->Html->url("/{$node['Node']['node_type_id']}/{$node['Node']['slug']}.html"); ?>"><?php echo $node['Node']['title']; ?></a></td>
			<?php if ($this->Layout->is('user.admin')): ?><td align="center"><a href="<?php echo $this->Html->url('/admin/node/contents/edit/' . $node['Node']['slug']); ?>"><?php echo __t('edit'); ?></a></td><?php endif; ?>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
