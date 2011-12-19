<?php echo $this->Form->create('Package', array('url' => '/admin/locale/packages/install', 'enctype' => 'multipart/form-data', 'onsubmit' => 'return checkPackage();')); ?>
    <!-- Filter -->
    <?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-upload_fieldset" style="cursor:pointer;">' . __t('Upload Translation Package') . '</span>'); ?>
        <div id="upload_fieldset" class="horizontalLayout" style="display:none;">
            <?php echo $this->Form->input('Package.po',
                    array(
                        'type' => 'file',
                        'label' => __t('Package (.po)')
                    )
                );
            ?>

            <?php echo $this->Form->input('Package.module',
                    array(
                        'type' => 'select',
                        'options' => $modules,
                        'label' => __t('App')
                    )
                );
            ?>

            <?php echo $this->Form->input('Package.language',
                    array(
                        'type' => 'select',
                        'options' => $languages,
                        'label' => __t('Language')
                    )
                );
            ?>

            <p>
                <?php echo $this->Form->input(__t('Upload'), array('type' => 'submit', 'label' => false)); ?>
            </p>
        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>

<table width="100%">
    <?php foreach ($packages as $plugin => $langs): ?>
    <?php
        if ($plugin != 'Core' && $plugin != 'Site') {
            $ppath = CakePlugin::path($plugin);

            if (strpos($plugin, 'Theme') !== false) {
                $Name = __t('Theme: %s', Configure::read('Modules.' . $plugin . '.yaml.info.name'));
            } elseif (strpos($ppath, DS . 'Fields' . DS)) {
                $Name = __t('Field: %s', $field_modules[$plugin]['name']);
            } else {
                $Name = __t('Module: %s', Configure::read('Modules.' . $plugin . '.yaml.name'));
            }
        } else {
            if ($plugin == 'Core') {
                $ppath = APP;
                $Name = '<b>' . __t('Core') . '</b>';
            } else {
                $ppath = ROOT . DS;
                $Name = '<b>' . __t('Site Domain') . '</b>';
            }
        }
    ?>
    <tr>
        <td>
            <?php echo $Name; ?><br/>
            <ul>
            <?php foreach ($langs as $code => $po): ?>
                <?php
                    if (!isset($languages[$code])) {
                        continue;
                    }
                ?>
                <li>
                    <?php echo $languages[$code]; ?>
                    <a href="<?php echo $this->Html->url("/admin/locale/packages/download_package/{$plugin}/{$code}"); ?>" target="_blank"><?php echo __t('download'); ?></a>

                    <?php if (strpos($ppath, APP) === false): ?>
                    <a href="<?php echo $this->Html->url("/admin/locale/packages/uninstall/{$plugin}/{$code}"); ?>" onClick="return confirm('<?php echo __t('Delete the selected package ?'); ?>'); "><?php echo __t('uninstall'); ?></a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
            </ul>
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

    $("#toggle-upload_fieldset").click(function () {
        $("#upload_fieldset").toggle('fast', 'linear');
    });
</script>