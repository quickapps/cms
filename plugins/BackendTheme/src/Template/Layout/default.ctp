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
		<?php echo $this->Html->head(['bootstrap' => true]); ?>
		<?php echo $this->Html->css('BackendTheme.back-bootstrap.css'); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	<body>
		<?php if ($this->request->is('userLoggedIn')): ?>
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only"><?php echo __d('backend_theme', 'Toggle navigation'); ?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<?php echo $this->Html->link('QuickApps CMS', '/', ['class' => 'navbar-brand']); ?>
				</div>

				<div class="collapse navbar-collapse">
					<?php echo $this->region('main-menu')->render(); ?>

					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<?php echo $this->Html->image(user()->avatar(['s' => 20])); ?>
								<?php echo user()->username; ?>
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li><?php echo $this->Html->link(__d('backend_theme', 'My account'), ['plugin' => 'User', 'controller' => 'gateway', 'action' => 'me', 'prefix' => false]); ?></li>
								<li><?php echo $this->Html->link(__d('backend_theme', 'Visit website'), '/', ['target' => '_blank']); ?></li>
								<li class="divider"></li>
								<li><?php echo $this->Html->link(__d('backend_theme', 'Sign out'), '/logout'); ?></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<?php endif; ?>

		<div class="container">
			<?php echo $this->Breadcrumb->renderIfNotEmpty(); ?>
			<?php echo $this->Flash->render(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
	</body>
</html>