<div class="users form">
    <?php echo $this->Form->create('User', array('url' => '/user/login'));?>
        <fieldset>
        <?php
            echo $this->Form->input('username', array('label' => __d('user', 'Username')));
            echo $this->Form->input('password', array('label' => __d('user', 'Password')));
        ?>
        </fieldset>
    <?php echo $this->Form->end(__d('user', 'Login'));?>
    <ul>
        <li><?php echo $this->Html->link(__d('user', 'Create new account'), '/user/register'); ?></li>
        <li><?php echo $this->Html->link(__d('user', 'Request new password'), '/user/password_recovery'); ?></li>
    </ul>
</div>