<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo $this->Html->meta('icon'); ?>
		<?php echo $this->Html->charset(); ?>
		<?php echo $this->fetch('meta'); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $this->fetch('title'); ?></title>
		<?php echo $this->fetch('css'); ?>
		<?php echo $this->fetch('script'); ?>
		<?php echo $this->Html->css('bootstrap.min.css'); ?>
		<?php echo $this->Html->css('bootstrap-theme.min.css'); ?>
		<?php echo $this->Html->script('bootstrap.min.js'); ?>
		<style>
			body { padding-top: 100px; }
			.message article.comment {
				margin-left: 35px;
			}
		</style>
	</head>
	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only"><?php echo __d('back_bootstrap', 'Toggle navigation'); ?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">QuickApps CMS</a>
				</div>
				<div class="collapse navbar-collapse">
					<?php
						echo $this->Menu->render(
							\Cake\ORM\TableRegistry::get('Menu.MenuLinks')
								->find('threaded')
								->where(['menu_slug' => 'main-menu']),
							['class' => 'nav navbar-nav']
						);
					?>
				</div>
			</div>
		</nav>
		<div class="container">
			<?php echo $this->Html->alerts(); ?>
			<?php echo $this->fetch('content'); ?>
			<?php echo $this->element('sql_dump'); ?>
		</div>
	</body>
</html>