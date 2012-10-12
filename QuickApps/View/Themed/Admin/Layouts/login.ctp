<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo Configure::read('Variable.language.code'); ?>" version="XHTML+RDFa 1.0" dir="<?php echo Configure::read('Variable.language.direction'); ?>">
	<head>
		<title><?php echo $this->Layout->title(); ?></title>
		<?php echo $this->Layout->meta();?>
		<?php echo $this->Layout->stylesheets();?>
		<?php echo $this->Layout->javascripts(); ?>
		<?php echo $this->Layout->header(); ?>
	</head>

	<body class="login">		
		<div class="container">
			<div class="content clearfix">
				<?php if ($sessionFlash = $this->Layout->sessionFlash()): ?>
					<div class="clearfix">
						<?php echo $sessionFlash; ?>
					</div>
				<?php endif; ?>

				<div class="clearfix">
					<div class="logo pull-left">
						<a href="http://www.quickappscms.org/" target="_blank"><?php echo $this->Html->image('/system/img/logo.png', array('border' => 0, 'width' => 128)); ?></a>
					</div>

					<div class="login-from pull-right">
						<?php echo $this->Layout->content(); ?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>