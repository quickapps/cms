<?php
/**
 * QuickApps class
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.Lib
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class QuickApps {
/**
 * Holds temporary information generated and used by some methods.
 *
 * @var array
 */
	private static $__tmp = array();

/**
 * Holds a list of registered display modes.
 *
 * @var array
 */
	private static $__displayModes = array(
		'Node.default' => array('label' => 'Default', 'locked' => true),
		'Node.full' => array('label' => 'Full'),
		'Node.list' => array('label' => 'List'),
		'Node.rss' => array('label' => 'RSS'),
		'Node.print' => array('label' => 'Print'),
		'User.default' => array('label' => 'Default', 'locked' => true),
		'User.user_profile' => array('label' => 'User profile')
	);

/**
 * The built in detectors used with `is()`. Can be modified with `addDetector()`.
 *
 * ### Built-in detectors:
 *
 * - is('view.frontpage'): is frontpage ?
 * - is('view.login'): is login screen ?
 * - is('view.admin'): is admin prefix ?
 * - is('view.frontend'): is front site ?
 * - is('view.backend'): same as `view.admin`
 * - is('view.search'): is search results page ?
 * - is('view.feed', 'Optional feed type. e.g: rss'): is actual request a feed result (rss, ajax, xml) ?
 * - is('view.node'): is node details page ?
 * - is('view.user_profile'): is user profile page ?
 * - is('view.my_account'): is user's "my account" form page ?
 * - is('user.logged'): is user logged in?
 * - is('user.admin'): has user admin privileges ?
 * - is('user.authorized', 'AcoPath'): is user allowed to use AcoPath ?
 * - is('theme.core', 'ThemeName'): is `ThemeName` a core theme ?
 * - is('theme.admin', 'ThemeName'): is `ThemeName` a backend theme ?
 * - is('module.core', 'ModuleName'): is `ModuleName` a core module ?
 * - is('module.field', 'ModuleName'): is `ModuleName` a field app ?
 * - is('module.theme', 'ModuleName'): is `ModuleName` a theme app ?
 *
 * @var array
 */
	protected static $_detectors = array(
		'view' => array(
			'frontpage' => array('self', '__viewIsFrontpage'),
			'login' => array('self', '__viewIsLogin'),
			'admin' => array('self', '__viewIsAdmin'),
			'frontend' => array('self', '__viewIsFrontend'),
			'backend' => array('self', '__viewIsBackend'),
			'search' => array('self', '__viewIsSearch'),
			'feed' => array('self', '__viewIsFeed'),
			'rss' => array('self', '__viewIsRss'),
			'node' => array('self', '__viewIsNode'),
			'user_profile' => array('self', '__viewIsUserProfile'),
			'my_account' => array('self', '__viewIsUserMyAccount')
		),
		'user' => array(
			'admin' => array('self', '__userIsAdmin'),
			'logged' => array('self', '__userIsLogged'),
			'authorized' => array('self', '__userIsAuthorized')
		),
		'theme' => array(
			'core' => array('self', '__themeIsCore'),
			'admin' => array('self', '__themeIsAdmin')
		),
		'module' => array(
			'core' => array('self', '__moduleIsCore'),
			'field' => array('self', '__moduleIsField'),
			'theme' => array('self', '__moduleIsTheme')
		)
	);

/**
 * Detector method. Uses the built in detection rules
 * as well as additional rules defined with QuickApps::addDetector()
 * Any detector can be called as `is($detector)` and multiple parameters
 * can be passed to detectors.
 *
 * ### Simple usage
 *
 *     QuickApps::is('view.frontpage');
 *
 * ### Passing multiple parameters
 *
 *     QuickApps::is('group.detector', 'param 1', 'param 2', ...);
 *
 * @param string $detect Dot-Syntax unsersored_detector_name and group name. e.g.: `group.detector_name`
 * @return boolean
 * @see QuickApps::$_detectors
 */
	public static function is($detect) {
		$detect = strtolower($detect);
		list($group, $check) = pluginSplit($detect);

		if (isset(self::$_detectors[$group][$check]) &&
			is_callable(self::$_detectors[$group][$check])
		) {
			$num_parameters = func_num_args() - 1;

			if ($num_parameters) {
				$params = func_get_args();

				array_shift($params);

				return call_user_func_array(self::$_detectors[$group][$check], $params);
			} else {
				return call_user_func(self::$_detectors[$group][$check], null);
			}
		} else {
			return false;
		}
	}

