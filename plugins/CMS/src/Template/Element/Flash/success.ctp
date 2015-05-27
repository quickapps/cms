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

<div class="alert alert-success<?php echo (!empty($dismiss) && $dismiss === true) ? ' alert-dismissible' : ''; ?>" role="alert">
	<?php if (!empty($dismiss) && $dismiss === true): ?>
	<button type="button" class="close" data-dismiss="alert">
		<span aria-hidden="true">&times;</span>
		<span class="sr-only"><?php echo __d('cms', 'Close'); ?></span>
	</button>
	<?php endif; ?>
	<?php echo $message; ?>
</div>