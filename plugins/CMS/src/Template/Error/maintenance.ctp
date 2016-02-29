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
 * Represents a HTTP 503 error page (server under maintenance).
 *
 * This content will be embed on `Template\Layout\maintenance.ctp` layout.
 * Themes are allowed to define their own `maintenance.ctp` layout.
 *
 * @author Christopher Castro <chris@quickapps.es>
 */
	$this->layout = 'maintenance';
?>

<div class="alert alert-warning">
	<h1><?= __d('cms', 'Site Under Maintenance'); ?></h1>
	<p><?= $this->shortcodes(html_entity_decode($message)); ?></p>
	<?php if (Configure::read('debug')): ?>
		<p>&nbsp;</p>
		<p><?= $this->element('exception_stack_trace'); ?></p>
	<?php endif; ?>
</div>