/**
 * Add a new detector to the list of detectors.  
 * All detector callbacks are grouped by category, this allows to group all
 * detectors by module name and avoid collisions between each other.
 *
 * ### Usage
 *
 *     QuickApps::addDetector('my_module.detector_name', array('MyModuleHookHelper', 'detector_handler'));
 *
 * The above will register `detector_name` on `my_module` category.
 * Also, we are using MyModule's Hook Helper class to register the callback method.
 * This last should looks:
 *
 * ### MyModuleHookHelper.php
 *
 *     class MyModuleHookHelper extends AppHelper {
 *         public static function detector_handler() {
 *             return (detector login here);
 *         }
 *     }
 *
 * @param string $detect Dot-Syntax detector name
 * @param array $callback Array with ClassName and Method Name
 * @return void
 */
	public static function addDetector($detect, $callback) {
		$detect = strtolower($detect);
		list($group, $check) = pluginSplit($detect);

		if ($group && $check && is_array($callback)) {
			self::$_detectors[$group][$check] = $callback;
		}
	}

/**
 * Checks if the given detector has been defined.
 *
 * ### Usage
 *
 *     QuickApps::detectorDefined('group_name.detector');
 *
 * @param string $detector Detector name and group in dot syntax
 * @return boolean
 */ 
	public static function detectorDefined($detector) {
		$detector = strtolower($detector);
		list($group, $check) = pluginSplit($detector);

		return ($group && $check && isset(self::$_detectors[$group][$check]));
	}

/**
 * Returns roles IDs to which user belongs to.
 *
 * @return array List of user's roles
 */
	public static function userRoles() {
		$roles = array();

		if (!self::__userIsLogged()) {
			$roles[] = 3;
		} else {
			$roles = CakeSession::read('Auth.User.role_id');
		}

		return $roles;
	}

/**
 * Returns current theme's machine name (CamelCased).
 *
 * @return string Theme name in CamelCase
 */
	public static function themeName() {
		return Configure::read('Theme.info.folder');
	}

/**
 * Parse hooktags attributes.
 *
 * @param string $text Tag string to parse
 * @return array List of attributes
 */
	public static function parseHooktagAttributes($text) {
		$atts = array();
		$pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
		$text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);

		if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
			foreach ($match as $m) {
				if (!empty($m[1])) {
					$atts[strtolower($m[1])] = stripcslashes($m[2]);
				} elseif (!empty($m[3])) {
					$atts[strtolower($m[3])] = stripcslashes($m[4]);
				} elseif (!empty($m[5])) {
					$atts[strtolower($m[5])] = stripcslashes($m[6]);
				} elseif (isset($m[7]) and strlen($m[7])) {
					$atts[] = stripcslashes($m[7]);
				} elseif (isset($m[8])) {
					$atts[] = stripcslashes($m[8]);
				}
			}
		} else {
			$atts = ltrim($text);
		}

		return $atts;
	}

