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
namespace Node\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use QuickApps\View\ViewModeRegistry;

/**
 * Main Hook Listener for Node plugin.
 *
 */
class NodeHook implements EventListenerInterface
{

    /**
     * Returns a list of hooks this Hook Listener is implementing. When the class is
     * registered in an event manager, each individual method will be associated with
     * the respective event.
     *
     * @return void
     */
    public function implementedEvents()
    {
        return [
            'Render.Node\Model\Entity\Node' => 'renderNode',
            'Block.Node.display' => 'renderBlock',
        ];
    }

    /**
     * Renders a single Content Node.
     *
     * You can define `specialized-renders` according to your needs as follow.
     * This method looks for specialized renders in the order described below,
     * if one is not found we look the next one, etc.
     *
     * ### Render node based on node-type & view-mode
     *
     *      render_node_[node-type]_[view-mode].ctp
     *
     * Renders the given node based on `node-type` + `view-mode` combination, for
     * example:
     *
     * - render_node_article_full.ctp: Render for "article" nodes in "full"
     *   view-mode.
     *
     * - render_node_article_search-result.ctp: Render for "article" nodes in
     *   "search-result" view-mode.
     *
     * - render_node_basic-page_search-result.ctp: Render for "basic-page" nodes in
     *   "search-result" view-mode.
     *
     * ### Render node based on node-type
     *
     *     render_node_[node-type].ctp
     *
     * Similar as before, but just based on `node-type` (and any view-mode), for
     * example:
     *
     * - render_node_article.ctp: Render for "article" nodes.
     *
     * - render_node_basic-page.ctp: Render for "basic-page" nodes
     *
     * ### Render node based on view-mode
     *
     *     render_node_[view-mode].ctp
     *
     * Similar as before, but just based on `view-mode` (and any node-type), for
     * example:
     *
     * - render_node_rss.ctp: Render any node (article, page, etc) in "rss"
     *   view-mode.
     *
     * - render_node_full.ctp: Render any node (article, page, etc) in "full"
     *   view-mode.
     *
     * NOTE: To avoid collisions between `view-mode` names and `node-type` names,
     * you should alway use unique and descriptive names as possible when defining
     * new content types. By default, Node plugin defines the following view-modes:
     * `default`, `teaser`, `search-result`, `rss`, `full`.
     *
     * ### Default
     *
     *     render_node.ctp
     *
     * This is the global render, if none of the above renders is found we try to
     * use this last. Themes can overwrite this view element by creating a new one
     * at `ExampleTheme/Template/Element/render_node.ctp`.
     *
     * ---
     *
     * NOTE: Please note the difference between "_" and "-"
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Node\Model\Entity\Node $node The node to render
     * @param array $options Additional options as an array
     * @return string HTML
     */
    public function renderNode(Event $event, $node, $options = [])
    {
        $View = $event->subject();
        $viewMode = $View->viewMode();
        $html = '';
        $try = [
            "Node.render_node_{$node->node_type_slug}_{$viewMode}",
            "Node.render_node_{$node->node_type_slug}",
            "Node.render_node_{$viewMode}",
            'Node.render_node'
        ];

        foreach ($try as $element) {
            if ($View->elementExists($element)) {
                $html = $View->element($element, compact('node', 'options'));
                break;
            }
        }

        return $html;
    }

    /**
     * Renders all blocks registered by Node plugin.
     *
     * Node plugin has two built-in blocks that comes with every QuickApps CMS
     * installation: "Recent Content" and "Search", both aimed to be placed on
     * backend's dashboard regions.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Block\Model\Entity\Block $block The block being rendered
     * @param array $options Additional options as an array
     * @return string
     */
    public function renderBlock(Event $event, $block, $options = [])
    {
        return $event->subject()->element("Node.{$block->delta}", compact('block', 'options'));
    }
}
