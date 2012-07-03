<?php
/**
 * Jquery UI Helper
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class JqueryUIHelper extends AppHelper {
/**
 * Loads in stack all the specified Jquery UI JS files.
 *
 * @return mixed
 *	TRUE if `all` was included.
 *	FALSE if no files were included because they are already included or were not found.
 *	String HTML <script> tags on success.
 * @see JqueryUI::add()
 */
	public function add() {
		$files = func_get_args();

		return JqueryUI::add($files, $this->_View->viewVars['Layout']['javascripts']['file']);
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
 * @see JqueryUI::theme()
 */
	public function theme($theme = false) {
		return JqueryUI::theme($theme, $this->_View->viewVars['Layout']['stylesheets']['all']);
	}
}