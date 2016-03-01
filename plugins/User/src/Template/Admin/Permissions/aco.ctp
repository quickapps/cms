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
        <div class="well well-sm">
            <p><?= implode($path, ' / '); ?></p>

            <?= $this->Form->create($aco, ['onsubmit' => 'return false;', 'id' => 'permissions-form']); ?>
                <div class="roles-list">
                    <?= $this->Form->input('roles._ids', ['type' => 'select', 'options' => $roles, 'multiple' => true, 'label' => __d('user', 'Role Permissions')]); ?>
                </div>
                <em class="help-block">
                    <?= __d('user', 'Hold <kbd>ctrl</kbd> key when clicking to select multiple roles. Administrators have full access to the entire platform. No restrictions can be applied to them.'); ?>
                </em>

                <a class="btn btn-success has-spinner">
                    <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
                    <?= __d('user', 'Save Permissions'); ?>
                </a>
            <?= $this->Form->end(); ?>
        </div>
    </div>
</div>

