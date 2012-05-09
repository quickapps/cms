<?php
App::uses('JqueryUI', 'System.Lib');

/**
 * Jquery UI Component
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.System.Controller.Component
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class JqueryUIComponent extends Component {
	public $Controller;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param object $controller Controller with components to initialize
 * @return void
 */
	public function initialize(Controller $Controller) {
		$this->Controller = $Controller;
		$this->Controller->helpers[] = 'System.JqueryUI';
	}

/**
 * Loads in stack all the specified Jquery UI JS files.
 *
 * ### Example
 *
 * #### Loading presets:
 * {{{
 *  $this->JqueryUI->add('sortable');
 * }}}
 *
 * The above will load all the JS libraries required for a `sortable` interaction.
 * You can only load one preset per call.
 * The code below is NOT ALLOWED:
 *
 * {{{
 *  $this->JqueryUI->add('sortable', 'draggable');
 * }}}
 *
 * You must use one call per preset instead:
 *
 * {{{
 *  $this->JqueryUI->add('sortable');
 *  $this->JqueryUI->add('draggable');
 * }}}
 *
 * #### Loading individual libraries:
 * {{{
 *  $this->JqueryUI->add('effects.blind', 'effects.fade');
 * }}}
 *
 * The above will load both `blind` & `fade` effects.
 *
 * @return mixed
 *  TRUE if `all` was included.
 *  FALSE if no files were included because they are already included or were not found.
 *  String HTML <script> tags on success.
 * @see JqueryUI::add()
 */
	public function add() {
		$files = func_get_args();

		return JqueryUI::add($files, $this->Controller->Layout['javascripts']['file']);
	}

/**
 * Loads in stack the CSS styles for the specified Jquery UI theme.
 *
 * ### Theme auto-detect:
 * If no theme is given ($theme = FALSE) the function will try:
 *  - To use global parametter `JqueryUI.default_theme`.
 *  - To use `System.ui-lightness` otherwise.
 *
 * ### Examples:
 * {{{
 *  $this->JqueryUI->theme('MyModule.flick');
 * }}}
 *
 * The above will load `flick` theme.
 * Theme should be located in `ROOT/Modules/MyModule/webroot/css/ui/flick/`
 *
 * {{{
 *  $this->JqueryUI->theme('flick');
 * }}}
 *
 * The above will load `flick` theme. But, now it should be located in
 * `ROOT/webroot/css/ui/flick/`
 *
 * @param mixed $theme String name of the theme to load (Plugin-dot-syntax allowed)
 * @return mixed
 *  TRUE if theme has been already included.
 *  FALSE theme was not found.
 *  String HTML <style> tags on success.
 * @see JqueryUI::theme()
 */
	public function theme($theme = false) {
		return JqueryUI::theme($theme, $this->Controller->Layout['stylesheets']['all']);
	}
}