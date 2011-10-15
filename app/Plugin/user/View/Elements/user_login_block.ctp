<div class="users form">
    <?php echo $this->Form->create('User', array('url' => array('plugin' => 'user', 'controller' => 'log', 'action' => 'login')));?>
        <fieldset>
        <?php
            echo $this->Form->input('username');
            echo $this->Form->input('password');
        ?>
        </fieldset>
    <?php echo $this->Form->end('Submit');?>
    <ul>
        <li><?php echo $this->Html->link(__d('user', 'Create new account'), '/user/register' ); ?></li>
        <li><?php echo $this->Html->link(__d('user', 'Request new password'), '/user/password_recovery' ); ?></li>
    </ul>
</div>