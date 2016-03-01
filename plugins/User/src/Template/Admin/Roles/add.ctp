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

<div class="row">
    <div class="col-md-12">
        <?= $this->element('User.index_submenu'); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?= $this->Form->create($role); ?>
            <fieldset>
                <legend><?= __d('user', 'Register New Role'); ?></legend>
                <?= $this->Form->input('name', ['type' => 'text', 'label' => 'Role Name']); ?>
                <?= $this->Form->submit(__d('user', 'Save')); ?>
            </fieldset>
        <?= $this->Form->end(); ?>
    </div>
</div>
