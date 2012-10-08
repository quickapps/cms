<?php
App::uses('JSMin', 'Vendor');

/**
 * jQueryUI static class.
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.Lib
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class jQueryUI {
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
 * Loads in stack all the specified jQueryUI JS files.
 *
 * ### Loading presets
 *
 *     $this->jQueryUI->add('sortable');
 *
 * The above will load all the JS libraries required for a `sortable` interaction.
 * You can only load one preset per call.
 * The code below is **NOT ALLOWED**:
 *
 *     $this->jQueryUI->add('sortable', 'draggable');
 *
 * You must use one call per preset instead:
 *
 *     $this->jQueryUI->add('sortable');
 *     $this->jQueryUI->add('draggable');
 *
 * ### Loading individual libraries
 *
 *     $this->jQueryUI->add('effects.blind', 'effects.fade');
 *
 * The above will load both `blind` & `fade` effects.
 *
 * @return mixed
 *	TRUE if `all` was included.
 *	FALSE if no files were included because they are already included or were not found.
 *	String HTML <script> tags on success.
 * @see jQueryUI::$__presets 
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
 * Loads in stack the CSS styles for the specified jQueryUI theme.
 * Plugin-Dot-Syntax allowed.
 *
 * ### Usage
 *
 *     $this->jQueryUI->theme('MyModule.flick');
 *
 * The above will load `flick` theme.
 * Theme should be located in `ROOT/Modules/MyModule/webroot/css/ui/flick/`
 *
 *     $this->jQueryUI->theme('flick');
 *
 * The above will load `flick` theme. But now it should be located in your site's webroot,
 * `ROOT/webroot/css/ui/flick/`
 *
 * ### Theme auto-detect
 *
 * If no theme is given ($theme = FALSE) this method will try:
 *
 * - To use global parameter `jQueryUI.default_theme`.
 * - To use `System.ui-lightness` otherwise.
 *
 * ### Default theme
 *
 * You can define the global parameter `jQueryUI.default_theme` in your site's bootstrap.php
 * to indicate the default theme to use.
 *
 *     Configure::write('jQueryUI.default_theme', 'flick');
 *
 * The `flick` theme will be used by default if no arguments is passed.
 *
 * @param mixed $theme String name of the theme to load (Plugin-dot-syntax allowed)
 * @return mixed
 *	TRUE if theme has been already included.
 *	FALSE theme was not found.
 *	String HTML <style> tags on success.
 */
	public static function theme($theme = false, &$stack) {
		if (!$theme) {
			if ($d = Configure::read('jQueryUI.default_theme')) {
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

		if (is_array($libs)) {
			self::$__presets[$name] = $libs;
		} elseif(func_num_args() >= 2) {
			$libs = func_get_args();
			$name = array_shift($libs);
			self::$__presets[$name] = $libs;
		}
	}

/**
 * Gets the full path of the first .css file located under the given path.
 *
 * @param string $folder Folder to read
 * @return string Full path to the first .css file in $path
 */
	private static function __themeCssFile($folder) {
		$f = new Folder($folder);
		$files = $f->read();

		if (isset($files[1][0])) {
			return $files[1][0];
		}

		return '';
	}
}