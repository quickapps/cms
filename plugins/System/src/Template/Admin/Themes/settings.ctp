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

<?php echo $this->Form->create($theme); ?>
    <?php $this->Form->prefix('settings:'); ?>
    <?php echo $this->element("{$theme->name}.settings"); ?>
    <?php $this->Form->prefix(''); ?>
    <?php echo $this->Form->submit(__d('system', 'Save all')); ?>
<?php echo $this->Form->end(); ?>