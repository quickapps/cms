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
 * Default layout for site front page.
 *
 * Usually themes may define a complete different page layout for the `index`
 * page. This layout is used exclusively when rendering site's front page (a.k.a index).
 *
 * @author Christopher Castro <chris@quickapps.es>
 */
?>
<!DOCTYPE html>
<html>
	<head>
		<?php echo $this->element('System.theme_head', ['bootstrap' => 'css,js']); ?>
	</head>
	<body class="front-page">
		<div class="container">
			<?php echo $this->fetch('content'); ?>
		</div>
	</body>
</html>