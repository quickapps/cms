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
namespace Block\Event;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Utility\Hash;
use QuickApps\Core\StaticCacheTrait;
use QuickApps\Event\HookAwareTrait;

/**
 * Block rendering dispatcher.
 *
 * Handles the `Block.<handler>.display` event.
 * 
 * Each block has a `handler` property which identifies the plugin that created
 * that Block, by default all blocks created using backend's administration page
 * defines `Block` has their handler.
 *
 * An external plugin may register a custom block by inserting its information
 * directly in the "blocks" table and setting an appropriate `handler` name.
 * 
 * For example, `Taxonomy` plugin may create a new `Categories` block by inserting
 * its information in the "blocks" table, this new block may have `Taxonomy` as
 * handler name.
 *
 * Block's handler property is used to compose event's name that is triggered
 * when block is being rendered (or edited). Event's name follows the pattern below:
 *
 *     Block.<handler>.<display|settings>
 *
 * So all blocks with `Block` as handler will trigger the event below when
 * being rendered:
 *
 *     Block.Block.display
 *
 * The event described above is handled by this class. In the other hand, for
 * the taxonomy example described above the following event will be triggered
 * when rendering the `Categories` block:
 *
 *     Block.Taxonomy.display
 *
 * Taxonomy plugin should catch this event and return a STRING.
 *
 * ---
 * 
 * **NOTES:**
 * 
 * - Event's subject is always the View instance being used.
 * - Plugins are allowed to define any `handler` name when registering blocks in
 *   the "blocks" table, the only constraint is that it must be unique in the
 *   entire "blocks" table. Use plugin's name itself is always a good practice
 *   as it's already unique in the whole system. Anyway, handler names such as
 *   `random-letters`, or `i-like-trains` are valid as well.
 */
class BlockHook implements EventListenerInterface {

	use HookAwareTrait;
	use StaticCacheTrait;

/**
 * Returns a list of hooks this Hook Listener is implementing. When the class
 * is registered in an event manager, each individual method will be associated
 * with the respective event.
 *
 * @return void
 */
	public function implementedEvents() {
		return [
			'Block.Block.display' => 'displayBlock',
		];
	}

/**
 * Renders the given block entity.
 *
 * You can define `specialized-renders` according to your needs as follow.
 * This method looks for specialized renders in the order described below, if one
 * is not found we look the next one, etc.
 *
 * ### Render block per theme's region & view-mode
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
 * ### Render block per theme's region
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
 *     render_block.ctp
 *
 * This is the global render, if none of the above is found we try to use this last.
 *
 * ---
 *  
 * NOTE: Please note the difference between "_" and "-"
 *
 * @param \Cake\Event\Event $event The event that was triggered
 * @param \Block\Model\Entity\Block $block Block entity to be rendered
 * @param array $options Additional options, will be passed to the template
 *  element being rendered
 * @return string The rendered block
 */
	public function displayBlock(Event $event, $block, $options = []) {
		$View = $event->subject;
		$viewMode = $View->inUseViewMode();
		// avoid scanning file system every time a block is being rendered
		$cacheKey = "displayBlock_{$block->region->region}_{$viewMode}";
		$cache = static::cache($cacheKey);
		if ($cache !== null) {
			$element = $cache;
		} else {
			$element = 'Block.render_block';
			$try = [
				"Block.render_block_{$block->region->region}_{$viewMode}",
				"Block.render_block_{$block->region->region}",
				'Block.render_block'
			];

			foreach ($try as $possible) {
				if ($View->elementExists($possible)) {
					$element = static::cache($cacheKey, $possible);
					break;
				}
			}
		}

		return $View->element($element, compact('block', 'options'));
	}

}
