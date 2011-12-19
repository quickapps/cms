<?php
/**
 * Node View Hooks
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Node.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class NodeHookHelper extends AppHelper {
    public function beforeLayout($layoutFile) {
        if (!isset($this->request->params['admin'])) {
            return true;
        }

        # content list toolbar:
        if (isset($this->request->params['admin']) &&
            $this->request->params['plugin'] == 'node' &&
            $this->request->params['controller'] == 'contents' &&
            $this->request->params['action'] == 'admin_index'
        ) {
            $this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar-index') . '<!-- NodeHookHelper -->'), 'toolbar');
        }

        # content types toolbar:
        if (isset($this->request->params['admin']) &&
            $this->request->params['plugin'] == 'node' &&
            $this->request->params['controller'] == 'types' &&
            $this->request->params['action'] == 'admin_index'
        ) {
            $this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar-types') . '<!-- NodeHookHelper -->'), 'toolbar');
        }

        # display toolbar:
        if (isset($this->request->params['admin']) &&
            $this->request->params['plugin'] == 'node' &&
            $this->request->params['controller'] == 'types' &&
            $this->request->params['action'] == 'admin_display'
        ) {
            $this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar-display') . '<!-- NodeHookHelper -->'), 'toolbar');
        }

        return true;
    }

    # search block
    public function node_search($data) {
        return array(
            'title' => __d('node', 'Search'),
            'body' => $this->_View->element('search_block', array('data' => $data), array('plugin' => 'Node'))
        );
    }

    # search block settings form
    public function node_search_settings($data) {
        return $this->_View->element('search_block_settings', array('data' => $data), array('plugin' => 'Node'));
    }

    # edit/add form (node type: Custom types)
    public function node_form($data) {
        return $this->_View->element('theme_node_edit', array('data' => $data));
    }

    # rendering (node type: Custom types) /node/details/
    public function node_render($node) {
        return $this->_View->element('theme_node', array('node' => $node));
    }
}