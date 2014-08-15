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

/**
 * Provides a basic head for theme's layouts.
 *
 * @see QuickApps\View\Helper\HtmlHelper::head()
 */
?>

<title><?php echo $this->fetch('title'); ?></title>
<?php echo $this->Html->charset(); ?>
<?php echo $this->fetch('meta'); ?>
<?php echo $this->fetch('css'); ?>
<?php echo $this->fetch('script'); ?>
<?php
	$bootstrap = !isset($bootstrap) ? 'css' : $bootstrap;
	$bootstrap = is_string($bootstrap) ? strtolower($bootstrap) : $bootstrap;
	if ($bootstrap !== false) {
		if (
			(is_bool($bootstrap) && $bootstrap === true) ||
			(is_string($bootstrap) && strpos($bootstrap, 'css') !== false)
		) {
			echo $this->Html->css(['System.bootstrap.css', 'System.bootstrap-theme.css']);
		}

		if (
			(is_bool($bootstrap) && $bootstrap === true) ||
			(is_string($bootstrap) && strpos($bootstrap, 'js') !== false)
		) {
			echo $this->Html->script('System.bootstrap.js');
		}
	}
