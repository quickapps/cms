<?php echo $this->Html->useTag('fieldsetstart', __d('comment', 'Anti-Spam Protection')); ?>
    <?php echo $this->Html->useTag('fieldsetstart', 'ReCaptcha'); ?>
        <?php
            echo $this->Form->input('Module.settings.use_recaptcha',
                array(
                    'type' => 'checkbox',
                    'label' => __d('comment', 'Use ReCaptcha'),
                    'onclick' => "if (this.checked) { $('#use_recaptcha').show(); } else { $('#use_recaptcha').hide(); }"
                )
            );
        ?>
        <div id="use_recaptcha" style="<?php echo !Configure::read('Modules.Comment.settings.use_recaptcha') ? 'display:none;' : ''; ?>">
            <?php echo $this->Form->input('Module.settings.recaptcha.public_key', array('type' => 'text', 'label' => __d('comment', 'ReCaptcha Public Key'))); ?>
            <?php echo $this->Form->input('Module.settings.recaptcha.private_key', array('type' => 'text', 'label' => __d('comment', 'ReCaptcha Private Key'))); ?>
            <?php
                echo $this->Form->input('Module.settings.recaptcha.theme',
                    array(
                        'type' => 'select',
                        'label' => __d('comment', 'Theme'),
                        'options' => array(
                            'red' => __d('comment', 'Red'),
                            'white' => __d('comment', 'White'),
                            'blackglass' => __d('comment', 'Black Glass'),
                            'clean' => __d('comment', 'Clean')
                        )
                    )
                );
            ?>
            <?php
                echo $this->Form->input('Module.settings.recaptcha.lang',
                    array(
                        'type' => 'select',
                        'label' => __d('comment', 'Language'),
                        'options' => array(
                            'en' => __d('comment', 'English'),
                            'nl' => __d('comment', 'Dutch'),
                            'fr' => __d('comment', 'French'),
                            'de' => __d('comment', 'German'),
                            'pt' => __d('comment', 'Portuguese'),
                            'ru' => __d('comment', 'Russian'),
                            'es' => __d('comment', 'Spanish'),
                            'tr' => __d('comment', 'Turkish'),
                            'custom' => __d('comment', 'Custom Translation')
                        ),
                        'onchange' => "if (this.value == 'custom') { $('#recaptcha_custom_lang').show(); } else { $('#recaptcha_custom_lang').hide(); } "
                    )
                );
            ?>
            <div id="recaptcha_custom_lang" style="<?php echo Configure::read('Modules.Comment.settings.recaptcha.lang') != 'custom' ? 'display:none;' : ''; ?>">
                <?php echo $this->Html->useTag('fieldsetstart', __d('comment', 'Custom Translation')); ?>
                    <?php echo $this->Form->input('Module.settings.recaptcha.custom_translations.instructions_visual', array('type' => 'text')); ?>
                    <?php echo $this->Form->input('Module.settings.recaptcha.custom_translations.instructions_audio', array('type' => 'text')); ?>
                    <?php echo $this->Form->input('Module.settings.recaptcha.custom_translations.play_again', array('type' => 'text')); ?>
                    <?php echo $this->Form->input('Module.settings.recaptcha.custom_translations.cant_hear_this', array('type' => 'text')); ?>
                    <?php echo $this->Form->input('Module.settings.recaptcha.custom_translations.visual_challenge', array('type' => 'text')); ?>
                    <?php echo $this->Form->input('Module.settings.recaptcha.custom_translations.audio_challenge', array('type' => 'text')); ?>
                    <?php echo $this->Form->input('Module.settings.recaptcha.custom_translations.refresh_btn', array('type' => 'text')); ?>
                    <?php echo $this->Form->input('Module.settings.recaptcha.custom_translations.help_btn', array('type' => 'text')); ?>
                    <?php echo $this->Form->input('Module.settings.recaptcha.custom_translations.incorrect_try_again', array('type' => 'text')); ?>
                <?php echo $this->Html->useTag('fieldsetend'); ?>
            </div>
        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

<!-- -->

    <?php echo $this->Html->useTag('fieldsetstart', 'Akismet'); ?>
        <?php
            echo $this->Form->input('Module.settings.use_akismet',
                array(
                    'type' => 'checkbox',
                    'label' => __d('comment', 'Use Akismet'),
                    'onclick' => "if (this.checked) { $('#use_akismet').show(); } else { $('#use_akismet').hide(); }"
                )
            );
        ?>

        <div id="use_akismet" style="<?php echo !Configure::read('Modules.Comment.settings.use_akismet') ? 'display:none;' : ''; ?>">
            <?php echo $this->Form->input('Module.settings.akismet.key', array('type' => 'text', 'label' => __d('comment', 'Akismet API Key'))); ?>
            <?php
                echo $this->Form->input('Module.settings.akismet.action',
                    array(
                        'type' => 'radio',
                        'legend' => __d('comment', 'On SPAM detected'),
                        'separator' => '<br />',
                        'options' => array(
                            'mark' => __d('comment', 'Mark as SPAM'),
                            'delete' => __d('comment', 'Delete')
                        )
                    )
                );
            ?>
        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Html->useTag('fieldsetend'); ?>