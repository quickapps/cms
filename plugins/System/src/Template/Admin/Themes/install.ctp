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
    <legend><?php echo __d('system', 'Install New Theme'); ?></legend>

    <?php echo $this->Form->create(null, ['type' => 'file']); ?>
        <?php echo $this->Form->input('file', ['type' => 'file', 'label' => __d('system', 'Upload ZIP package')]); ?>
        <?php echo $this->Form->submit(__d('system', 'Upload package'), ['name' => 'upload']); ?>
    <?php echo $this->Form->end(); ?>

    <hr />

    <?php echo $this->Form->create(null); ?>
        <?php echo $this->Form->input('url', ['type' => 'text', 'label' => __d('system', 'Download ZIP package from URL')]); ?>
        <?php echo $this->Form->submit(__d('system', 'Install from URL'), ['name' => 'download']); ?>
    <?php echo $this->Form->end(); ?>
</fieldset>
