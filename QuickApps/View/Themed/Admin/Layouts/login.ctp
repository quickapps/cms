<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo Configure::read('Variable.language.code'); ?>" version="XHTML+RDFa 1.0" dir="<?php echo Configure::read('Variable.language.direction'); ?>">
	<head>
		<title><?php echo $this->Layout->title(); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<?php echo $this->Layout->meta();?>
		<?php echo $this->Layout->stylesheets();?>
		<?php echo $this->Layout->javascripts(); ?>
		<?php echo $this->Layout->header(); ?>
	</head>

	<body>
		<div id="content" class="clearfix">
			<div class="left-block">
				<a href="http://www.quickappscms.org/" target="_blank"><?php echo $this->Html->image('/system/img/logo.png', array('border' => 0, 'width' => 128)); ?></a>
			</div>

			<div class="right-block">
				<div class="container">
					<div class="sessionFlash">
					<?php if ($sessionFlash = $this->Layout->sessionFlash()): ?>
						<?php echo $sessionFlash; ?>
					<?php endif; ?>
					</div>

					<?php echo $this->Layout->content(); ?>
				</div>
			</div>
		</div>
	</body>
</html>