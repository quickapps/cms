<?php
/**
 * Block Hooktags
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Block.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@qucikapps.es>
 * @link	 http://www.quickappscms.org
 */
class BlockHooktagsHelper extends AppHelper {
	private $__tmp = array();
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

		if (!isset($this->__tmp['block_ids'])) {
			$this->__tmp['block_ids'] = Hash::extract($this->_View->viewVars['Layout']['blocks'], "{n}.Block.id");
		}

		if (in_array($id, $this->__tmp['block_ids'])) {
			foreach ($this->_View->viewVars['Layout']['blocks'] as $block) {
				if ($block['Block']['id'] == $id) {
					break;
				}
			}
		} else {
			$block = ClassRegistry::init('Block.Block')->findById($id);
		}

		if (!isset($block)) {
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
 *
 * ### Usage
 *
 *     [blocks_in region=region-name /]
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