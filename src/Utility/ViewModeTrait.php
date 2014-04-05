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
namespace QuickApps\Utility;

use QuickApps\Utility\ViewModeRegistry;

/**
 * Provides methods for handling ViewModeRegistry.
 *
 */
trait ViewModeTrait {

/**
 * Sets a view mode.
 *
 * @param string|null $slug Slug name of the view mode
 * @return void
 */
	public function switchViewMode($slug) {
		return ViewModeRegistry::switchViewMode($slug);
	}

/**
 * Gets the slug name of in use view mode.
 *
 * @return string
 */
	public function getViewMode() {
		return ViewModeRegistry::inUse();
	}

}