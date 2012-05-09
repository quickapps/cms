<?php
App::uses('JSMin', 'Vendor');

/**
 * JqueryUI class
 *
 * PHP version 5
 *
 * @package  QuickApps.Controller.Plugin.System.Lib
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class JqueryUI {
/**
 * List of loaded libraries.
 *
 * @var array
 */
	protected static $_loadedUI = array();

/**
 * List of loaded themes.
 *
 * @var array
 */
	protected static $_loadedThemes = array();

/**
 * UI presets list. Can be modified with `definePreset()`.
 *
 * @var array
 */
	private static $__presets = array(
		// interactions
		'draggable' => array('ui.core', 'ui.widget', 'ui.mouse'),
		'droppable' => array('ui.core', 'ui.widget', 'ui.mouse', 'ui.raggable'),
		'resizable' => array('ui.core', 'ui.widget', 'ui.mouse'),
		'selectable' => array('ui.core', 'ui.widget', 'ui.mouse'),
		'sortable' => array('ui.core', 'ui.widget', 'ui.mouse'),
		// widgets
		'accordion' => array('ui.core', 'ui.widget', 'effects.core'),
		'autocomplete' => array('ui.core', 'ui.widget', 'ui.position'),
		'button' => array('ui.core', 'ui.widget'),
		'datepicker' => array('ui.core'),
		'dialog' => array('ui.core', 'ui.position', 'ui.widget', 'ui.mouse', 'ui.draggable', 'ui.resizable'),
		'progressbar' => array('ui.core', 'ui.widget'),
		'slider' => array('ui.core', 'ui.widget', 'ui.mouse'),
		'tabs' => array('ui.core', 'ui.widget')
	);

/**
 * Loads in stack all the specified Jquery UI JS files.
 *
 * If site's `js` folder (ROOT/webroot/js/) is writable all the files will be
 * combined and mergered using JSMin.
 * See the contents of the `System/webroot/js/ui` sub-directory for a list of available
 * files that may be included.
 *
 * The required ui.core file is automatically included,
 * as is effects.core if you include any effects files.
 *
 * You can load a preset, which include all the required js files for that preset.
 *
 * @param mixed $files Array list of UI files to include. Or string as preset name to load.
 * @param array $stack Reference to AppController::$Layout['javascripts']['file']
 * @return mixed
 *  TRUE if `all` was included.
 *  FALSE if no files were included because they are already included or were not found.
 *  String HTML <script> tags on success.
 * @see JqueryUI::$__presets
 */
	public static function add($files = array(), &$stack) {
		if (in_array('all', self::$_loadedUI)) {
			return true;
		}

		// no arguments -> load all
		if (empty($files)) {
			$files = array('all');
		}

		// load preset
		if (
			(is_array($files) && count($files) === 1 && strpos($files[0], '.') === false && isset(self::$__presets[strtolower($files[0])])) ||
			(is_string($files) && strpos($files, '.') === false && isset(self::$__presets[strtolower($files)]))
		) {
			$preset = is_array($files) ? strtolower($files[0]) : strtolower($files);
			self::$_loadedUI[] = $preset;
			$files = array();
			$files = self::$__presets[$preset];
			$files[] = 'ui.' . $preset;
		}

		$m = implode('|', $files);

		// autoload missing effects.core
		if (strpos($m, 'effects.') !== false &&
			strpos($m, 'effects.core') === false &&
			!in_array('effects.core', self::$_loadedUI) &&
			!in_array('all', $files)
		) {
			array_unshift($files, 'effects.core');
		}

		// autoload missing ui.core
		if (strpos($m, 'ui.') !== false &&
			strpos($m, 'ui.core') === false &&
			!in_array('ui.core', self::$_loadedUI) &&
			!in_array('all', $files)
		) {
			array_unshift($files, 'ui.core');
		}

		$files = array_diff($files, self::$_loadedUI);
		self::$_loadedUI = Hash::merge(self::$_loadedUI, $files);
		$rootJS = ROOT . DS . 'webroot' . DS . 'js' . DS;

		if (!empty($files)) {
			if (is_writable($rootJS)) {
				$cache = '';
				$source = CakePlugin::path('System') . 'webroot' . DS . 'js' . DS . 'ui' . DS;

				foreach ($files as $file) {
					if (file_exists("{$source}jquery.{$file}.min.js")) {
						$content = preg_replace('/\(jQuery\)$/', '(jQuery);', file_get_contents("{$source}jquery.{$file}.min.js"));
						$cache .=  "{$content}\n\n";
					}
				}

				if (empty($cache)) {
					false;
				}

				$cacheFile = 'jquery-ui-' . md5($cache) . '.js';

				if (!cache(str_replace(WWW_ROOT, '', JS) . $cacheFile, null, '+99 days', 'public')) {
					$cache = '/* ' . implode(',', $files) . ' */' . "\n" . JSMin::minify($cache);

					cache(str_replace(WWW_ROOT, '', JS) . $cacheFile, $cache, '+99 days', 'public');
				}

				$stack[] = "/js/{$cacheFile}";

				return '<script type="text/javascript" src="' . Router::url("/js/{$cacheFile}") . '"></script>';
			} else {
				$out = '';

				foreach ($files as $file) {
					$stack[] = "/system/js/ui/jquery.{$file}.min.js";
					$out .= '<script type="text/javascript" src="' . Router::url("/system/js/ui/jquery.{$file}.min.js") . '"></script>';
				}

				return $out;
			}
		}

		return false;
	}

