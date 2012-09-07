<?php
/**
 * Node View Hooks
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Node.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class NodeHookHelper extends AppHelper {
/**
 * Toolbar menu for section:
 *  - `Contents`
 *  - `Structure/Content Types`
 *  - `Structure/Content Types/Display`
 *
 * @return void
 */
	public function beforeLayout($layoutFile) {
		if (!isset($this->request->params['admin'])) {
			return true;
		}

		// contents
		if (isset($this->request->params['admin']) &&
			$this->request->params['plugin'] == 'node' &&
			$this->request->params['controller'] == 'contents' &&
			$this->request->params['action'] == 'admin_index'
		) {
			$this->_View->Block->push(array('body' => $this->_View->element('toolbar-index') . '<!-- NodeHookHelper -->'), 'toolbar');
		}

		// content types
		if (isset($this->request->params['admin']) &&
			$this->request->params['plugin'] == 'node' &&
			$this->request->params['controller'] == 'types' &&
			$this->request->params['action'] == 'admin_index'
		) {
			$this->_View->Block->push(array('body' => $this->_View->element('toolbar-types') . '<!-- NodeHookHelper -->'), 'toolbar');
		}

		// display
		if (isset($this->request->params['admin']) &&
			$this->request->params['plugin'] == 'node' &&
			$this->request->params['controller'] == 'types' &&
			$this->request->params['action'] == 'admin_display'
		) {
			$this->_View->Block->push(array('body' => $this->_View->element('toolbar-display') . '<!-- NodeHookHelper -->'), 'toolbar');
		}

		return true;
	}

/**
 * Add/edit form for custom node types.
 *
 * @return string HTML element
 */
	public function node_form($data) {
		return $this->_View->element('theme_node_edit', array('data' => $data));
	}

/**
 * Theme individual content types.
 *
 * @return string HTML element
 */
	public function node_render($node) {
		$nodeType = false;

		if (!$this->_View->request->is('requested') &&
			isset($node['NodeType']['id'])
		) {
			$nodeType = $node['NodeType']['id'];
		} elseif (
			strtolower(Router::getParam('plugin')) == 'node' &&
			Router::getParam('controller') == 'node' &&
			Router::getParam('action') == 'index' &&
			$siteFrontPage = Configure::read('Variable.site_frontpage')
		) {
			$params = Router::parse($siteFrontPage);

			if (isset($params['pass'][0])) {
				$nodeType = $params['pass'][0];
			}
		}

		if ($nodeType) {
			$display = $this->_View->viewVars['Layout']['display'];

			if ($this->_View->Layout->elementExists("theme_node_{$nodeType}_{$display}")) {
				return $this->_View->element("theme_node_{$nodeType}_{$display}", array('node' => $node));
			} elseif ($this->_View->Layout->elementExists("theme_node_{$nodeType}")) {
				return $this->_View->element("theme_node_{$nodeType}", array('node' => $node));
			}
		}

		return $this->_View->element('theme_node', array('node' => $node));
	}
}