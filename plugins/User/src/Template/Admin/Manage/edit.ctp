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
        <?= $this->Form->create($user, ['id' => 'user-form']); ?>
            <fieldset>
                <legend><?= __d('user', "Editing User's Information"); ?></legend>
                <?= $this->Form->input('name', ['label' => __d('user', 'Real name')]); ?>
                <em class="help-block"><?= __d('user', "User's real name, e.g. John Locke"); ?></em>

                <?= $this->Form->input('username', ['label' => __d('user', 'User Name'), 'disabled']); ?>
                <em class="help-block"><?= __d('user', 'Username cannot be changed, it is used only for identification purposes.'); ?></em>

                <?= $this->Form->input('email', ['label' => __d('user', 'e-Mail')]); ?>
                <em class="help-block"><?= __d('user', 'Must be unique.'); ?></em>

                <?= $this->Form->input('web', ['label' => __d('user', 'Website')]); ?>
                <em class="help-block"><?= __d('user', "User's website, e.g. http://john-locke.about.me"); ?></em>

                <?= $this->Form->input('locale', ['type' => 'select', 'options' => $languages, 'label' => __d('user', 'Preferred Language'), 'empty' => __d('user', 'Default')]); ?>
                <em class="help-block"><?= __d('user', "Preferred user's language"); ?></em>

                <?= $this->Form->input('status', ['type' => 'checkbox', 'label' => __d('user', 'Active')]); ?>
                <em class="help-block"><?= __d('user', 'Inactive users cannot log-in. Administrator users cannot be disabled.'); ?></em>

                <?= $this->Form->input('password', ['type' => 'password', 'label' => __d('user', 'Password'), 'value' => false]); ?>
                <?= $this->element('User.password_help'); ?>

                <?= $this->Form->input('password2', ['type' => 'password', 'label' => __d('user', 'Confirm Password')]); ?>
                <em class="help-block"><?= __d('user', "Leave both fields empty if you do not need to change User's password."); ?></em>

                <?= $this->Form->input('roles._ids', ['type' => 'select', 'options' => $roles, 'label' => __d('user', 'Roles'), 'multiple' => 'checkbox']); ?>
                <?= $this->Form->submit(__d('user', 'Save Changes')); ?>
            </fieldset>

            <?php if (isset($user->_fields) && $user->_fields->count()): ?>
            <fieldset>
                <legend><?= __d('user', 'Additional Information'); ?></legend>
                <?php foreach ($user->_fields as $field): ?>
                    <?= $this->Form->input($field); ?>
                <?php endforeach; ?>
            </fieldset>
            <?php endif; ?>
        <?= $this->Form->end(); ?>
    </div>
</div>