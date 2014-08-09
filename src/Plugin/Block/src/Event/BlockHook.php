<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Hook;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListener;
use Cake\Event\EventManager;
use Cake\Utility\Hash;
use QuickApps\Utility\CacheTrait;
use QuickApps\Utility\HookTrait;

/**
 * Block rendering dispatcher.
 *
 * Dispatches `Render.Block\Model\Entity\Block` rendering-request from View.
 *
 *     $block = new \Block\Model\Entity\Block();
 *     $this->render($block);
 *     // triggers: `Render.Block\Model\Entity\Block`
 *
 * It also dispatches BlockHelper::block():
 *
 *     $block = new \Block\Model\Entity\Block();
 *     $this->Block->render($block);
 *     // triggers: `Block.Block.display`
 */
class BlockHook implements EventListener {

	use CacheTrait;
	use HookTrait;

/**
 * Returns a list of hooks this Hook Listener is implementing. When the class is registered
 * in an event manager, each individual method will be associated with the respective event.
 *
 * @return void
 */
	public function implementedEvents() {
		return [
			'Render.Block\Model\Entity\Block' => 'renderBlock',
			'Block.Block.display' => 'displayBlock',
		];
	}

/**
 * Renders the given block entity.
 *
 * @param \Cake\Event\Event $event
 * @param \Block\Model\Entity\Block $block Block entity to be rendered
 * @return string The rendered block
 */
	public function renderBlock(Event $event, $block, $options = []) {
		return $event->subject->Block->render($block, $options);
	}

/**
 * Renders the given block entity.
 *
 * You can define `specialized-renders` according to your needs as follow.
 * This method looks for specialized renders in the order described below,
 * if one is not found we look the next one, etc.
 *
 * ### Render blocks per theme's region & view-mode
 * 
 *      render_block_[region-name]_[view-mode]
 *
 * Renders the given block per theme's `region-name` + `view-mode` combination:
 *
 *     // render for blocks in `left-sidebar` region when view-mode is `full`
 *     `render_block_left-sidebar_full.ctp`
 *
 *     // render for blocks in `left-sidebar` region when view-mode is `search-result`
 *     `render_block_left-sidebar_search-result.ctp`
 *
 *     // render for blocks in `footer` region when view-mode is `search-result`
 *     `render_block_footer_search-result.ctp`
 *
 * ### Render blocks per theme's region
 *
 *     render_block_[region-name]
 *
 * Similar as before, but just per theme's `region` and any view-mode
 *
 *     // render for blocks in `right-sidebar` region
 *     `render_block_right-sidebar.ctp`
 *
 *     // render for blocks in `left-sidebar` region
 *     `render_block_left-sidebar.ctp`
 *
 * ### Default
 * 
 *     render_block
 *
 * This is the global render, if none of the above is found we try to use this last.
 *
 * ---
 *  
 * NOTE: Please note the difference between "_" and "-"
 *
 * @param \Cake\Event\Event $event
 * @param \Block\Model\Entity\Block $block Block entity to be rendered
 * @return string The rendered block
 */
	public function displayBlock(Event $event, $block, $options = []) {
		if (!($event->subject instanceof \Block\View\Helper\BlockHelper)) {
			return '';
		}

		$View = $event->subject->_View;
		// avoid scanning file system every time a block is being rendered
		$cacheKey = "displayBlock_{$block->block_regions->region}";
		$cache = static::_cache($cacheKey);
		if ($cache !== null) {
			$element = $cache;
		} else {
			$try = [
				"render_block_{$block->block_regions->region}",
				"render_block_{$block->block_regions->region}",
				'Block.render_block'
			];

			foreach ($try as $possible) {
				if ($View->elementExists($possible)) {
					$element = static::_cache($cacheKey, $possible);
					break;
				}
			}
		}

		return $View->element($element, compact('block', 'options'));
	}

}