/**
 * Check if a path matches any pattern in a set of patterns.
 *
 * @param string $patterns String containing a set of patterns separated by \n, \r or \r\n
 * @param mixed $path String as path to match. Or boolean FALSE to use actual page url
 * @return boolean TRUE if the path matches a pattern, FALSE otherwise
 */
	public static function urlMatch($patterns, $path = false) {
		if (empty($patterns)) {
			return false;
		}

		$request = self::__getRequestObject();

		$path = !$path ? '/' . $request->url : $path;
		$patterns = explode("\n", $patterns);

		if (Configure::read('Variable.url_language_prefix')) {
			if (!preg_match('/^\/([a-z]{3})\//', $path, $matches)) {
				$path = "/" . Configure::read('Config.language'). $path;
			}
		}

		foreach ($patterns as &$p) {
			$p = Router::url('/') . $p;
			$p = str_replace('//', '/', $p);
			$p = str_replace($request->base, '', $p);
		}

		$patterns = implode("\n", $patterns);

		// Convert path settings to a regular expression.
		// Therefore replace newlines with a logical or, /* with asterisks and the <front> with the frontpage.
		$to_replace = array(
			'/(\r\n?|\n)/', // newlines
			'/\\\\\*/',	 // asterisks
			'/(^|\|)\/($|\|)/' // front '/'
		);

		$replacements = array(
			'|',
			'.*',
			'\1' . preg_quote(Router::url('/'), '/') . '\2'
		);

		$patterns_quoted = preg_quote($patterns, '/');
		$regexps[$patterns] = '/^(' . preg_replace($to_replace, $replacements, $patterns_quoted) . ')$/';

		return (bool) preg_match($regexps[$patterns], $path);
	}

/**
 * This method can do two things:
 *
 * - Retun an array list of all regisreted display-modes for the given Model.
 * - Return information for the specified display-mode (if exists), given as Dot-Syntax (Model.display_mode).
 *
 * ### Usage
 *
 * The example below will returns information for all display modes related to the `Node` model:
 *
 *     QuickApps::displayModes('Node');
 *     // output:
 *     array(
 *         'default' => array('label' => 'Default'),
 *         'full' => array('label' => 'Full'),
 *         'list' => array('label' => 'List'),
 *         'rss' => array('label' => 'RSS'),
 *         'print' => array('label' => 'Print')
 *     );
 *
 * Note: If the specified Model does not exists an empty array will be returned.
 *
 * The example below will return information for the `full` display-mode under the `Node` model:
 *
 *     QuickApps::displayModes('Node.full');
 *     // output:
 *     array('label' => 'Full');
 *
 * Note: If the the given path does not exists FALSE will be returned.
 *
 * @param string $path Model name or Mode.display_mode path
 * @return mixed Array list or FALSE on failure.
 */
	public static function displayModes($path) {
		list($model, $display) = pluginSplit($path);
		$model = Inflector::camelize($model);
		$display = Inflector::underscore($display);

		if ($model && $display) {
			if (isset(self::$__displayModes["{$model}.{$display}"])) {
				return self::$__displayModes["{$model}.{$display}"];
			} else {
				return false;
			}
		} elseif (strpos($path, '.') === false) {
			$model = $path;
			$output = array();

			foreach (self::$__displayModes as $key => $data) {
				list($m, $d) = pluginSplit($key);

				if ($m === $model) {
					$output[$d] = $data;
				}
			}

			return $output;
		}

		return false;
	}

/**
 * Registers a new display-mode under the given Model group (overwrite if already exists).
 * Notes:
 *
 *  - When registering a new display-mode the arguments: `label` is REQUIRED and `options` is OPTIONAL.
 *  - When overwriting an existing display-mode the arguments: `label` is OPTIONAL and `options` is REQUIRED.
 *
 * ### Usage
 *
 *     // register new display-mode under `Node`
 *     QuickApps::registerDisplayMode('Node.new_mode', 'New Mode');
 *
 *     // overwriting the `Full` display-mode (label renaming)
 *     QuickApps::registerDisplayMode('Node.full', 'New Label');
 *
 *     // unlock of `Node.default`
 *     QuickApps::registerDisplayMode('Node.default', null, array('locked' => false));
 *
 * @param string $path Mode.display_mode syntax
 * @param string $label Human-readable name. e.g.: My Display Mode
 * @param array $options Additional options.
 * @param return boolean TRUE on success. FALSE otherwise
 */
	public static function registerDisplayMode($path, $label, $options = array()) {
		list($model, $display) = pluginSplit($path);
		$model = Inflector::camelize($model);
		$display = Inflector::camelize($display);

		if ($model && $display) {
			if (empty($label) && (empty($options) || !is_array($options))) {
				return false;
			}

			if (!empty($label)) {
				self::$__displayModes["{$model}.{$display}"] = array('label' => $label);
			}

			if (is_array($options) && !empty($options)) {
				if (isset($options['label'])) {
					unset($options['label']);
				}

				self::$__displayModes["{$model}.{$display}"] = array_merge(self::$__displayModes["{$model}.{$display}"], $options);
			}

			return true;
		}

		return false;
	}

