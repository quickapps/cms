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
 * ### Usage:
 *
 * In your theme's layout (e.g. `default.ctp`) you have to include this element
 * between `<head>`  & `</head>` tags, for example:
 *
 *     <!DOCTYPE html>
 *     <html>
 *	       <head>
 *             <?php echo $this->element('System.theme_head', ['bootstrap' => true]); ?>
 *             <!-- rest of your head code -->
 *          </head>
 *          <body>
 *              <!-- page content -->
 *          </body>
 *     </html>
 *
 * If you want to automatically include some Twitter Bootstrap's files
 * set `bootstrap` option as follow:
 *
 * - (bool) false: Nothing will be automatically included.
 * - (bool) true: Will include Twitter Bootstrap's CSS & JS files.
 * - (string) "css": Include CSS files only. (By default)
 * - (string) "js": Include JS files only.
 * - (string) combination of "css" and "js": Equivalent to bool true. will
 *   include both, JS and CSS files.
 *   
 *
 * #### Example:
 * 
 *     // no CSS nor JS
 *     <?php echo $this->element('System.theme_head', ['bootstrap' => false]); ?>
 *     
 *     // CSS files only 
 *     <?php echo $this->element('System.theme_head', ['bootstrap' => 'css']); ?>
 *     
 *     // CSS & JS files
 *     <?php echo $this->element('System.theme_head', ['bootstrap' => true]); ?>
 *     
 *     // JS files only
 *     <?php echo $this->element('System.theme_head', ['bootstrap' => 'js']); ?>
 *     
 *     // CSS & JS files, it can be either "css,js" or "js,css"
 *     <?php echo $this->element('System.theme_head', ['bootstrap' => 'css,js']); ?>
 */
?>

<title><?php echo $this->fetch('title'); ?></title>
<?php echo $this->Html->charset(); ?>
<?php echo $this->Html->meta('icon'); ?>
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
?>