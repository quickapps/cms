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
	public $helpers = array('System.jQueryUI', 'Node.Node');

	public function beforeRender($viewFile) {
		$this->_View->viewVars['Layout']['meta']['viewport'] = array(
			'name' => 'viewport',
			'content' => 'width=device-width; initial-scale=1.0'
		);

		if (
			$this->request->params['plugin'] == 'node' &&
			$this->request->params['controller'] == 'node' &&
			$this->request->params['action'] == 'details'
		) {
			$this->jQueryUI->add('dialog');
			$this->jQueryUI->theme();
		}
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

		if (
			$this->request->params['plugin'] == 'node' &&
			$this->request->params['controller'] == 'node' &&
			$this->request->params['action'] == 'details'
		) {
			$js['inline'][] = '
				$(document).ready(function() {
					$("a.comment-reference").click(function () {
						var theRef = $(this);
						var cid = $(this).children("span").html().split("#")[1];
						var ref = $.ajax("' . Router::url('/theme_default/comment/reference/' . $this->Node->getAttr('id'), true) . '/" + cid, {cache: true})
							.done(function (data) {
								if (data) {
									$(".ui-dialog-content").dialog("close");

									$("<div>" + data + "</div>").dialog({
										resizable: false,
										maxWidth: 300,
										draggable: false,
										closeOnEscape: true,
										position: {my: "left top", at: "left bottom", of: theRef}
									});
								}
							});

						return false;
					});
				});
			';
		}
	}
}