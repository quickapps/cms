<div class="users form">
    <?php echo $this->Form->create('User', array('url' => '/user/login'));?>
        <fieldset>
        <?php
            echo $this->Form->input('username', array('label' => __t('Username')));
            echo $this->Form->input('password', array('label' => __t('Password')));
        ?>
        </fieldset>
    <?php echo $this->Form->end(__t('Login'));?>
    <ul>
        <li><?php echo $this->Html->link(__t('Create new account'), '/user/register'); ?></li>
        <li><?php echo $this->Html->link(__t('Request new password'), '/user/password_recovery'); ?></li>
    </ul>
</div>