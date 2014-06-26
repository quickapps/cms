<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo $this->Html->charset(); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $this->fetch('title'); ?></title>
		<?php echo $this->Html->css('Installer.bootstrap.min'); ?>
		<?php echo $this->fetch('script') ?>
		<?php echo $this->fetch('css') ?>
		<?php echo $this->fetch('meta') ?>
	</head>
	<body>
		<div class="container">
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<div class="well clearfix">
				<p class="text-center"><?php echo $this->Html->image('Installer.logo.png'); ?></p>
				<?php echo $this->element('Installer.startup_menu', compact($menu)); ?>
				<p><?php echo $this->fetch('content'); ?></p>
			</div>
		</div>
	</body>
</html>