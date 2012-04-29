<div class="user-profile">
    <?php echo $this->Html->useTag('fieldsetstart', __t('Profile')); ?>
        <?php
            echo $this->Html->nestedList(
                array(),
                array('id' => 'user-profile-menu', 'class' => 'username-' . $this->data['User']['username'])
            );
        ?>

        <div class="avatar">
            <?php echo $this->Layout->userAvatar($this->data, array('width' => '80', 'border' => 0)); ?>
        </div>

        <div class="information">
            <div class="input"><h4><?php echo __t('Username'); ?>:</h4> <?php echo $this->data['User']['username']; ?></div>
            <div class="input"><h4><?php echo __t('Real name'); ?>:</h4> <?php echo $this->data['User']['name']; ?></div>

            <?php if ($this->data['User']['public_email']): ?>
            <div class="input">
                <h4><?php echo __t('Email'); ?>:</h4>
                <a href="mailto:<?php echo $this->data['User']['email']; ?>"><?php echo $this->data['User']['email']; ?></a>
            </div>
            <?php endif; ?>

            <div class="input"><h4><?php echo __t('User since'); ?>:</h4> <?php echo date(__t('M d, Y'), $this->data['User']['created']); ?></div>
            <div class="input"><h4><?php echo __t('Last connection'); ?>:</h4> <?php echo date(__t('M d, Y'), $this->data['User']['last_login']); ?></div>
        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
</div>