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

/**
 * DetectorRegistry is used as a registry for detector methods, also provides a few
 * utility methods such as is() or addDetector().
 *
 * Detectors are simple methods aimed to answer `is X?` questions about
 * current request. For example, `is current page site's front?`, to answer this
 * you should use `page.frontpage` or `page.index` detector.
 *
 *     is('page.frontpage');
 *
 * You can define your own detectors methods by using `DetectorRegistry::addDetector` method.
 *
 *     addDetector('theme.core', function ($givenTheme) {
 *         // stuff here
 *     });
 *
 * When defining new detectors you must provide both, a `detector name` (e.g. "theme.core")
 * and a callable function.
 *
 * Callable function may accept an unlimited number of arguments. In the example above, `theme.core`
 * should answer if the **given theme-name** is a core theme or not.
 * So when invoking your detector, you must pass a theme-name as first argument:
 *
 *     // this will ask to your callable function
 *     is('theme.core', 'MyThemeName');
 */
class DetectorRegistry {

/**
 * Built-in detectors.
 *
 * New detectors added using `addDetector()` will be attached
 * to this list.
 *
 * Built-in detectors are:
 *
 * - `user.logged`: is user logged in?
 * - `user.admin`: is user an administrator of the site?
 * - `page.frontpage`: is current request site's front page?
 * - `page.index`: alias for "page.frontpage"
 * - `detector.defined`: is [given] detector defined?
 *
 * @var array
 */
	protected static $_detectors = [
		'user.logged' => '_isUserLogged',
		'user.admin' => '_isUserAdmin',
		'page.frontpage' => '_isPageFrontpage',
		'page.index' => '_isPageFrontpage',
		'detector.defined' => '_isDetectorDefined',
	];

/**
 * General propose cache for built-in detectors.
 *
 * Detectors may to store information in cache, to speed up
 * their performance.
 *
 * @var array
 */
	protected static $_cache = [];

/**
 * Runs the given detector.
 *
 * Direct callback invocation is up to 30% faster than using call_user_func_array.
 * Optimize the common cases to provide improved performance.
 *
 * @param string $name The detector name. e.g. `user.logged`
 * @return mixed Response from detector method
 */
	public static function is($name) {
		if (isset(static::$_detectors[$name])) {
			$callable = static::$_detectors[$name];
			$args = func_get_args();
			array_shift($args);

			if (is_string($callable)) {
				return (new DetectorRegistry())->callBuiltIn($callable, $args);
			} elseif(is_callable($callable)) {
				switch (count($args)) {
					case 0:
						return $callable();
					case 1:
						return $callable($args[0]);
					case 2:
						return $callable($args[0], $args[1]);
					case 3:
						return $callable($args[0], $args[1], $args[2]);
					default:
						return call_user_func_array($callable, $args);
				}
			}
		}
	}

/**
 * Returns a list of all defined detectors.
 *
 * @return array
 */
	public static function detectors() {
		return array_keys(static::$_detectors);
	}

/**
 * Registers a new detector method. Or overwrite if already exists.
 *
 * @param string $name Name of the detector. e.g. `theme.core` (checks if in use theme is a core theme)
 * @param object $callable Callable function for handling your detector.
 * @return void
 */
	public static function addDetector($name, $callable) {
		if (is_callable($callable)) {
			static::$_detectors[$name] = $callable;
		}
	}

/**
 * Calls a built-in detector.
 *
 * Used by `static::is()` to access built-in detectors as
 * in PHP we can not perform direct call invocations of static methods:
 *
 *     // does not work
 *     $callable = "static::someMethod";
 *     return $callable();
 *
 * @param string $detectorName Name of the built-in detector. e.g.: `_isUserLogged`
 * @param array $args Arguments to be passed to detector method as an array list. e.g.: `[$arg1, $arg2, $arg3]`
 * @return mixed Whatever detector is suppose to return
 */
	public function callBuiltIn($detectorName, $args) {
		switch (count($args)) {
			case 0:
				return $this->$detectorName();
			case 1:
				return $this->$detectorName($args[0]);
			case 2:
				return $this->$detectorName($args[0], $args[1]);
			case 3:
				return $this->$detectorName($args[0], $args[1], $args[2]);
			default:
				return call_user_func_array([$this, $detectorName], $args);
		}
	}

/**
 * Checks if visitor user is logged in.
 *
 * @return boolean True if logged in. False other wise
 */
	protected function _isUserLogged() {
		// TODO: DetectorRegistry::_isUserLogged()
		return true;
	}

/**
 * Checks if visitor user is logged in and has administrator privileges.
 *
 * @return boolean True if administrator. False other wise.
 */
	protected function _isUserAdmin() {
		// TODO: DetectorRegistry::_isUserAdmin()
		return true;
	}

/**
 * Checks if the given detector exists.
 *
 * @param string $name Detector name to check
 * @return boolean True if exists. False other wise.
 */
	protected function _isDetectorDefined($name) {
		return !empty(static::$_detectors[$name]);
	}

/**
 * Checks if page being rendered is site's front page.
 *
 * @param boolean $force Set to true to ignore reading from cache
 * @return boolean
 */
	protected function _isPageFrontpage($force = false) {
		if (!empty(static::$_cache['_isPageFrontpage']) && !$force) {
			return static::$_cache['_isPageFrontpage'];
		}

		$request = \Cake\Routing\Router::getRequest();
		$is = (
			!empty($request->params['plugin']) &&
			$request->params['plugin'] === 'node' &&
			!empty($request->params['controller']) &&
			$request->params['controller'] === 'serve' &&
			!empty($request->params['action']) &&
			$request->params['action'] === 'frontpage'
		);

		static::$_cache['_isPageFrontpage'] = $is;
		return $is;
	}

}
