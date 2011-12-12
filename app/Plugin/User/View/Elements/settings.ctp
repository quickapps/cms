<?php
    $variables = array();
    $_variables = ClassRegistry::init('System.Variable')->find('all',
        array(
            'conditions' => array(
                'Variable.name LIKE' => 'user_mail_%'
            )
        )
    );

    foreach ($_variables as $v) {
        $variables[$v['Variable']['name']] = $v['Variable']['value'];
    }
?>

<?php echo $this->Html->useTag('fieldsetstart', __d('user', 'Mailing notifications')); ?>
<div id="messages">
    <!-- Welcome -->
    <?php echo $this->Html->useTag('fieldsetstart', __d('user', 'Welcome')); ?>
    <div style="display:none;">
        <?php
            # Welcome
            echo $this->Form->input('Variable.user_mail_welcome_subject',
                array(
                    'type' => 'text',
                    'label' => __d('user', 'Subject'),
                    'value' => @$variables['user_mail_welcome_subject']
                )
            );
            echo $this->Form->input('Variable.user_mail_welcome_body',
                array(
                    'type' => 'textarea',
                    'value' => @$variables['user_mail_welcome_body'],
                    'label' => __d('user', 'Body')
                )
            );
        ?>
    </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

    <!-- User activation -->
    <?php echo $this->Html->useTag('fieldsetstart', __d('user', 'User activation')); ?>
    <div style="display:none;">
        <?php
            # Account Activation
            echo $this->Form->input('Variable.user_mail_activation_notify', array('checked' => @$variables['user_mail_activation_notify'], 'label' => __d('user', 'Notify user when account is activated.'), 'type' => 'checkbox'));
            echo $this->Form->input('Variable.user_mail_activation_subject',
                array(
                    'type' => 'text',
                    'label' => __d('user', 'Subject'),
                    'value' => @$variables['user_mail_activation_subject']
                )
            );
            echo $this->Form->input('Variable.user_mail_activation_body',
                array(
                    'type' => 'textarea',
                    'value' => @$variables['user_mail_activation_body'],
                    'label' => __d('user', 'Body')
                )
            );
        ?>
    </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

    <!-- Account blocked -->
    <?php echo $this->Html->useTag('fieldsetstart', __d('user', 'Account blocked')); ?>
    <div style="display:none;">
        <?php
            # Blocking
            echo $this->Form->input('Variable.user_mail_blocked_notify', array('checked' => @$variables['user_mail_blocked_notify'], 'label' => __d('user', 'Notify user when account is blocked.'), 'type' => 'checkbox'));
            echo $this->Form->input('Variable.user_mail_blocked_subject',
                array(
                    'type' => 'text',
                    'value' => @$variables['user_mail_blocked_subject'],
                    'label' => __d('user', 'Body')
                )
            );
            echo $this->Form->input('Variable.user_mail_blocked_body',
                array(
                    'type' => 'textarea',
                    'value' => @$variables['user_mail_blocked_body'],
                    'label' => __d('user', 'Body')
                )
            );
        ?>
    </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

    <!-- Password recovery -->
    <?php echo $this->Html->useTag('fieldsetstart', __d('user', 'Password recovery')); ?>
    <div style="display:none;">
    <?php
        # Pass Recovery
        echo $this->Form->input('Variable.user_mail_password_recovery_subject',
            array(
                'type' => 'text',
                'label' => __d('user', 'Subject'),
                'value' => @$variables['user_mail_password_recovery_subject']
            )
        );
        echo $this->Form->input('Variable.user_mail_password_recovery_body',
            array(
                'type' => 'textarea',
                'value' => @$variables['user_mail_password_recovery_body'],
                'label' => __d('user', 'Body')
            )
        );
    ?>
    </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

    <!-- Account canceled -->
    <?php echo $this->Html->useTag('fieldsetstart', __d('user', 'Account canceled')); ?>
    <div style="display:none;">
    <?php
        # account canceled Recovery
        echo $this->Form->input('Variable.user_mail_canceled_notify', array('checked' => @$variables['user_mail_canceled_notify'], 'label' => __d('user', 'Notify user when account is canceled.'), 'type' => 'checkbox'));
        echo $this->Form->input('Variable.user_mail_canceled_subject',
            array(
                'type' => 'text',
                'label' => __d('user', 'Subject'),
                'value' => @$variables['user_mail_canceled_subject']
            )
        );
        echo $this->Form->input('Variable.user_mail_canceled_body',
            array(
                'type' => 'textarea',
                'value' => @$variables['user_mail_canceled_body'],
                'label' => __d('user', 'Body')
            )
        );
    ?>
    </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
</div>
<?php echo $this->Html->useTag('fieldsetend'); ?>

<script>
    $(document).ready(
        function() {
            $('form fieldset legend').css({'cursor': 'pointer'});
            $('form #messages fieldset legend').click(
                function() {
                    $(this).next().toggle('fast', 'linear');
                }
            );
        }
    );
</script>