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
namespace Block\Widget;

use Block\Model\Entity\Block;
use Block\Widget;
use CMS\Core\StaticCacheTrait;
use CMS\View\View;

/**
 * Shows latest articles.
 *
 */
class CustomBlockWidget extends Widget
{

    use StaticCacheTrait;

    /**
     * {@inheritDoc}
     *
     * This method will look for certain view elements when rendering each custom
     * block, if one of this elements is not present it'll look the next one, and so
     * on. These view elements should be defined by Themes by placing them in
     * `<MyTheme>/Template/Element`.
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
     */
    public function render(Block $block, View $view)
    {
        $viewMode = $view->viewMode();
        $blockRegion = isset($block->region->region) ? 'none' : $block->region->region;
        $cacheKey = "render_{$block->id}_{$blockRegion}_{$viewMode}";
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
