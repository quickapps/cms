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
            'Dispatcher.beforeDispatch' => [
                'callable' => 'dispatcherBeforeDispatch',
                'priority' => -10,
            ],
            'Block.Node.display' => 'renderBlock',
        ];
    }

    /**
     * Fired before any controller instance is created.
     *
     * Here we register some basic view modes, for later use in controllers. We could
     * register this view modes at "bootstrap.php", but __d() would not work there
     * as no language has been set yet, so we do it here.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Cake\Network\Request $request Request object to dispatch
     * @param \Cake\Network\Response $response Response object to put the results of
     *  the dispatch into
     * @return void
     */
    public function dispatcherBeforeDispatch(Event $event, $request, $response)
    {
        ViewModeRegistry::addViewMode([
            'default' => [
                'name' => __d('node', 'Default'),
                'description' => __d('node', 'Default is used as a generic view mode if no other view mode has been defined for your content.'),
            ],
            'teaser' => [
                'name' => __d('node', 'Teaser'),
                'description' => __d('node', 'Teaser is a really short format that is typically used in main the main page, such as "last news", etc.'),
            ],
            'search-result' => [
                'name' => __d('node', 'Search Result'),
                'description' => __d('node', 'Search Result is a short format that is typically used in lists of multiple content items such as search results.'),
            ],
            'rss' => [
                'name' => __d('node', 'RSS'),
                'description' => __d('node', 'RSS is similar to "Search Result" but intended to be used when rendering content as part of a RSS feed list.'),
            ],
            'full' => [
                'name' => __d('node', 'Full'),
                'description' => __d('node', 'Full content is typically used when the content is displayed on its own page.'),
            ],
        ]);
    }

    /**
     * Renders a single Content Node.
     *
     * You can define `specialized-renders` according to your needs as follow.
     * This method looks for specialized renders in the order described below,
     * if one is not found we look the next one, etc.
     *
     * ### Render node per node-type & view-mode
     *
     *      render_node_[node-type]_[view-mode]
     *
     * Renders the given node per `node-type` + `view-mode` combination:
     *
     *     // render for "article" nodes in "full" view-mode
     *     render_node_article_full.ctp
     *
     *     // render for "article" nodes in "search-result" view-mode
     *     render_node_article_search-result.ctp
     *
     *     // render for "basic-page" nodes in "search-result" view-mode
     *     render_node_basic-page_search-result.ctp
     *
     * ### Render node per node-type
     *
     *     render_node_[node-type]
     *
     * Similar as before, but just per `node-type` and any view-mode:
     *
     *     // render for "article" nodes
     *     render_node_article.ctp
     *
     *     // render for "basic-page" nodes
     *     render_node_basic-page.ctp
     *
     * ### Render node per view-mode
     *
     *     render_node_[view-mode]
     *
     * Similar as before, but just per `view-mode` and any `node-type`:
     *
     *     // render any node (article, page, etc) in "rss" view-mode
     *     render_node_rss.ctp
     *
     *     // render any node (article, page, etc) in "full" view-mode
     *     render_node_full.ctp
     *
     * NOTE: To avoid collisions between `view-mode` names and `node-type` names, you
     * should alway use unique and descriptive names as possible when defining new
     * content types. By default, Node plugin defines the following view-modes:
     * `default`, `teaser`, `search-result`, `rss`, `full`.
     *
     * ### Default
     *
     *     render_node
     *
     * This is the global render, if none of the above is found we try to use this last.
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
        $viewMode = $View->inUseViewMode();
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
