<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo Configure::read('Variable.language.code_1'); ?>" lang="<?php echo Configure::read('Variable.language.code_1'); ?>" version="XHTML+RDFa 1.0" dir="<?php echo Configure::read('Variable.language.direction'); ?>">
	<head>
		<title><?php echo $this->Layout->title(); ?></title>
		<?php echo $this->Layout->meta(); ?>
		<?php echo $this->Layout->stylesheets(); ?>
		<?php echo $this->Layout->javascripts(); ?>
		<?php echo $this->Layout->header(); ?>
	</head>

	<body>
		<div class="container">
			<div class="header-top">
				<?php if (Configure::read('Theme.settings.site_logo')): ?>
					<?php echo $this->Html->image(Configure::read('Theme.settings.site_logo_url'), array('url' => '/', 'class' => 'site-logo')); ?>
				<?php endif; ?>

				<?php if ($this->Block->regionCount('user-menu')): ?>
				<div id="user-menu">
					<?php echo $this->Block->region('user-menu'); ?>
				</div>
				<?php endif; ?>

				<?php if ($this->Block->regionCount('language-switcher')): ?>
				<div id="language-switcher">
					<?php echo $this->Block->region('language-switcher'); ?>
				</div>
				<?php endif; ?>

				<?php if ($this->Block->regionCount('search')): ?>
				<div id="search-block">
					<?php echo $this->Block->region('search'); ?>
				</div>
				<?php endif; ?>
			</div>
		 </div>

		<div id="main-menu-wrap">
			<?php echo $this->Block->region('main-menu'); ?>
		</div>

		<div id="page">
			<?php if ($this->Layout->is('view.frontpage')): ?>
				<div class="container">
					<?php if ($this->Block->regionCount('slider')): ?>
					<div class="slider">
						<?php echo $this->Block->region('slider'); ?>
					</div>
					<?php endif; ?>

					<?php if (Configure::read('Theme.settings.site_slogan')): ?>
					<div id="quote">
						<a href="#" onclick="return false;" class="slogan"><?php echo __t(Configure::read('Variable.site_slogan')); ?></a>
					</div> <!-- end #quote -->
					<?php endif; ?>

				
					<div id="services">
						<div class="service">
							<?php echo $this->Block->region('services-left'); ?>
						</div> <!-- end .service -->

						<div class="service">
							<?php echo $this->Block->region('services-center'); ?>
						</div> <!-- end .service -->

						<div class="service last">
							<?php echo $this->Block->region('services-right'); ?>
						</div> <!-- end .service -->
					</div>
				</div>
			<?php else: ?>
				<div class="container">
					<div id="help-blocks">
						<?php echo $this->Block->region('help'); ?>
					</div>

					<?php if ($sessionFlash = $this->Layout->sessionFlash()): ?>
					<div class="session-flash">
						<?php echo $sessionFlash; ?>
					</div>
					<?php endif; ?>
					<?php if ($this->Block->regionCount('sidebar-left')): ?>
						<div id="sidebar-left">
							<?php echo $this->Block->region('sidebar-left'); ?>
						</div>
					<?php endif; ?>

					<div id="content" class="clearfix">
						<?php echo $this->Layout->content(); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<div id="footer">
			<div class="container">
				<?php echo $this->Block->region('footer'); ?>
				<?php
					if ($Layout['feed']) {
						echo $this->Html->link(
							$this->Html->image('feed.png'),
							$Layout['feed'],
							array(
								'class' => 'rss-feed-icon',
								'escape' => false
							)
						);
					}
				?>				
				&nbsp;
			</div>
		</div>

		<?php echo $this->Layout->footer(); ?>
	</body>
</html>