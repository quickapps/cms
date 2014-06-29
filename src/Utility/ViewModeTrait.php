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
 * Provides methods for handling switching view mode.
 *
 */
trait ViewModeTrait {

/**
 * Sets a view mode.
 *
 * @param string|null $slug Slug name of the view mode
 * @return void
 * @see QuickApps\Utility\ViewModeRegistry
 */
	public function switchViewMode($slug) {
		return ViewModeRegistry::switchViewMode($slug);
	}

/**
 * Registers a new view mode. Or overwrite if already exists.
 *
 * @param string|array $slug Slug name of your view mode. e.g.: `my-view mode`. Or
 * an array of view modes to register indexed by slug name
 * @param string|null $name Human readable name. e.g.: `My View Mode`
 * @param string|null $description A brief description about for what is this view mode
 * @return void
 * @see QuickApps\Utility\ViewModeRegistry
 */
	public static function registerViewMode($slug, $name = null, $description = null) {
		return ViewModeRegistry::registerViewMode($slug, $name, $description);
	}

/**
 * Gets the slug name of in use view mode.
 *
 * @return string
 * @see QuickApps\Utility\ViewModeRegistry
 */
	public function inUseViewMode() {
		return ViewModeRegistry::inUseViewMode();
	}

/**
 * Gets all registered view modes.
 *
 * @param boolean $full
 * @return array
 * @see QuickApps\Utility\ViewModeRegistry
 */
	public function viewModes($full = false) {
		return ViewModeRegistry::viewModes($full);
	}

}