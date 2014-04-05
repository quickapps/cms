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
namespace Node\Model\Entity;

use Cake\ORM\Entity;

/**
 * Represents a single "node" from "nodes" database table.
 *
 */
class Node extends Entity {

/**
 * Gets node type.
 *
 * As Node Types are not dependent of
 * Nodes (deleting a node_type won't remove all nodes of that type).
 * Some types we found nodes without `node_type`, in that cases, if no node_type
 * is found `--unknow--` will be returned.
 *
 * @return string
 */
	public function getType() {
		$name = $this->node_type->has('name') ? $this->node_type->get('name') : __('--unknow--');
		$name = empty($name) ? __('--unknow--') : $name;
		return $name;
	}

/**
 * Gets node's details page URL.
 *
 * Node's details URL's follows the syntax below:
 *
 *     http://example.com/[node-type-slug]/[node-slug].html
 *
 * Example:
 *
 *     http://example.com/blog-article/my-first-article.html
 *
 * @return string
 */
	public function getUrl() {
		return \Cake\Routing\Router::url("/{$this->node_type_slug}/{$this->slug}.html", true);
	}

/**
 * Gets node's author name.
 *
 * If user is not found (because he/she was removed from the system after content was created)
 * `--unknow--` will be returned.
 *
 * @return string
 */
	public function getAuthorName() {
		$name = $this->author->has('name') ? $this->author->get('name') : __('--unknow--');
		$name = empty($name) ?  __('--unknow--') : $name;
		return $name;
	}
}
