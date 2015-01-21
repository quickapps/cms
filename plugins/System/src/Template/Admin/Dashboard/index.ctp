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

/**
 * Backend themes should always implement both "dashboard-main" and
 * "dashboard-sidebar" in order to properly fill this page. 
 */
?>

<div class="row dashboard-container">
	<div class="dashboard-main col-md-8">
		<?php echo $this->region('dashboard-main'); ?>
	</div>

	<div class="dashboard-sidebar col-md-4">
		<?php echo $this->region('dashboard-sidebar'); ?>
	</div>
</div>