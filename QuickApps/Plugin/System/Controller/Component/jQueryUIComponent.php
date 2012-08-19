<?php
App::uses('jQueryUI', 'System.Lib');

/**
 * jQueryUI Component
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.Controller.Component
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class jQueryUIComponent extends Component {
	public $Controller;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param object $controller Controller with components to initialize
 * @return void
 */
	public function initialize(Controller $Controller) {
		$this->Controller = $Controller;
		$this->Controller->helpers[] = 'System.jQueryUI';
	}

/**
 * Loads in stack all the specified jQueryUI JS files.
 *
 * @return mixed
 *  TRUE if `all` was included.
 *  FALSE if no files were included because they are already included or were not found.
 *  String HTML <script> tags on success.
 * @see jQueryUI::add()
 */
	public function add() {
		$files = func_get_args();

		return jQueryUI::add($files, $this->Controller->Layout['javascripts']['file']);
	}

/**
 * Loads in stack the CSS styles for the specified jQueryUI theme.
 *
 * @param mixed $theme String name of the theme to load (Plugin-dot-syntax allowed)
 * @return mixed
 *  TRUE if theme has been already included.
 *  FALSE theme was not found.
 *  String HTML <style> tags on success.
 * @see jQueryUI::theme()
 */
	public function theme($theme = false) {
		return jQueryUI::theme($theme, $this->Controller->Layout['stylesheets']['all']);
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