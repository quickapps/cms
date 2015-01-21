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

<?php $type = !isset($type) ? 'danger' : $type; ?>
<div class="alert alert-<?php echo $type; ?>">
	<strong><?php echo $message; ?>:</strong>
	<br />
	<ol>
		<?php foreach ($params['errors'] as $error): ?>
		<li><?php echo $error; ?></li>
		<?php endforeach; ?>
	</ol>
</div>