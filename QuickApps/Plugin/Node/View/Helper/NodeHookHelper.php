<?php
/**
 * Node View Hooks
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Node.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
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
            $this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar-index') . '<!-- NodeHookHelper -->'), 'toolbar');
        }

        // content types
        if (isset($this->request->params['admin']) &&
            $this->request->params['plugin'] == 'node' &&
            $this->request->params['controller'] == 'types' &&
            $this->request->params['action'] == 'admin_index'
        ) {
            $this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar-types') . '<!-- NodeHookHelper -->'), 'toolbar');
        }

        // display
        if (isset($this->request->params['admin']) &&
            $this->request->params['plugin'] == 'node' &&
            $this->request->params['controller'] == 'types' &&
            $this->request->params['action'] == 'admin_display'
        ) {
            $this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar-display') . '<!-- NodeHookHelper -->'), 'toolbar');
        }

        return true;
    }

/**
 * Block: Search form.
 *
 * @return array formatted block array
 */
    public function node_search($data) {
        return array(
            'title' => __t('Search'),
            'body' => $this->_View->element('Node.search_block', array('data' => $data))
        );
    }

/**
 * Block settings: Search form.
 *
 * @return string HTML element
 */
    public function node_search_settings($data) {
        return $this->_View->element('Node.search_block_settings', array('data' => $data));
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
 * Rendering for custom node types.
 *
 * @return string HTML element
 */
    public function node_render($node) {
        $tp = App::themePath(Configure::read('Theme.info.folder'));

        if ($this->is('view.node') &&
            file_exists($tp . 'Elements' . DS . 'theme_node_' . $node['NodeType']['id'] . '.ctp')
        ) {
            return $this->_View->element('theme_node_' . $node['NodeType']['id'], array('node' => $node));
        } else {
            return $this->_View->element('theme_node', array('node' => $node));
        }
    }
}