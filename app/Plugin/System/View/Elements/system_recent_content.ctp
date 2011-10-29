<table width="100%">
    <?php foreach ($nodes as $node): ?>
    <tr>
        <td align="left" <?php if ($this->Layout->isAdmin()): ?>width="90%"<?php endif; ?>><a href="<?php echo $this->Html->url("/d/{$node['Node']['node_type_id']}/{$node['Node']['slug']}"); ?>"><?php echo $node['Node']['title']; ?></a></td>
        <?php if ($this->Layout->isAdmin()): ?><td align="center"><a href="<?php echo $this->Html->url('/admin/node/contents/edit/' . $node['Node']['slug']); ?>"><?php echo __t('edit'); ?></a></td><?php endif; ?>
    </tr>
    <?php endforeach; ?>
</table>