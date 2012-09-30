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

		<div class="navbar navbar-fixed-top navbar-inverse">
			<div class="navbar-inner">
				<?php echo $this->Block->region('management-menu'); ?>

				<ul class="nav pull-right">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo CakeSession::read('Auth.User.email'); ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
						  <li><?php echo $this->Html->link(__t('My account'), '/admin/user/list/edit/' . CakeSession::read('Auth.User.id')); ?></li>
						  <li><?php echo $this->Html->link(__t('View site'), '/', array('target' => '_blank')); ?></li>
						  <li><?php echo $this->Html->link(__t('Logout'), '/user/logout'); ?></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>

		<p>&nbsp;</p>
		<p>&nbsp;</p>

		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12 well clearfix">
					<?php echo $this->Layout->breadCrumb(); ?>
					<div class="main-title"><h2><?php echo $this->Layout->title();?></h2></div>

					<?php if ($this->Block->regionCount('toolbar')): ?>
					<div class="toolbar">
						<?php echo $this->Block->region('toolbar'); ?>
					</div>
					<?php endif; ?>

					<?php if ($this->Block->regionCount('help')): ?>
					<div class="help">
						<?php echo $this->Block->region('help'); ?>
					</div>
					<?php endif; ?>

					<?php if ($sessionFlash = $this->Layout->sessionFlash()): ?>
					<div id="sessionFlash">
						<?php echo $sessionFlash; ?>
					</div>
					<?php endif; ?>

					<div class="clearfix">
						<?php echo $this->Layout->content(); ?>
					</div>
				</div>
			</div>
		</div>
		
		<div class="span12 site-footer">
			<?php echo $this->Block->region('footer'); ?>
			<br/>
		</div>
		
		<?php echo $this->Layout->footer(); ?>
	</body>
</html>