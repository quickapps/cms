<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Block\Event;

use Block\Model\Entity\Block;
use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use QuickApps\Core\StaticCacheTrait;
use QuickApps\Event\HookAwareTrait;

/**
 * Block rendering dispatcher.
 *
 * Each block has a `handler` property which identifies the plugin that created
 * that Block, by default all blocks created using backend's administration page
 * defines `Block` has their handler.
 *
 * An external plugin may register a custom block by inserting its information
 * directly in the "blocks" table and set an appropriate `handler` name.
 *
 * For example, `Taxonomy` plugin may create a new `Categories` block by inserting
 * its information in the "blocks" table, this new block will have `Taxonomy` as
 * its handler name.
 *
 * Block's handler property is used to compose event's name that is triggered
 * when block is being rendered (or edited). Event's name follows the pattern
 * described below:
 *
 *     Block.<handler>.<display|settings|...>
 *
 * So all blocks with `Block` as handler will trigger the event below when
 * being rendered:
 *
 *     Block.Block.display
 *
 * The event described above is handled by this class. In the other hand, for
 * the Taxonomy example described above the following event will be triggered
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
class BlockHook implements EventListenerInterface
{

    use HookAwareTrait;
    use StaticCacheTrait;

    /**
     * Returns a list of hooks this Hook Listener is implementing. When the class
     * is registered in an event manager, each individual method will be associated
     * with the respective event.
     *
     * @return void
     */
    public function implementedEvents()
    {
        return [
            'Render.Block\Model\Entity\Block' => 'renderBlock',
        ];
    }

    /**
     * Block rendering dispatcher, renders the given block.
     *
     * This method will delegate the rendering task to block's handler by triggering
     * the `Block.<handler>.display` event, or the event
     * `Block.<handler>.display<CamelizedDelta>`, where "CamelizedDelta" is delta's
     * CamelizedName, e.g.: `Block.<handler>.displayMyDeltaName` (for block's delta
     * `my-delta-name`).
     *
     * If such event does not exists it will look for certain view elements when
     * rendering each block, if one of this elements is not present it'll look the
     * next one, and so on. These view elements should be defined by Themes by
     * placing them in `<MyTheme>/Template/Element`.
     *
     * ### Render block based on theme's region & view-mode
     *
     *     render_block_[region-name]_[view-mode].ctp
     *
     * Renders the given block based on theme's **region-name and view-mode**, for
     * example:
     *
     * - render_block_left-sidebar_full.ctp: Render for blocks in `left-sidebar`
     *   region when view-mode is `full`
     *
     * - render_block_left-sidebar_search-result.ctp: Render for blocks in
     *   `left-sidebar` region when view-mode is `search-result`.
     *
     * - render_block_footer_search-result.ctp: Render for blocks in `footer`
     *   region when view-mode is `search-result`.
     *
     *
     * ### Render block based on theme's region
     *
     *     render_block_[region-name].ctp
     *
     * Similar as before, but based on theme's **region-name** (and any view-mode),
     * for example:
     *
     * - render_block_right-sidebar.ctp: Render for blocks in `right-sidebar` region.
     *
     * - render_block_left-sidebar.ctp: Render for blocks in `left-sidebar` region.
     *
     *
     * ### Default
     *
     *     render_block.ctp
     *
     * This is the global render, if none of the above renders is found we try to
     * use this last. Themes can overwrite this view element by creating a new one
     * at `ExampleTheme/Template/Element/render_block.ctp`.
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
    public function renderBlock(Event $event, Block $block, $options = [])
    {
        // plugin should take care of rendering
        $eventName = Inflector::variable('display_' . $block->get('delta'));
        if (in_array("Block.{$block->handler}.{$eventName}", listeners())) {
            return $this->trigger(["Block.{$block->handler}.{$eventName}", $event->subject()], $block, $options)->result;
        } elseif (in_array("Block.{$block->handler}.display", listeners())) {
            return $this->trigger(["Block.{$block->handler}.display", $event->subject()], $block, $options)->result;
        }

        $view = $event->subject();
        $viewMode = $view->viewMode();
        $blockRegion = isset($block->region->region) ? 'none' : $block->region->region;
        $cacheKey = "displayBlock_{$block->id}_{$blockRegion}_{$viewMode}";
        $cache = static::cache($cacheKey);
        $element = 'Block.render_block';

        if ($cache !== null) {
            $element = $cache;
        } else {
            $try = [
                "Block.render_block_{$blockRegion}_{$viewMode}",
                "Block.render_block_{$blockRegion}",
                'Block.render_block'
            ];

            foreach ($try as $possible) {
                if ($view->elementExists($possible)) {
                    $element = static::cache($cacheKey, $possible);
                    break;
                }
            }
        }

        return $view->element($element, compact('block', 'options'));
    }
}
