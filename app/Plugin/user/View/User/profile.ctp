<div class="user-profile">
    <?php echo $this->Html->useTag('fieldsetstart', __d('user', 'Profile')); ?>
        <?php
            if (
                (isset($result['User']['avatar']) && !empty($result['User']['avatar'])) ||
                (!isset($result['User']['avatar']) && Configure::read('Variable.user_default_avatar') != '')
            ):
        ?>

        <div class="avatar">
            <?php $avatar = (isset($result['User']['avatar']) && !empty($result['User']['avatar'])) ? $result['User']['avatar'] : Configure::read('Variable.user_default_avatar') ; ?>
            <?php echo $this->Html->image($avatar, array('width' => '80')); ?>

        </div>
        <?php endif; ?>
        
        <div class="information">
            <div class="input"><h4><?php echo __d('user', 'Username'); ?>:</h4> <?php echo $result['User']['username']; ?></div>
            <div class="input"><h4><?php echo __d('user', 'Real name'); ?>:</h4> <?php echo $result['User']['name']; ?></div>

            <?php if ($result['User']['public_email']): ?>
            <div class="input"><h4><?php echo __d('user', 'Email'); ?>:</h4> <a href="mailto:<?php echo $result['User']['email']; ?>"><?php echo $result['User']['email']; ?></a></div>
            <?php endif; ?>

            <div class="input"><h4><?php echo __d('user', 'User since'); ?>:</h4> <?php echo date(__t('M d, Y'), $result['User']['created']); ?></div>
            <div class="input"><h4><?php echo __d('user', 'Last connection'); ?>:</h4> <?php echo date(__t('M d, Y'), $result['User']['last_login']); ?></div>
            
            <?php foreach ($result['Field'] as $field): ?>
                <div class="input"><?php echo $this->Layout->renderField($field); ?></div>
            <?php endforeach; ?>

        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
</div>