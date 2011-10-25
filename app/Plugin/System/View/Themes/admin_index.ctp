<?php echo $this->Form->create('Package', array( 'url' => '/admin/system/themes/install', 'enctype' => 'multipart/form-data', 'onsubmit' => 'return checkPackage();')); ?>
    <!-- Filter -->
    <?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-install_fieldset" style="cursor:pointer;">' . __t('Install New Theme') . '</span>' ); ?>
        <div id="install_fieldset" class="horizontalLayout" style="display:none;">
            <?php echo $this->Form->input('Package.data',
                    array(
                        'type' => 'file',
                        'label' => __t('Package')
                    )
                );
            ?>
            <p>
                <?php echo $this->Form->input(__t('Install'), array('type' => 'submit', 'label' => false)); ?>
            </p>
        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>


<?php echo $this->Html->useTag('fieldsetstart', __t('Frontend themes')  ); ?>
<table width="100%">
    <?php
    foreach ($themes as $name => $data):
        if (strpos($name, 'Admin') !== false )
            continue;
    ?>
        <tr>
            <td width="210">
                <img src=" <?php echo $this->Html->url('/admin/system/themes/theme_tn/' . $name) ?> " border="0" width="202" height="152" style="border:2px solid #666;" />
            </td>
            <td valign="top">
                <p>
                    <b><?php echo $data['info']['name']; ?></b><br/>
                    <?php echo $data['info']['description']; ?>
                </p>
                <p>
                    <?php echo __t('<b>version:</b> %s', $data['info']['version']); ?><br/>
                    <em><?php echo __t('author: %s', htmlspecialchars($data['info']['author'])); ?></em>
                </p>
                <?php if (Configure::read('Variable.site_theme') != $name) : ?>
                    <a href="<?php echo $this->Html->url('/admin/system/themes/set_theme/' . $name); ?>" style="float:right;" onclick="return confirm('<?php echo __t('Change site theme, are you sure ?'); ?>');"><?php echo __t('Set as default'); ?></a>
                    <?php if (!in_array($name, array('Default', 'AdminDefault'))): ?>
                    <a href="<?php echo $this->Html->url('/admin/system/themes/uninstall/' . $name); ?>" style="float:right;" onclick="return confirm('<?php echo __t('Delete selected theme ?\nThis operation can be undone!'); ?>');"><?php echo __t('Uninstall'); ?>&nbsp;</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo $this->Html->url('/admin/system/themes/settings/' . $name ); ?>" style="float:right;"><?php echo __t('Configure'); ?></a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?php echo $this->Html->useTag('fieldsetend'); ?>

<p>&nbsp;</p>

<?php echo $this->Html->useTag('fieldsetstart', __t('Backend themes')  ); ?>
    <table width="100%">
    <?php
    foreach ($themes as $name => $data):
        if (strpos($name, 'Admin') === false )
            continue;
    ?>
        <tr>
            <td width="210">
                <img src=" <?php echo $this->Html->url('/admin/system/themes/theme_tn/' . $name) ?> " border="0" width="202" height="152" style="border:2px solid #666;" />
            </td>
            <td valign="top">
                <p>
                    <b><?php echo $data['info']['name']; ?></b><br/>
                    <?php echo $data['info']['description']; ?>
                </p>
                <p>
                    <?php echo __t('<b>version:</b> %s', $data['info']['version']); ?><br/>
                    <em><?php echo __t('author: %s', htmlspecialchars($data['info']['author'])); ?></em>
                </p>
                <?php if (Configure::read('Variable.admin_theme') != $name) : ?>
                    <a href="<?php echo $this->Html->url('/admin/system/themes/set_theme/' . $name); ?>" style="float:right;" onclick="return confirm('<?php echo __t('Change administrator theme, are you sure ?\n'); ?>');"><?php echo __t('Set as default'); ?> </a>
                <?php else: ?>
                    <a href="<?php echo $this->Html->url('/admin/system/themes/settings/' . $name ); ?>" style="float:right;"><?php echo __t('Configure'); ?></a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </table>
<?php echo $this->Html->useTag('fieldsetend'); ?>

<script>
    function checkPackage() {
        var ext = $('#PackageData').val().substr( ($('#PackageData').val().lastIndexOf('.') +1));
        if (ext != 'app') {
            alert('<?php echo __t('Invalid package'); ?>');
            return false;
        }
        return true;
    }
    $("#toggle-install_fieldset").click(function () {
        $("#install_fieldset").toggle('fast', 'linear');
    });
</script>