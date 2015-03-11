<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<fieldset>
    <legend><?php echo __d('system', 'Install New Plugin'); ?></legend>

    <?php echo $this->Form->create(null, ['type' => 'file']); ?>
        <?php
            echo $this->Form->input('file', [
                'type' => 'file',
                'label' => __d('system', 'Upload ZIP package')
            ]);

            echo $this->Form->input('activate', [
                'type' => 'checkbox',
                'label' => __d('system', 'Activate after installation'),
                'id' => 'activate-upload'
            ]);

            echo $this->Form->submit(__d('system', 'Upload package'), ['name' => 'upload']);
        ?>
    <?php echo $this->Form->end(); ?>

    <hr />

    <?php echo $this->Form->create(null); ?>
        <?php
            echo $this->Form->input('url', [
                'label' => __d('system', 'Download ZIP package from URL'),
                'placeholder' => __d('system', 'http://example.com/my-plugin.zip')
            ]);

            echo $this->Form->input('activate', [
                'type' => 'checkbox',
                'label' => __d('system', 'Activate after installation'),
                'id' => 'activate-download',
            ]);

            echo $this->Form->submit(__d('system', 'Install from URL'), ['name' => 'download']);
        ?>
    <?php echo $this->Form->end(); ?>

    <hr />

    <?php echo $this->Form->create(null); ?>
        <?php
            echo $this->Form->input('path', [
                'label' => __d('system', 'Install using a package in a server directory or ZIP file.'),
                'placeholder' => __d('system', '/example/path/to/package.zip')
            ]);

            echo $this->Form->input('activate', [
                'type' => 'checkbox',
                'label' => __d('system', 'Activate after installation'),
                'id' => 'activate-file-system'
            ]);

            echo $this->Form->submit(__d('system', 'Install from File System'), ['name' => 'file_system']);
        ?>
    <?php echo $this->Form->end(); ?>
</fieldset>
