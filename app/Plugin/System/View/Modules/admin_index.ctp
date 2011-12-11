<?php
    $modules = Configure::read('Modules');
    $categories = array_unique(Set::extract('{s}.yaml.category', $modules));
?>

<?php echo $this->Form->create('Package', array('url' => '/admin/system/modules/install', 'enctype' => 'multipart/form-data')); ?>
    <!-- Filter -->
    <?php echo $this->Html->useTag('fieldsetstart', '<span id="toggle-install_fieldset" style="cursor:pointer;">' . __t('Install New Module') . '</span>' ); ?>
        <div id="install_fieldset" class="horizontalLayout" style="display:none;">
            <?php
                echo $this->Form->input('Package.data',
                    array(
                        'type' => 'file',
                        'label' => __t('Package')
                    )
                );

                echo $this->Form->input('Package.activate',
                    array(
                        'type' => 'checkbox',
                        'label' => __t('Activate after install')
                    )
                );
            ?>
            <p>
                <?php echo $this->Form->input(__t('Install'), array('type' => 'submit', 'label' => false)); ?>
            </p>
        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>

<?php foreach ($categories as $category): ?>
<h2><?php echo __t($category); ?></h2>
<p>
    <table width="100%">
        <?php foreach ($modules as $name => $data): ?>
        <?php if (strpos($name, 'Theme') === 0) continue; ?>
        <?php if (empty($data['yaml']) || $data['yaml']['category'] !== $category) continue; ?>
        <tr>
            <td width="80%" align="left">
                <b><?php echo $data['yaml']['name']; ?></b> <?php echo $data['yaml']['version']; ?><br/>
                <em><?php echo __d(Inflector::underscore($name), $data['yaml']['description']); ?></em><br/>
                <?php echo isset($data['yaml']['dependencies']) ?  __t('Dependencies') . ': ' . implode(', ', $data['yaml']['dependencies']) : ''; ?>
            </td>

            <td align="right">
                <?php if (file_exists($data['path'] . 'View' . DS . 'Elements' . DS . 'help.ctp')): ?>
                <a href="<?php echo $this->Html->url("/admin/system/help/module/" . $name); ?>"><?php echo __t('Help'); ?></a>
                <?php endif; ?>

                <?php if (file_exists($data['path'] . 'View' . DS . 'Elements' . DS . 'settings.ctp') && Configure::read('Modules.' . $name)): ?>
                <a href="<?php echo $this->Html->url('/admin/system/modules/settings/' . $name); ?>"><?php echo __t('Settings'); ?></a>
                <?php endif; ?>

                <?php if (!in_array(Inflector::camelize($name), Configure::read('coreModules'))) : ?>
                <a href="<?php echo $this->Html->url('/admin/system/modules/toggle/' . $name); ?>"><?php echo $data['status'] == 1 ? __t('Disable') : __t('Enable'); ?></a>
                <a href="<?php echo $this->Html->url('/admin/system/modules/uninstall/' . $name); ?>" onclick="return confirm('<?php echo __t('Delete selected module ? This change cant be undone!'); ?>'); "><?php echo __t('Uninstall'); ?></a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</p>

<p>&nbsp;</p>

<?php endforeach; ?>

<script>
    $("#toggle-install_fieldset").click(function () {
        $("#install_fieldset").toggle('fast', 'linear');
    });
</script>