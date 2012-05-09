<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title><?php echo $this->Layout->title(); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<?php echo $this->Layout->meta();?>
		<?php echo $this->Layout->stylesheets();?>
		<?php echo $this->Layout->javascripts();?>
		<?php echo $this->Layout->header();?>
	</head>

	<body>
		<div id="wrapper">
			<div id="toolbar-menu" class="clearfix" >
				<?php echo $this->Layout->blocks('management-menu'); ?>
				<div id="right-btns">
					<?php echo $this->Html->link(__t('Logout'), '/user/logout'); ?>
					<?php echo $this->Html->link(__t('View site'), '/',  array('target' => '_blank')); ?>
				</div>
			</div>

			<div id="branding" class="clearfix">
				<span class="clearfix"><?php echo $this->Layout->breadCrumb(); ?></span>
				<h1 class="page-title">
					<em><?php echo $this->Layout->title();?></em>
				</h1>
			</div>

			<div id="page">
				<?php if (!$this->Layout->emptyRegion('toolbar')): ?>
				<div class="toolbar">
					<?php echo $this->Layout->blocks('toolbar'); ?>
				</div>
				<?php endif; ?>

				<?php if (!$this->Layout->emptyRegion('help')): ?>
				<div class="help">
					<?php echo $this->Layout->blocks('help'); ?>
				</div>
				<?php endif; ?>

				<?php if ($sessionFlash = $this->Layout->sessionFlash()): ?>
				<div id="sessionFlash">
					<?php echo $sessionFlash; ?>
				</div>
				<?php endif; ?>

				<div id="content" class="clearfix">
					<?php echo $this->Layout->content(); ?>
				</div>
			</div>

			<div id="footer">
				<?php echo $this->Layout->blocks('footer'); ?>
			</div>

		</div>
		<?php echo $this->Layout->footer(); ?>
	</body>
</html>