/**
 * Unregister the given display-mode.
 * Display-modes marked as `locked` can not be removed.
 *
 * @param string $path Model.display_mode syntax
 * @return boolean TRUE on success. FALSE otherwise
 */
	public static function removeDisplayMode($path) {
		list($model, $display) = pluginSplit($path);
		$model = Inflector::camelize($model);
		$display = Inflector::camelize($display);

		if (isset(self::$__displayModes["{$model}.{$display}"])) {
			if (
				!isset(self::$__displayModes["{$model}.{$display}"]['locked']) ||
				!self::$__displayModes["{$model}.{$display}"]['locked']
			) {
				unset(self::$__displayModes[$id]);

				return true;
			}
		}

		return false;
	}

/**
 * Translation function, domain search order:
 *
 * 1.  Current plugin
 * 2.  Default
 * 3.  Translatable entries cache
 *
 * If no translation is found for the given string in any of the
 * domains above, then it gets marked as `fuzzy`.
 * To manage all `fuzzy` entries go to: `/admin/locale/translations/fuzzy_list`
 *
 * @param string $singular String to translate
 * @return string The translated string
 */
	public static function __t($singular = false, $args = null) {
		if (!$singular) {
			return;
		}

		App::uses('I18n', 'I18n');

		$route = class_exists('Router') ? Router::getParams() : null;
		$translation_found = false;
		$language = Configure::read('Config.language');

		if (isset($route['plugin']) && !empty($route['plugin'])) {
			// 1º look in plugin
			$plugin = Inflector::underscore($route['plugin']);
			$translated = I18n::translate($singular, null, $plugin);
			$domains = I18n::domains();
			$translation_found = isset($domains[$plugin][$language]['LC_MESSAGES'][$singular]);
		} else {
			$translated = $singular;
		}

		if ($translated === $singular) {
			// 2º look in default
			$translated = I18n::translate($singular, null, 'default');
			$domains = I18n::domains();
			$translation_found = isset($domains['default'][$language]['LC_MESSAGES'][$singular]);
		}

		if ($translated === $singular) {
			// 3º look in transtalion db-cache
			$cache = Cache::read(md5($singular) . '_' . $language, 'i18n');
			$translated = $cache ? $cache : $singular;
			$translation_found = !empty($cache);
		}

		if (!$translation_found && $language != Configure::read('Variable.default_language')) {
			// translation not found, create fuzzy
			$id = md5($singular);
			$i18n = Cache::config('i18n');

			if (
				Cache::isInitialized('i18n') &&
				!file_exists(CACHE . 'i18n' . DS . "{$i18n['settings']['prefix']}fuzzy_{$id}_{$language}")
			) {
				$backtrace = debug_backtrace();
				$back1 = array_shift($backtrace);
				$back2 = array_shift($backtrace);

				if (!isset($back2['function']) || $back2['function'] != '__t') {
					// direct call of QuickApps::__t()
					$caller = $back1;
				} else {
					// called using __t()
					$caller = $back2;
				}

				array_shift($caller['args']);

				$info['id'] = "{$id}_{$language}";
				$info['file'] = $caller['file'];
				$info['line'] = $caller['line'];
				$info['language'] = Configure::read('Config.language');
				$info['original'] = $singular;
				$info['args'] = $caller['args'];
				$info['hidden'] = 0;

				Cache::write("fuzzy_{$id}_{$language}", $info, 'i18n');
			}
		}

		if ($args === null) {
			return $translated;
		}

		return vsprintf($translated, $args);
	}

/**
 * Return only the methods for the indicated object.  
 * It will strip out inherited methods.
 *
 * @return array List of methods
 */
	public static function get_this_class_methods($class) {
		$methods = array();
		$primary = get_class_methods($class);

		if ($parent = get_parent_class($class)) {
			$secondary = get_class_methods($parent);
			$methods = array_diff($primary, $secondary);
		} else {
			$methods = $primary;
		}

		return $methods;
	}

