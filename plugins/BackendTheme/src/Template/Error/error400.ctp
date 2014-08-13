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
?>
<div class="panel panel-danger">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo __d('backend_theme', 'Error'); ?></h3>
	</div>
	<div class="panel-body">
		<p><?php echo $message; ?></p>
		<p><?php echo __d('backend_theme', 'The requested address <strong>"{0}"</strong> was not found on this server.', $url); ?></p>

		<?php if (Configure::read('debug') > 0): ?>
			<p><?php echo $this->element('exception_stack_trace'); ?></p>
		<?php endif; ?>
	</div>
</div>