<?php
/**
 * Theme Helper
 * Theme: Default
 *
 * PHP version 5
 *
 * @package  QuickApps.Themes.Default.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class ThemeDefaultHookHelper extends AppHelper {
	public function beforeRender($viewFile) {
		$this->_View->viewVars['Layout']['meta']['viewport'] = array(
			'name' => 'viewport',
			'content' => 'width=device-width; initial-scale=1.0'
		);
	}

/**
 * Display search form only on zero (0) results.
 *
 * @return void
 */
	public function stylesheets_alter(&$css) {
		if (count($this->_View->viewVars['Layout']['node'])) {
			$css['inline'][] = "#search-advanced { display:none; }";
		}
	}

/**
 * Adding toggle effect to advanced search form
 *
 * @return void
 */
	public function javascripts_alter(&$js) {
		if (
			$this->request->params['plugin'] == 'node' &&
			$this->request->params['controller'] == 'node' &&
			$this->request->params['action'] == 'search' &&
			!count($this->_View->viewVars['Layout']['node'])
		) {
			$js['inline'][] = '
				$(document).ready(function() {
					$("#toggle-search_advanced").click(function () {
						$("#search_advanced").toggle("fast");
					});
				});
				';
		}
	}
}