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
		<title><?php echo $this->fetch('title'); ?></title>
	</head>
	<body>
		<?php echo $this->fetch('content'); ?>
		<p>This email was sent using the <a href="http://quickappscms.org">QuickApps CMS</a></p>
	</body>
</html>