<?php if ($this->Layout->isAdmin()): ?>
<table width="100%">
    <?php foreach ($nodes as $node): ?>
    <tr>
        <td align="left" width="90%"><a href="<?php echo $this->Html->url('/d/' . $node['Node']['slug']); ?>"><?php echo $node['Node']['title']; ?></a></td>
        <td align="center"><a href="<?php echo $this->Html->url('/admin/node/contents/edit/' . $node['Node']['slug']); ?>"><?php echo __t('edit'); ?></a></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <?php foreach ($nodes as $node): ?>
        <?php echo $this->Layout->renderNode($node['Node']['slug'], 'list'); ?>
    <?php endforeach; ?>
<?php endif; ?>