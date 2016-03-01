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

<?= $this->Form->create($user, ['class' => 'form-vertical']); ?>
<fieldset>
    <legend><?= __d('installer', 'Create Account'); ?></legend>
    <small><em><?= __d('installer', 'Complete the following information for create a new user account, you can login later and administer your website   .'); ?></em></small>

    <hr />

    <div class="row">
        <div class="col-md-12">
            <?= $this->Flash->render(); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?= $this->Form->input('name', ['label' => __d('installer', 'Name') . ' *']); ?>
                <em><?= __d('installer', 'Your real name, e.g. John Locke.'); ?></em>
            </div>

            <div class="form-group">
                <?= $this->Form->input('username', ['label' => __d('installer', 'Username') . ' *']); ?>
                <em><?= __d('installer', 'The username you will use to login to QuickApps CMS.'); ?></em>
            </div>

            <div class="form-group">
                <?= $this->Form->input('email', ['label' => __d('installer', 'e-Mail') . ' *']); ?>
                <em><?= __d('installer', 'In case you forget your login details.'); ?></em>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <?= $this->Form->input('password', ['label' => __d('installer', 'Password') . ' *']); ?>
                <em><?= __d('installer', 'The password you will use to login to QuickApps CMS.'); ?></em>
            </div>

            <div class="form-group">
                <?= $this->Form->input('password2', ['type' => 'password', 'label' => __d('installer', 'Confirm password')]); ?>
                <em><?= __d('installer', 'Confirm your password.'); ?></em>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 col-md-offset-6">
            <p><?= $this->Form->submit(__d('installer', 'Create Account')); ?></p>
        </div>
    </div>
</fieldset>
<?= $this->Form->end(); ?>