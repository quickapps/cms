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
<!DOCTYPE html>
<html lang="<?php echo language('code'); ?>">
    <head>
        <?php
            echo $this->Html->head([
                'bootstrap' => 'js',
                'append' => [
                    $this->Html->css('/bootstrap/css/bootstrap.min.css'),
                    $this->Html->css('font-awesome.min.css'),
                    $this->Html->css('AdminLTE.min.css'),
                ],
            ]);
        ?>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    </head>

    <body class="login-page">
        <div class="login-box">
            <div class="login-logo">
                <?php echo $this->Html->link('QuickApps<b>CMS</b>', 'http://www.quickappscms.org/', ['escape' => false]); ?>
            </div><!-- /.login-logo -->

            <div class="login-box-body">
                <p class="login-box-msg"><?php echo $this->Flash->render(); ?></p>

                <?php echo $this->Form->create($user); ?>
                    <div class="form-group has-feedback">
                        <?php echo $this->Form->input('username', ['label' => false, 'placeholder' => __d('backend_theme', 'username or email')]); ?>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>

                    <div class="form-group has-feedback">
                        <?php echo $this->Form->input('password', ['label' => false, 'placeholder' => __d('backend_theme', 'password')]); ?>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>

                    <div class="row">
                        <div class="col-xs-8">
                            <div class="checkbox icheck">
                                <?php echo $this->Form->input('remember', ['type' => 'checkbox', 'label' => __d('backend_theme', 'Remember me')]); ?>
                            </div>
                        </div><!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">
                                <?php echo __d('backend_theme', 'Sign In'); ?>
                            </button>
                        </div><!-- /.col -->
                    </div>

                    <small><?php echo $this->Html->link(__d('backend_theme', 'Forgot password?'), '/user/gateway/forgot'); ?></small>
                    <br />
                    <small><?php echo $this->Html->link(__d('backend_theme', 'Resend activation email?'), '/user/gateway/activation_email'); ?></small>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </body>
</html>