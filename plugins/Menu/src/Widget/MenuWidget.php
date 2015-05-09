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
namespace Menu\Widget;

use Block\Model\Entity\Block;
use Block\Widget;
use Cake\ORM\TableRegistry;
use Cake\View\View;
use QuickApps\Core\StaticCacheTrait;

/**
 * Menu block rendering widget.
 *
 */
class MenuWidget extends Widget
{

    use StaticCacheTrait;

    /**
     * {@inheritDoc}
     *
     * Renders menu's associated block.
     *
     * This method will look for certain view elements when rendering each menu, if
     * one of this elements is not present it'll look the next one, and so on. These
     * view elements should be defined by Themes by placing them in
     * `<MyTheme>/Template/Element`.
     *
     * ### Render menu based on theme's region & view-mode
     *
     *     render_menu_[region-name]_[view-mode].ctp
     *
     * Renders the given block based on theme's `region-name` and `view-mode`, for
     * example:
     *
     * - `render_menu_left-sidebar_full.ctp`: Render for menus in `left-sidebar`
     *    region when view-mode is `full`.
     *
     * - `render_menu_left-sidebar_search-result.ctp`: Render for menus in
     *   `left-sidebar` region when view-mode is `search-result`
     *
     * - `render_menu_footer_search-result.ctp`: Render for menus in `footer` region
     *   when view-mode is `search-result`.
     *
     * ### Render menu based on theme's region
     *
     *     render_menu_[region-name].ctp
     *
     * Similar as before, but based only on theme's `region` (and any view-mode), for
     * example:
     *
     * - `render_menu_right-sidebar.ctp`: Render for menus in `right-sidebar`
     *   region.
     *
     * - `render_menu_left-sidebar.ctp`: Render for menus in `left-sidebar`
     *   region.
     *
     * ### Default
     *
     *     render_block.ctp
     *
     * This is the default render, if none of the above is found we try to use this
     * last. Themes can overwrite this view element by creating a new one
     * at `ExampleTheme/Template/Element/render_block.ctp`.
     *
     * ---
     *
     * NOTE: Please note the difference between "_" and "-"
     */
    public function render(Block $block, View $view)
    {
        $menu = TableRegistry::get('Menu.Menus')
            ->find()
            ->where(['Menus.id' => intval($block->settings['menu_id'])])
            ->first();
        $links = TableRegistry::get('Menu.MenuLinks')
            ->find('threaded')
            ->where(['menu_id' => $menu->id])
            ->order(['lft' => 'ASC']);
        $menu->set('links', $links);

        $viewMode = $view->viewMode();
        $blockRegion = isset($block->region->region) ? $block->region->region : 'none';
        $cacheKey = "render_{$blockRegion}_{$viewMode}";
        $cache = static::cache($cacheKey);
        $element = 'Menu.render_menu';

        if ($cache !== null) {
            $element = $cache;
        } else {
            $try = [
                "Menu.render_menu_{$blockRegion}_{$viewMode}",
                "Menu.render_menu_{$blockRegion}",
                'Menu.render_menu'
            ];

            foreach ($try as $possible) {
                if ($view->elementExists($possible)) {
                    $element = static::cache($cacheKey, $possible);
                    break;
                }
            }
        }

        return $view->element($element, compact('menu'));
    }
}
