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
use Cake\Core\Configure;

/**
 * Represents a HTTP 400 error page.
 *
 * This content will be embed on `Template\Layout\error.ctp` layout.
 *
 * @author Christopher Castro <chris@quickapps.es>
 */
?>

<div class="alert alert-info">
	<h3><?php echo __('Error'); ?></h3>
	<p><?php echo $message; ?></p>
	<p><?php echo __('The requested address {0} was not found on this server.', "<strong>'{$url}'</strong>"); ?></p>
	<?php if (Configure::read('debug') > 0): ?>
		<p>&nbsp;</p>
		<p><?php echo $this->element('exception_stack_trace'); ?></p>
	<?php endif; ?>
</div>