/**
 * Creates unique arrays using an md5 hash.
 *
 * @param array $array
 * @return array
 */
	public static function array_unique_assoc($array, $preserveKeys = false) {
		$arrayRewrite = array();
		$arrayHashes = array();

		foreach ($array as $key => $item) {
			$hash = md5(serialize($item));

			if (!isset($arrayHashes[$hash])) {
				$arrayHashes[$hash] = $hash;

				if ($preserveKeys) {
					$arrayRewrite[$key] = $item;
				} else {
					$arrayRewrite[] = $item;
				}
			}
		}

		return $arrayRewrite;
	}

/**
 * Strip language prefix from the given internal URL.
 *
 * ### Usage
 *
 *     QuickApps::strip_language_prefix('http://www.example.com/eng/some-url');
 *     // returns: http://www.example.com/some-url
 *
 * The URL must be internal to the site. If not, the original URL be will returned
 * without modifications:
 *
 *     QuickApps::strip_language_prefix('http://www.example.com/eng/external-page.html');
 *     // returns: http://www.example.com/eng/external-page.html
 *
 * @param string $url Internal FULL-URL where to replace language prefix
 * @return string URL with no language prefix
 */
	public static function strip_language_prefix($url) {
		$request = self::__getRequestObject();
		$base = env('HTTP_HOST') . $request->base . '/';

		if (strpos($url, $base) !== false) {
			$url = str_replace($base, '', $url);
		} else {
			// external url: do not process
			return $url;
		}

		$protocol = env('HTTPS') ? 'https://' : 'http://';
		$url = str_replace($protocol, '', $url);
		$url = preg_replace('/\/{2,}/', '/', '/' . $url);
		$url = preg_replace('/^\/[a-z]{3}\//', '/', $url);
		$url = $base . $url;
		$url = preg_replace('/\/{2,}/', '/', $url);

		return $protocol . $url;
	}

/**
 * Replace the first ocurrence only.
 *
 * @param string $str_pattern What to find for
 * @param string $str_replacement The replacement for $str_pattern
 * @param string $string The original to find and replace
 * @return string
 */
	public static function str_replace_once($str_pattern, $str_replacement, $string) {
		if (strpos($string, $str_pattern) !== false) {
			$occurrence = strpos($string, $str_pattern);

			return substr_replace($string, $str_replacement, strpos($string, $str_pattern), strlen($str_pattern));
		}

		return $string;
	}

/**
 * Return an associative array with field(s) information.
 *
 * ### Usage
 *
 *     QuickApps::field_info('FieldText');
 *
 * The above will return an array of all information for `FieldText`.
 * You can use both formats CamelCase or under_scored.  
 * e.g.: `FieldText` or `field_text` are valid inputs.
 *
 *     QuickApps::field();
 *
 * This will return a list of fields and its information.
 *
 * @param mixed $field
 *	Field name as string will return information for these field only,
 *	FALSE will return all fields information. (default false)
 * @return array Associative array index by Field name. Empty array if Field does not exists
 */
	public static function field_info($field = false) {
		if (!isset(self::$__tmp['field_modules'])) {
			$plugins = App::objects('plugins');

			foreach ($plugins as $plugin) {
				$ppath = App::pluginPath($plugin);

				if (self::is('module.field', $plugin)) {
					$yaml = Spyc::YAMLLoad($ppath . "{$plugin}.yaml");
					$yaml['path'] = $ppath;
					$field_modules[$plugin] = $yaml;
				}
			}

			self::$__tmp['field_modules'] = $field_modules;
		}

		if (!$field) {
			return self::$__tmp['field_modules'];
		} else {
			$field = Inflector::camelize((string)$field);

			if (isset(self::$__tmp['field_modules'][$field])) {
				return array(
					$field => self::$__tmp['field_modules'][$field]
				);
			} else {
				return array();
			}
		}
	}

