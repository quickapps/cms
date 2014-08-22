<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>
<!DOCTYPE html>
<html lang="<?php echo language('code'); ?>">
	<head>
		<?php echo $this->Html->head(['bootstrap' => true]); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
			body {
				padding-top:100px;
				padding-bottom:40px;
				background-color:#333;
			}
		</style>
	</head>

	<body>
		<div class="container">
			<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
				<div class="panel panel-default" >
					<div class="panel-heading">
						<div class="panel-title">
							<?php echo __d('backend_theme', 'Sign In'); ?>
							<div class="pull-right">
								<small>
									<?php echo $this->Html->link(__d('backend_theme', 'Forgot password?'), ['plugin' => 'User', 'controller' => 'gateway', 'action' => 'forgot']); ?>
								</small>
							</div>
						</div>

					</div>

					<div class="panel-body">
						<?php echo $this->Flash->render(); ?>

						<?php echo $this->Form->create($user); ?>
							<p>
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
									<?php echo $this->Form->input('username', ['label' => false, 'placeholder' => __d('backend_theme', 'username or email')]); ?>
								</div>
							</p>

							<p>
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
									<?php echo $this->Form->input('password', ['label' => false, 'placeholder' => __d('backend_theme', 'password')]); ?>
								</div>
							</p>

							<p>
								<div class="input-group">
									<div class="checkbox">
										<label>
											<input type="checkbox" name="remember" value="1">
											<?php echo __d('backend_theme', 'Remember me'); ?>
										</label>
									</div>
								</div>
							</p>

							<?php echo $this->Form->submit(__d('backend_theme', 'Sign in')); ?>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>