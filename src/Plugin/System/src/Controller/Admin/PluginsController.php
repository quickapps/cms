<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 1.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace System\Controller\Admin;

use System\Controller\SystemAppController;
use QuickApps\Utility\Plugin;

/**
 * Controller for handling plugin tasks.
 *
 * Here is where can install new plugin or remove existing ones.
 */
class PluginsController extends SystemAppController {

/**
 * Main action.
 *
 * @return void
 */
	public function index() {
		$plugins = Plugin::matching(['isTheme' => false], false);
		$this->set('plugins', $plugins);
	}
}
