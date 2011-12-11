<?php echo $this->Form->create('Variable', array('url' => '/admin/system/configuration')); ?>
    <!-- Settings -->
    <?php echo $this->Html->useTag('fieldsetstart', __t('Site information')); ?>
        <?php echo $this->Html->useTag('fieldsetstart', __t('Site details')); ?>
            <!--<?php echo $this->Form->input('Variable.site_online', array('type' => 'checkbox', 'label' => __t('Site online'))); ?>
            -->
            <?php echo $this->Form->input('Variable.site_name', array('required' => 'required', 'type' => 'text', 'label' => __t('Site name *'))); ?>

            <?php echo $this->Form->input('Variable.site_slogan', array('type' => 'text', 'label' => __t('Slogan'))); ?>
            <em><?php echo __t("How this is used depends on your site's theme."); ?></em>

            <?php echo $this->Form->input('Variable.site_description', array('type' => 'textarea', 'label' => __t('Description'), 'rows' => 2)); ?>
            <em><?php echo __t("A brief description about your site, this will be used as default meta-description in layout."); ?></em>

            <?php echo $this->Form->input('Variable.site_mail', array('required' => 'required', 'type' => 'email', 'label' => __t('E-mail address *'))); ?>
            <em><?php echo __t("The From address in automated e-mails sent during registration and new password requests, and other notifications. (Use an address ending in your site's domain to help prevent this e-mail being flagged as spam.)"); ?></em>

            <?php echo $this->Form->input('Variable.site_online', array('type' => 'select', 'options' => array(1 => __t('No'), 0 => __t('Yes')), 'label' => __t('Site under maintenance'))); ?>

            <?php echo $this->Form->input('Variable.site_maintenance_message', array('type' => 'textarea', 'label' => __t('Maintenance message'))); ?>
        <?php echo $this->Html->useTag('fieldsetend'); ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

    <?php echo $this->Html->useTag('fieldsetstart', __t('Front page')); ?>
        <?php echo $this->Form->input('Variable.default_nodes_main', array('type' => 'select', 'options' => Set::combine(array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10), '{n}', '{n}'), 'label' => __t('Number of posts on front page'))); ?>
        <em><?php echo __t("The maximum number of posts displayed on overview pages such as the front page."); ?></em>

        <?php echo $this->Form->input('Variable.site_frontpage', array('between' => Router::url('/', true), 'type' => 'text', 'label' => __t('Default front page'))); ?>
        <em><?php echo __t("Optionally, specify a relative URL to display as the front page. Leave blank to display the default content feed"); ?></em>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

    <?php echo $this->Html->useTag('fieldsetstart', __t('Regional settings')); ?>
        <?php App::import('Lib', 'Locale.Locale'); ?>
        <?php echo $this->Form->input('Variable.default_language', array('type' => 'select', 'options' => $languages, 'label' => __t('Default language'))); ?>

        <?php echo $this->Form->input('Variable.date_default_timezone', array('type' => 'select', 'options' => Locale::time_zones(), 'label' => __t('Default time zone'))); ?>

        <?php echo $this->Form->input('Variable.url_language_prefix', array('type' => 'checkbox', 'options' => array(0 => __t('No'), 1 => __t('Yes')), 'label' => __t('URL path prefix'))); ?>
        <em><?php echo __t('URLs like http://domain.com/fre/about set language to French (fre). <b>Warning: Changing this setting may break incoming URLs. Use with caution on a production site.</b>'); ?></em>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

    <?php echo $this->Html->useTag('fieldsetstart', __t('Users settings')); ?>
        <?php echo $this->Form->input('Variable.user_default_avatar', array('type' => 'text', 'label' => __t('Default avatar'))); ?>
        <em><?php echo __t("URL of picture to display for users with no custom picture selected or anonymous users. Leave empty to use <a href='http://www.gravatar.com'>Gravatar</a> based on user's email."); ?></em>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

    <?php
        $moduleSettingsLinks = array();

        foreach (Configure::read('Modules') as $name => $data) {
            $isTheme = strpos($name, 'Theme') === 0;

            if (!$isTheme && file_exists($data['path'] . 'View' . DS . 'Elements' . DS . 'settings.ctp' )) {
                $moduleSettingsLinks[] =
                    "<li>" .
                        $this->Html->link($data['yaml']['name'], '/admin/system/modules/settings/' . $name) .
                        "<p><em>" . __d($name, $data['yaml']['description']) . "</em></p>" .
                    "</li>";
            }
        }

        if (!empty($moduleSettingsLinks)):
    ?>
        <?php echo $this->Html->useTag('fieldsetstart', __t('Other module settings')); ?>
            <ul>
                <?php echo implode("\n",  $moduleSettingsLinks); ?>
            </ul>
        <?php echo $this->Html->useTag('fieldsetend'); ?>
    <?php endif; ?>

    <!-- Submit -->
    <?php echo $this->Form->input(__t('Save all'), array('type' => 'submit')); ?>
<?php echo $this->Form->end(); ?>