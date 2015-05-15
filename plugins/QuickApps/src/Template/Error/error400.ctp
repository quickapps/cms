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
use Cake\Core\Configure;

/**
 * Represents a HTTP 4XX (404, etc) error page.
 *
 * This content will be embed on `Template\Layout\error.ctp` layout.
 * Themes are allowed to define their own `error.ctp` layout.
 *
 * @author Christopher Castro <chris@quickapps.es>
 */
	$this->layout = 'error';
?>

<div class="alert alert-warning">
	<h1>
		<?php echo __('Error'); ?>
		<br />
		<small><?php echo __('The requested address {0} was not found on this server.', "<strong>'{$url}'</strong>"); ?></small>
	</h1>
	<p><?php echo $message; ?></p>
	<?php if (Configure::read('debug')): ?>
		<p>&nbsp;</p>
		<p><?php echo $this->element('exception_stack_trace'); ?></p>
	<?php endif; ?>
</div>