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
<html>
	<head>
		<?php echo $this->element('System.theme_header'); ?>
		<?php echo $this->Html->css(['System.bootstrap.css', 'System.bootstrap-theme.css', 'backbootstrap.css']); ?>
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
								->where(['menu_slug' => 'main-menu'])
								->order(['lft' => 'ASC']),
							[
								'class' => 'nav navbar-nav',
								'formatter' => function ($item, $info) {
									$options = [];
									if ($info['hasChildren'] && $info['depth'] === 0) {
										$item->title .= ' <span class="caret"></span>';
									}

									if ($info['depth'] > 0) {
										$options['childAttrs']['class'] = ['dropdown-submenu'];
									}

									return $this->Menu->formatter($item, $info, $options);
								},
							]
						);
					?>
				</div>
			</div>
		</nav>
		<div class="container">
			<?php echo $this->Html->alerts(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
	</body>
</html>