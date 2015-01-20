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
		<?php echo $this->Html->head(); ?>
		<?php echo $this->Html->css(['front-bootstrap.css']); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	</head>

	<body>
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only"><?php echo __d('frontend_theme', 'Toggle navigation'); ?></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<?php echo $this->Html->link('QuickApps CMS', '/', ['class' => 'navbar-brand']); ?>
				</div>
				<div class="collapse navbar-collapse">
					<?php echo $this->region('main-menu')->render(); ?>
				</div>
			</div>
		</nav>

		<div class="container">
			<?php echo $this->Flash->render(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
	</body>
</html>