/**
 * Check if the given module name belongs to QuickApps CMS core.
 *
 * @param string $module Module name to check
 * @return boolean TRUE if module is a core module, FALSE otherwise
 */
	private static function __moduleIsCore($module) {
		$module = Inflector::camelize($module);

		if (CakePlugin::loaded($module)) {
			$path = CakePlugin::path($module);

			if (strpos($path, APP . 'Plugin' . DS) !== false) {
				return true;
			}
		}

		return false;
	}

/**
 * Get CakeRequest instance.
 *
 * return CakeRequest
 */
	private static function __getRequestObject() {
		$request = Configure::read('CakeRequest');

		if (!($request instanceof CakeRequest)) {
			$request = new CakeRequest();
		}

		return $request;
	}

/**
 * Check if the given module name is a Field handler.
 *
 * @param string $module Module name to check
 * @return boolean TRUE if module is a field, FALSE otherwise
 */
	private static function __moduleIsField($module) {
		$module = Inflector::camelize($module);

		if (CakePlugin::loaded($module)) {
			$path = CakePlugin::path($module);

			if (strpos($path, DS . 'Fields' . DS) !== false) {
				return true;
			}
		}

		return false;
	}

/**
 * Check if the given module name is a theme-associated-module.
 *
 * @param string $module Module name to check
 * @return boolean TRUE if module is a field, FALSE otherwise
 */
	private static function __moduleIsTheme($module) {
		$module = Inflector::camelize($module);

		if (CakePlugin::loaded($module)) {
			$path = CakePlugin::path($module);

			if (strpos($path, DS . 'Themed' . DS) !== false) {
				return true;
			}
		}

		return false;
	}

/**
 * Check if the given theme name is a QuickApps CMS core-theme.
 *
 * @param string $theme Theme name to check
 * @return boolean TRUE if theme is a core theme, FALSE otherwise
 */
	private static function __themeIsCore($theme) {
		$theme = Inflector::camelize($theme);
		$theme = strpos($theme, 'Theme') !== 0 ? "Theme{$theme}" : $theme;

		if (CakePlugin::loaded($theme)) {
			$app_path = CakePlugin::path($theme);

			if (strpos($app_path, APP . 'View' . DS . 'Themed' . DS) !== false) {
				return true;
			}
		}

		return false;
	}

/**
 * Check if the given theme name is a backend theme.  
 * If no theme name is given current theme is checked.
 *
 * @param string $theme Optional theme name to check, FALSE will check actual theme. (defaul false)
 * @return boolean
 */
	private static function __themeIsAdmin($theme = false) {
		$theme = !$theme ? self::themeName() : $theme;
		$theme = Inflector::camelize($theme);
		$theme = strpos($theme, 'Theme') !== 0 ? "Theme{$theme}" : $theme;

		if ($info = Configure::read('Modules.' . $theme)) {
			if (isset($info['yaml']['info']['admin']) && $info['yaml']['info']['admin'] === true) {
				return true;
			}
		}

		return false;
	}

/**
 * Checks if current view is site's front page.
 *
 * @return boolean
 */
	private static function __viewIsFrontpage() {
		$params = Router::getParams();

		return (
			$params['plugin'] == 'Node' &&
			$params['action'] == 'index' &&
			!Configure::read('Variable.site_frontpage')
		);
	}

/**
 * Checks if current view is the login screen.
 *
 * @return boolean
 */
	private static function __viewIsLogin() {
		$params = Router::getParams();

		return (
			$params['plugin'] == 'user' &&
			$params['controller'] == 'user' &&
			in_array($params['action'], array('login', 'admin_login'))
		);
	}

/**
 * Checks if current view IS a backend view.
 *
 * @return boolean
 */
	private static function __viewIsAdmin() {
		$params = Router::getParams();

		return isset($params['admin']) && $params['admin'];
	}

/**
 * Checks if current view IS NOT a backend view.
 *
 * @return boolean
 */
	private static function __viewIsFrontend() {
		return !self::__viewIsAdmin();
	}

/**
 * Alias for QuickApps::is('view.admin').
 * Checks if current view IS NOT a backend view.
 *
 * @return boolean
 * @see QuickApps::__viewIsAdmin()
 */
	private static function __viewIsBackend() {
		return self::__viewIsAdmin();
	}

