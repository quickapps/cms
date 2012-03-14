<?php echo $this->Form->create('User', array('url' => "/user/register/")); ?>
    <!-- Settings -->
    <?php echo $this->Html->useTag('fieldsetstart', __t('Create new account')); ?>
        <?php echo $this->Form->input('name', array('required' => true, 'type' => 'text', 'label' => __t('Real name *'))); ?>
        <em><?php echo __t('Your real name, it is used only for identification purposes. i.e: John Locke'); ?></em>
        <?php echo $this->Form->input('username', array('required' => true, 'type' => 'text', 'label' => __t('User name *'))); ?>
        <em><?php echo __t('Nick used to login. Must be unique and alphanumeric.'); ?></em>
        <?php echo $this->Form->input('avatar', array('type' => 'text', 'label' => __t('Avatar'))); ?>
        <em><?php echo __t('Full url to avatar image file. i.e: http://www.some-domain.com/my-avatar.jpg'); ?></em>
        <?php echo $this->Form->input('email', array('required' => true, 'type' => 'text', 'label' => __t('E-mail *'))); ?>
        <?php echo $this->Form->input('language', array('type' => 'select', 'options' => $languages, 'label' => __t('Language'))); ?>
        <?php App::import('Lib', 'Locale.QALocale'); ?>
        <?php echo $this->Form->input('timezone', array('type' => 'select', 'value' => Configure::read('Variable.date_default_timezone'), 'options' => QALocale::time_zones(), 'label' => __t('Time zone'))); ?>
        <?php echo $this->Form->input('password', array('required' => true, 'type' => 'password', 'label' => __t('Password') . ' *', 'value' => '')); ?>
        <?php echo $this->Form->input('password2', array('required' => true, 'type' => 'password', 'label' => __t('Confirm password') . ' *')); ?>

        <?php foreach ($fields as $field): ?>
            <?php echo $this->Layout->hook("{$field['Field']['field_module']}_edit", $field['Field'], array('collectReturn' => false)); ?>
        <?php endforeach; ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

    <!-- Submit -->
    <?php echo $this->Form->submit(__t('Register')); ?>
<?php echo $this->Form->end(); ?>
