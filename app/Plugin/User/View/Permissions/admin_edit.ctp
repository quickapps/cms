<p><?php foreach ($acoPath as $node) { echo ' » ' . $node['Aco']['alias']; } ?></p>

<p>
    <table width="100%">
    <?php foreach ($aros as $roleName => $data): ?>
    <?php $data['allowed'] = $roleName == 'administrator' ? 1 : $data['allowed']; ?>
        <tr>
            <td align="left"><?php echo $roleName; ?></td>
            <td align="right">
                <a href="" id="<?php echo 'permission-' . $acoPath[count($acoPath)-1]['Aco']['id'] . '-' . $data['id']; ?>" onClick="<?php if ($roleName != 'administrator' ): ?> toggle_permission(<?php echo $acoPath[count($acoPath)-1]['Aco']['id']; ?>, <?php echo $data['id']; ?>);<?php endif; ?> return false;">
                    <?php echo $this->Html->image('/user/img/allow-' . $data['allowed'] . '.gif'); ?>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
</p>