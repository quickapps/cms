<?php
/**
 * Block Hooktags
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Block.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@qucikapps.es>
 * @link     http://www.quickappscms.org
 */
class BlockHooktagsHelper extends AppHelper {
/**
 * Renders out single block by ID.
 * ### Usage:
 *  `[block id=1 /]`
 *
 * @return string HTML
 */
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

        $title = isset($title) ? $title : true;
        $body = isset($body) ? $body : true;
        $region = isset($region) ? $region : false;

        return $this->_View->Layout->block($block,
            array(
                'title' => $title,
                'body' => $body,
                'region' => $region
            )
        );
    }

/**
 * Renders out all the blocks that belongs to the specified region.
 * ### Usage:
 *  `[blocks_in region=region-name /]`
 *
 * @return string HTML
 */
    public function blocks_in($options) {
        extract($options);

        if (!isset($region)) {
            return;
        }

        return $this->_View->Layout->blocks($region);
    }
}