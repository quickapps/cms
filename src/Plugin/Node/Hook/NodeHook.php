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

use Cake\Event\Event;
use Cake\Event\EventListener;

/**
 * Main Hook Listener for Node plugin.
 *
 */
class NodeHook implements EventListener {

/**
 * Returns a list of hooks this Hook Listener is implementing. When the class is registered
 * in an event manager, each individual method will be associated with the respective event.
 *
 * @return void
 */
	public function implementedEvents() {
		return [
			'ObjectRender.Node\Model\Entity\Node' => 'renderNode'
		];
	}

/**
 * Renders a single Node.
 *
 * You can define `specialized-renders` according to your needs as follow.
 * This method looks for specialized renders in the order described below,
 * if one is not found we look the next one, etc.
 *
 * ### render_node_[node_type]_[viewMode]
 *
 * Renders the given node per node-type + viewMode combination. example:
 *
 *     // render for `article` nodes in `full` view-mode
 *     `render_node_article_full.ctp`
 *
 *     // render for `article` nodes in `search_result` view-mode
 *     `render_node_article_search_result.ctp`
 *
 * ### render_node_[node_type]
 *
 * Similar as before, but just per `node-type` and any view-mode
 *
 *     // render for `article` nodes
 *     `render_node_article.ctp`
 *
 *     // render for `page` nodes
 *     `render_node_page.ctp`
 *
 * ### render_node
 *
 * This is the global render, if none of the above is found we try to use this last.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Node\Model\Entity\Node $node
 * @param array $options
 * @return string HTML
 */
	public function renderNode(Event $event, $node, $options = []) {
		$View = $event->subject;
		$viewMode = $View->getViewMode();
		$html = '';
		$try = [
			"render_node_{$node->node_type_slug}_{$viewMode}",
			"render_node_{$node->node_type_slug}",
			'render_node'
		];

		foreach ($try as $element) {
			if ($View->elementExists($element)) {
				$html = $View->element($element, ['node' => $node, 'options' => $options]);
				break;
			}
		}

		return $html;
	}

}
