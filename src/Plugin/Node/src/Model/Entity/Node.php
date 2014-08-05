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

use Cake\Error\InternalErrorException;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Node\Model\Entity\NodeType;

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
	public function _getType() {
		$name = $this->node_type->has('name') ? $this->node_type->get('name') : __d('node', '--unknow--');
		$name = empty($name) ? __d('node', '--unknow--') : $name;
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
	public function _getUrl() {
		$url = Router::url('node_details', [
			'node_type_slug' => $this->node_type_slug,
			'node_slug' => $this->slug,
		]);

		return Router::normalize($url);
	}

/**
 * Gets node's author name.
 *
 * If user is not found (because he/she was removed from the system after content was created)
 * `--unknow--` will be returned.
 *
 * @return string
 */
	public function _getAuthorName() {
		$name = $this->author->has('name') ? $this->author->get('name') : __d('node', '--unknow--');
		$name = empty($name) ?  __d('node', '--unknow--') : $name;
		return $name;
	}

/**
 * Set defaults content settings based on parent content type.
 *
 * You can provide a NodeType entity to fetch defaults values.
 * By default if none is provided it automatically fetches the information from
 * the corresponding Content Type.
 * 
 * @param mixed $type False for auto fetch, or a \Node\Model\Entity\NodeType entity to extract information from
 */
	public function setDefaults($type = false) {
		if (!$type) {
			if (!$this->has('node_type_slug') && !$this->has('id')) {
				throw new InternalErrorException(__d('node', "Node::setDefaults() was unable to get Content Type information."));
			}

			if (!$this->has('node_type_slug')) {
				$node_type_slug = TableRegistry::get('Node.Nodes')->find()
					->select(['node_type_slug'])
					->where(['id' => $this->get('id')])
					->first();
				$node_type_slug = $node_type_slug->node_type_slug;
			} else {
				$node_type_slug = $this->get('node_type_slug');
			}

			$type = TableRegistry::get('Node.NodeTypes')->find()
				->where(['slug' => $node_type_slug])
				->first();
		}

		if (!($type instanceof NodeType) || !$type->has('defaults')) {
			throw new InternalErrorException(__d('node', "Node::setDefaults() was unable to get Content Type defaults values."));
		}

		$this->set('language', $type->defaults->language);
		$this->set('comment_status', $type->defaults->comment_status);
		$this->set('status', $type->defaults->promote);
		$this->set('promote', $type->defaults->promote);
		$this->set('sticky', $type->defaults->sticky);
	}

}
