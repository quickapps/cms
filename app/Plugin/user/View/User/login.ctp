<div class="users form">
    <h2><?php echo __t('Login'); ?></h2>
    <?php echo $this->Form->create('User', array('url' => '/user/login'));?>
        <fieldset>
        <?php
            echo $this->Form->input('username');
            echo $this->Form->input('password');
        ?>
        </fieldset>
    <?php echo $this->Form->end('Submit');?>
</div>