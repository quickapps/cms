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

<?= $this->Flash->render('register'); ?>

<?php if (!$registered): ?>
    <?= $this->Form->create($user, ['id' => 'user-form']); ?>
        <fieldset>
            <legend><?= __d('user', 'Creating New Account'); ?></legend>
            <?= $this->Form->input('name', ['label' => __d('user', 'Real name')]); ?>
            <em class="help-block"><?= __d('user', 'Your real name, e.g. John Locke'); ?></em>

            <?= $this->Form->input('username', ['label' => __d('user', 'User Name')]); ?>
            <em class="help-block"><?= __d('user', 'Alphanumeric characters only and "_" symbol. e.g. My_user_Email'); ?></em>

            <?= $this->Form->input('email', ['label' => __d('user', 'e-Mail')]); ?>
            <em class="help-block"><?= __d('user', 'Must be unique.'); ?></em>

            <?= $this->Form->input('password', ['type' => 'password', 'label' => __d('user', 'Password')]); ?>
            <?= $this->element('User.password_help'); ?>

            <?= $this->Form->input('password2', ['type' => 'password', 'label' => __d('user', 'Confirm Password')]); ?>

            <?= $this->Form->submit(__d('user', 'Register')); ?>
        </fieldset>
    <?= $this->Form->end(); ?>
<?php endif; ?>