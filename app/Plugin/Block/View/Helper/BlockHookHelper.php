<?php
/**
 * Block View Hooks
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Block.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link     http://cms.quickapps.es
 */
class BlockHookHelper extends AppHelper {
    // toolbar
    public function beforeLayout($layoutFile) {
        if (isset($this->request->params['plugin']) &&
            $this->request->params['plugin'] == 'block' &&
            $this->request->params['action'] != 'admin_add'
        ) {
            $this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar')), 'toolbar');
        }

        return true;
    }

    // hooktag, block rendering
    public function block($options) {
        extract($options);

        if (!isset($id)) {
            return;
        }

        if ($_block = Set::extract("/Block[id={$id}]/..", $this->_View->viewVars['Layout']['blocks'])) {
            $block = $_block[0];
        } else {
            $block = ClassRegistry::init('Block.Block')->findById($id);
        }

        if (!$block) {
            return;
        }

        $region = isset($region) ? $region : false;
        $title = isset($title) ? int_val($title) : false;

        return $this->_View->Layout->block($block, array('title' => $title, 'region' => $region));
    }

    // hooktag
    public function block_title($options) {
        extract($options);

        if (!isset($id)) {
            return;
        }

        if ($_block = Set::extract("/Block[id={$id}]/..", $this->_View->viewVars['Layout']['blocks'])) {
            $block = $_block[0];
        } else {
            $block = ClassRegistry::init('Block.Block')->findById($id);
        }

        if (!$block) {
            return false;
        }

        return $this->_View->Layout->hooktags($block['Block']['title']);
    }
}