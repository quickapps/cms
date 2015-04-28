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

$skins = [
    'black' => 'Black',
    'black-light' => 'Black Light',
    'blue' => 'Blue',
    'blue-light' => 'Blue Light',
    'green' => 'Green',
    'green-light' => 'Green Light',
    'purple' => 'Purple',
    'purple-light' => 'Purple Light',
    'red' => 'Red',
    'red-light' => 'Red Light',
    'yellow' => 'Yellow',
    'yellow-light' => 'Yellow Light',
];
?>

<fieldset>
    <legend><?php echo __d('backend_theme', 'Layout Options'); ?></legend>

    <?php echo $this->Form->input('fixed_layout', ['type' => 'checkbox', 'label' => __d('backend_theme', 'Fixed Layout')]); ?>
    <?php echo $this->Form->input('boxed_layout', ['type' => 'checkbox', 'label' => __d('backend_theme', 'Boxed Layout')]); ?>
    <?php echo $this->Form->input('collapsed_sidebar', ['type' => 'checkbox', 'label' => __d('backend_theme', 'Collapsed Sidebar')]); ?>
</fieldset>

<fieldset>
    <legend><?php echo __d('backend_theme', 'Skins'); ?></legend>
    <?php echo $this->Form->input('skin', ['type' => 'select', 'options' => $skins, 'label' => false]); ?>
</fieldset>
