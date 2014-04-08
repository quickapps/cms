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

use QuickApps\Utility\DetectorRegistry;

/**
 * Adds "is()" detector methods to any object.
 *
 */
trait DetectorTrait {

/**
 * Runs the given detector.
 *
 * Direct callback invocation is up to 30% faster than using call_user_func_array.
 * Optimize the common cases to provide improved performance.
 *
 * @param string $name The detector name. e.g. `user.logged`
 * @return mixed Response from detector method
 * @see QuickApps\Utility\DetectorRegistry::is()
 */
	public function is($name) {
		$args = func_get_args();
		array_shift($args);

		switch (count($args)) {
			case 0:
				return DetectorRegistry::is($name);
			case 1:
				return DetectorRegistry::is($name, $args[0]);
			case 2:
				return DetectorRegistry::is($name, $args[0], $args[1]);
			case 3:
				return DetectorRegistry::is($name, $args[0], $args[1], $args[2]);
			default:
				return call_user_func_array("QuickApps\Utility\DetectorRegistry::is", array_merge([$name], $args));
		}
	}

/**
 * Registers a new checker function.
 *
 * @param string $name Name of the checker. e.g. `theme.core` (checks if in use theme is a core theme)
 * @param object $callable Callable function for handling your checker.
 * @return void
 * @see \QuickApps\Utility\DetectorRegistry::addDetector()
 */
	public function addDetector($name, $callable) {
		return DetectorRegistry::addDetector($name, $callable);
	}

/**
 * Returns a list of all defined detectors.
 *
 * @return array
 * @see \QuickApps\Utility\DetectorRegistry::detectors()
 */
	public function detectors() {
		return DetectorRegistry::detectors();
	}

}