/**
 * Checks if current view is search result.  
 *
 * @return boolean
 */
	private static function __viewIsSearch() {
		$params = Router::getParams();

		return (
			strtolower($params['plugin']) == 'node' &&
			$params['controller'] == 'node' &&
			$params['action'] == 'search'
		);
	}

/**
 * Checks if current view is a search result feed.
 * Feed may be an RSS, Ajax or XML result.
 *
 * @param string $type Type of feed to detect (rss, ajax, xml). FALSE (default) means any
 * @return boolean
 */
	private static function __viewIsFeed($type = false) {
		$params = Router::getParams();
		$c1 = true;
		$c2 = (
			$params['plugin'] == 'node' &&
			$params['controller'] == 'node' &&
			$params['action'] == 'search' &&
			isset($params['named']['feed'])
		);

		if ($type) {
			$c1 = (
				isset($params['named']['feed']) &&
				$params['named']['feed'] == $type
			);
		}

		return $c1 && $c2;
	}

/**
 * Checks if current view is a node details page.  
 *
 * @return boolean
 */
	private static function __viewIsNode() {
		$params = Router::getParams();

		return (
			strtolower($params['plugin']) == 'node' &&
			$params['controller'] == 'node' &&
			$params['action'] == 'details'
		);
	}

/**
 * Checks if current view is an user's profile view.  
 *
 * @return boolean
 */
	private static function __viewIsUserProfile() {
		$params = Router::getParams();

		return (
			strtolower($params['plugin']) == 'user' &&
			$params['controller'] == 'user' &&
			$params['action'] == 'profile'
		);
	}

/**
 * Checks if current view is an user's "my account" view.
 *
 * @return boolean
 */
	private static function __viewIsUserMyAccount() {
		$params = Router::getParams();

		return (
			strtolower($params['plugin']) == 'user' &&
			$params['controller'] == 'user' &&
			$params['action'] == 'my_account'
		);
	}

/**
 * Checks if user has admin privileges.
 *
 * @return boolean
 */
	private static function __userIsAdmin() {
		return in_array(1, (array)self::userRoles());
	}

/**
 * Checks if user is logged in.
 *
 * @return boolean
 */
	private static function __userIsLogged() {
		return CakeSession::check('Auth.User.id');
	}

/**
 * Checks if user is allowed to access the specified ACO.  
 * ACO path syntax: `Module.Controller.action`
 *
 * @param string $acoPath Dot-Syntax path to aco. e.g.: `Block.Manage.admin_index`
 * @return boolean
 */
	private static function __userIsAuthorized($acoPath) {
		if (isset(self::$__tmp['authorized'][$acoPath])) {
			return self::$__tmp['authorized'][$acoPath];
		}

		$roles = self::userRoles();

		if (in_array(1, $roles)) {
			self::$__tmp['authorized'][$acoPath] = true;

			return true;
		}

		list($plugin, $controller, $action) = explode('.', $acoPath);

		if ($plugin && $controller && $action) {
			$Aco = ClassRegistry::init('Aco');
			$Permission = ClassRegistry::init('Permission');
			$conditions = array();
			$p = $Aco->find('first', array('conditions' => array('Aco.parent_id' => null, 'Aco.alias' => $plugin), 'recursive' => -1));
			$c = $Aco->find('first', array('conditions' => array('Aco.parent_id' => $p['Aco']['id']), 'recursive' => -1));
			$a = $Aco->find('first', array('conditions' => array('Aco.parent_id' => $c['Aco']['id']), 'recursive' => -1));

			foreach ($roles as $role) {
				$conditions['OR'][] = array(
					'AND' => array(
						'Permission.aro_id' => $role,
						'Permission.aco_id ' => $a['Aco']['id'],
						'Permission._create' => 1,
						'Permission._read' => 1,
						'Permission._update' => 1,
						'Permission._delete' => 1
					)
				);
			}

			$authorized = $Permission->find('count', array('conditions' => $conditions)) > 0;
			self::$__tmp['authorized'][$acoPath] = $authorized;

			return $authorized;
		}

		return false;
	}
}