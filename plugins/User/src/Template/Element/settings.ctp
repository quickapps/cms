<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<fieldset>
    <legend><?php echo __d('user', 'Login security'); ?></legend>

    <div class="input-group">
        <span class="input-group-addon"><?php echo __d('user', 'After:'); ?></span>
        <?php
            echo $this->Form->input('failed_login_attempts', [
                'type' => 'select',
                'options' => [
                    2 => '2',
                    3 => '3',
                    4 => '4',
                    5 => '5',
                    10 => '10'
                ],
                'empty' => true,
                'label' => false
            ]);
        ?>
        <span class="input-group-addon"><?php echo __d('user', 'failed login attempts, block visitor for: '); ?></span>
        <?php
            echo $this->Form->input('failed_login_attempts_block_seconds', [
                'type' => 'select',
                'options' => [
                    MINUTE => '1',
                    MINUTE * 2 => '2',
                    MINUTE * 3 => '3',
                    MINUTE * 4 => '4',
                    MINUTE * 5 => '5',
                    MINUTE * 10 => '10',
                    MINUTE * 20 => '20',
                    MINUTE * 30 => '30',
                    MINUTE * 40 => '40',
                    MINUTE * 50 => '50',
                    HOUR => '60'
                ],
                'empty' => true,
                'label' => false
            ]);
        ?>
        <span class="input-group-addon"><?php echo __d('user', 'minutes'); ?></span>
    </div>

    <em class="help-block"><?php echo __d('user', 'Leave empty any of the parameters for disable this feature.'); ?></em>
</fieldset>

<p>&nbsp;</p>

<fieldset>
    <legend><?php echo __d('user', 'Password strength'); ?></legend>

    <?php echo $this->Form->input('password_min_length', ['type' => 'text', 'label' => __d('user', 'Minimum password length')]); ?>
    <?php echo $this->Form->input('password_uppercase', ['type' => 'checkbox', 'label' => __d('user', 'Require at least one uppercase letter')]); ?>
    <?php echo $this->Form->input('password_lowercase', ['type' => 'checkbox', 'label' => __d('user', 'Require at least one lowercase letter')]); ?>
    <?php echo $this->Form->input('password_number', ['type' => 'checkbox', 'label' => __d('user', 'Require at least one number')]); ?>
    <?php echo $this->Form->input('password_non_alphanumeric', ['type' => 'checkbox', 'label' => __d('user', 'Require at least one non-alphanumeric character')]); ?>
</fieldset>

<p>&nbsp;</p>

<fieldset>
    <legend><?php echo __d('user', 'Mailing'); ?></legend>

    <div class="alert alert-info">
        <strong><?php echo __d('user', 'Available variables are:'); ?></strong>
        <code>{{user:name}}</code>
        <code>{{user:username}}</code>
        <code>{{user:email}}</code>
        <code>{{user:activation-url}}</code>
        <code>{{user:one-time-login-url}}</code>
        <code>{{user:cancel-url}}</code>
        <code>{{site:name}}</code>
        <code>{{site:url}}</code>
        <code>{{site:description}}</code>
        <code>{{site:slogan}}</code>
        <code>{{site:login-url}}</code>
    </div>

    <hr />

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo __d('user', 'Welcome'); ?></div>
                <div class="panel-body">
                    <em class="help-block"><?php echo __d('user', 'Edit the welcome e-mail messages sent to new member accounts created by an administrator.'); ?></em>
                    <hr />
                    <?php echo $this->Form->input('message_welcome_subject', ['label' => __d('user', 'Subject')]); ?>
                    <?php echo $this->Form->input('message_welcome_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><?php echo __d('user', 'Account Activation'); ?></div>
                <div class="panel-body">
                    <em class="help-block"><?php echo __d('user', 'Enable and edit e-mail messages sent to users upon account activation (when an administrator activates an account of a user who has already registered).'); ?></em>
                    <hr />
                    <?php
                        echo $this->Form->input('message_activation', [
                            'type' => 'checkbox',
                            'label' => __d('user', 'Notify user when account is activated'),
                            'onclick' => 'toggleInputs()',
                            'data-toggle' => 'message_activation_inputs',
                        ]);
                    ?>
                    <div class="message_activation_inputs">
                        <?php echo $this->Form->input('message_activation_subject', ['label' => __d('user', 'Subject')]); ?>
                        <?php echo $this->Form->input('message_activation_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><?php echo __d('user', 'Account Blocked'); ?></div>
                <div class="panel-body">
                    <em class="help-block"><?php echo __d('user', 'Enable and edit e-mail messages sent to users when their accounts are blocked.'); ?></em>
                    <hr />
                    <?php
                        echo $this->Form->input('message_blocked', [
                            'type' => 'checkbox',
                            'label' => __d('user', 'Notify user when account is blocked'),
                            'onclick' => 'toggleInputs()',
                            'data-toggle' => 'message_blocked_inputs',
                        ]);
                    ?>
                    <div class="message_blocked_inputs">
                        <?php echo $this->Form->input('message_blocked_subject', ['label' => __d('user', 'Subject')]); ?>
                        <?php echo $this->Form->input('message_blocked_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo __d('user', 'Password Recovery'); ?></div>
                <div class="panel-body">
                    <em class="help-block"><?php echo __d('user', 'Edit the e-mail messages sent to users who request a new password.'); ?></em>
                    <hr />
                    <?php echo $this->Form->input('message_password_recovery_subject', ['label' => __d('user', 'Subject')]); ?>
                    <?php echo $this->Form->input('message_password_recovery_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><?php echo __d('user', 'Account Cancellation Confirmation'); ?></div>
                <div class="panel-body">
                    <em class="help-block"><?php echo __d('user', 'Edit the e-mail messages sent to users when they attempt to cancel their accounts.'); ?></em>
                    <hr />
                    <?php echo $this->Form->input('message_cancel_request_subject', ['label' => __d('user', 'Subject')]); ?>
                    <?php echo $this->Form->input('message_cancel_request_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><?php echo __d('user', 'Account Canceled'); ?></div>
                <div class="panel-body">
                    <em class="help-block"><?php echo __d('user', 'Enable and edit e-mail messages sent to users when their accounts are canceled.'); ?></em>
                    <hr />
                    <?php
                        echo $this->Form->input('message_canceled', [
                            'type' => 'checkbox',
                            'label' => __d('user', 'Notify user when account is canceled'),
                            'onclick' => 'toggleInputs()',
                            'data-toggle' => 'message_canceled_inputs'
                        ]);
                    ?>
                    <div class="message_canceled_inputs">
                        <?php echo $this->Form->input('message_canceled_subject', ['label' => __d('user', 'Subject')]); ?>
                        <?php echo $this->Form->input('message_canceled_body', ['type' => 'textarea', 'label' => __d('user', 'Body')]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>

<script>
    function toggleInputs() {
        $('input[type=checkbox]').each(function () {
            $cb = $(this);
            var className = $cb.data('toggle');
            if ($cb.is(':checked')) {
                $('div.' + className).show();
            } else {
                $('div.' + className).hide();
            }
        });
    }

    $(document).ready(function () {
        toggleInputs();
    });
</script>