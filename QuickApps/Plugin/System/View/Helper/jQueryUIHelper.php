<?php
App::uses('jQueryUI', 'System.Lib');

/**
 * jQueryUI Helper
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class jQueryUIHelper extends AppHelper {
/**
 * Loads in stack all the specified Jquery UI JS files.
 *
 * @return mixed
 *	TRUE if `all` was included.
 *	FALSE if no files were included because they are already included or were not found.
 *	String HTML <script> tags on success.
 * @see jQueryUI::add()
 */
	public function add() {
		$files = func_get_args();

		return jQueryUI::add($files, $this->_View->viewVars['Layout']['javascripts']['file']);
	}

/**
 * Loads in stack the CSS styles for the specified Jquery UI theme.
 * Plugin-Dot-Syntax allowed.
 *
 * @param mixed $theme String name of the theme to load
 * @return mixed
 *	TRUE if theme has been already included.
 *	FALSE theme was not found.
 *	String HTML <style> tags on success.
 * @see jQueryUI::theme()
 */
	public function theme($theme = false) {
		return jQueryUI::theme($theme, $this->_View->viewVars['Layout']['stylesheets']['all']);
	}

/**
 * Register a new preset, or overwrite if already exists.
 *
 * @param string $name Lowercase preset name. e.g.: `preset_name`
 * @param array $libs Array of libraries used by the preset
 * @return void
 * @see jQueryUI::definePreset()
 */
	public function definePreset($name, $libs = array()) {
		jQueryUI::definePreset($name, $libs);
	}
}