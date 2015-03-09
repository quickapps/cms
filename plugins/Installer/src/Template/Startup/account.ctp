<?php echo $this->Form->create($user, ['class' => 'form-vertical']); ?>
<fieldset>
    <legend><?php echo __d('installer', 'Create Account'); ?></legend>
    <small><em><?php echo __d('installer', 'Complete the following information for create a new user account, you can login later and administer your website   .'); ?></em></small>

    <hr />

    <div class="row">
        <div class="col-md-12">
            <?php echo $this->Flash->render(); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $this->Form->input('name', ['label' => __d('installer', 'Name') . ' *']); ?>
                <em><?php echo __d('installer', 'Your real name, e.g. John Locke.'); ?></em>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('username', ['label' => __d('installer', 'Username') . ' *']); ?>
                <em><?php echo __d('installer', 'The username you will use to login to QuickApps CMS.'); ?></em>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('email', ['label' => __d('installer', 'e-Mail') . ' *']); ?>
                <em><?php echo __d('installer', 'In case you forget your login details.'); ?></em>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <?php echo $this->Form->input('password', ['label' => __d('installer', 'Password') . ' *']); ?>
                <em><?php echo __d('installer', 'The password you will use to login to QuickApps CMS.'); ?></em>
            </div>

            <div class="form-group">
                <?php echo $this->Form->input('password2', ['type' => 'password', 'label' => __d('installer', 'Confirm password')]); ?>
                <em><?php echo __d('installer', 'Confirm your password.'); ?></em>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 col-md-offset-6">
            <p><?php echo $this->Form->submit(__d('installer', 'Create Account')); ?></p>
        </div>
    </div>
</fieldset>
<?php echo $this->Form->end(); ?>