/**
 * Loads in stack the CSS styles for the specified Jquery UI theme.
 *
 * If no theme name is given (false) then `Configure::read('JqueryUI.default_theme')`
 * will be used by default if its set. You can define this value in your site's
 * `bootstrap.php` file.
 * `System.ui-lightness` will be used otherwise.
 *
 * Themes must be located under `/webroot/css/ui/` folder of you Module or Site.
 * Example, some valid routes are:
 *  - Core module webroot: ROOT/QuickApps/Plugin/System/webroot/css/ui/theme-name/
 *  - Site webroot: ROOT/webroot/css/ui/theme-name/
 *  - Module webroot: ROOT/Modules/MyModule/webroot/css/ui/theme-name/
 *
 * A theme folder must contain at least one .css file to be included. e.g.:
 *  ROOT/webroot/css/ui/theme-name/
 *	  images/
 *	  theme-name.css
 *
 * Plugin-dot-syntax is allowed, for themes located in Module's css folder.
 * `Site Css` folder will be used otherwise.
 *
 * ## Example:
 *  `MyModule.theme_name`:
 *	  This will load **the .css file** located in `ROOT/Modules/MyModule/webroot/css/ui/theme_name/`
 *  `theme_name`:
 *	  This will load **the .css file** located in `ROOT/webroot/css/ui/theme_name/`
 *
 * @param mixed $theme String name of the theme to load (Plugin-dot-syntax allowed)
 *					 or leave empty for auto-detect.
 * @param array $stack Reference to AppController::$Layout['stylesheets']['file']
 * @return mixed
 *  TRUE if theme has been already included.
 *  FALSE theme was not found.
 *  String HTML <style> tags on success.
 */
	public static function theme($theme = false, &$stack) {
		if (!$theme) {
			if ($d = Configure::read('JqueryUI.default_theme')) {
				$theme = $d;
			} else {
				$theme = 'System.ui-lightness';
			}
		}

		list($plugin, $theme) = pluginSplit($theme);

		if (in_array($theme, self::$_loadedThemes)) {
			return true;
		}

		if ($plugin) {
			$plugin = Inflector::camelize($plugin);
			$base = CakePlugin::path($plugin) . 'webroot' . DS . 'css' . DS . 'ui' . DS;
			$cssName = self::__themeCssFile($base . $theme);
			$path = $base . $theme . DS . $cssName;
			$plugin = "/{$plugin}";
		} else {
			$cssName = self::__themeCssFile(ROOT . DS . 'webroot' . DS . 'css' . DS . 'ui' . DS . $theme);
			$path = ROOT . DS . 'webroot' . DS . 'css' . DS . 'ui' . DS . $theme . DS . $cssName;
			$plugin = '';
		}

		if (file_exists($path)) {
			$stack[] = "{$plugin}/css/ui/{$theme}/{$cssName}";
			self::$_loadedThemes = Hash::merge(self::$_loadedThemes, array($theme));

			return '<link rel="stylesheet" type="text/css" href="' . Router::url("{$plugin}/css/ui/{$theme}/{$cssName}") . '" media="all" />';
		}

		return false;
	}

/**
 * Register a new preset, or overwrite if already exists.
 *
 * @param string $name Lowercase preset name. e.g.: `preset_name`
 * @param array $libs Array of libraries used by the preset
 * @return void
 */
	public static function definePreset($name, $libs = array()) {
		$name = strtolower($name);
		self::$__presets[$name] = $libs;
	}

	private static function __themeCssFile($folder) {
		$f = new Folder($folder);
		$files = $f->read();

		if (isset($files[1][0])) {
			return $files[1][0];
		}
	}
}