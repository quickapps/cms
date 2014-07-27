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
<h2><?php echo $message; ?></h2>
<p class="error">
	<strong><?php echo __d('backend_theme', 'Error'); ?>: </strong>
	<?php echo __d('backend_theme', 'An Internal Error Has Occurred.'); ?>
</p>
<?php if (Configure::read('debug') > 0): ?>
	<?php echo $this->element('exception_stack_trace'); ?>
<?php endif; ?>
