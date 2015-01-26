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
namespace Node\Model\Entity;

use Cake\Error\FatalErrorException;
use Cake\I18n\I18n;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Node\Model\Entity\NodeType;
use User\Model\Entity\User;

/**
 * Represents a single "node" within "nodes" table.
 *
 */
class Node extends Entity
{

    /**
     * Gets node type.
     *
     * As Node Types are not dependent of Nodes (deleting a node_type won't remove
     * all nodes of that type). Some types we found nodes without `node_type`, in
     * that cases, if no node_type is found `--unknow--` will be returned.
     *
     * @return string
     */
    protected function _getType()
    {
        $name = __d('node', '(unknown)');
        if ($this->has('node_type') && $this->node_type->has('name')) {
            $name = $this->node_type->get('name');
        }
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
    protected function _getUrl()
    {
        $url = Router::getRequest()->base;

        if (option('url_locale_prefix')) {
            $url .= '/' . I18n::defaultLocale();
        }

        $url .= "/{$this->node_type_slug}/{$this->slug}.html";

        return Router::normalize($url);
    }

    /**
     * Gets node's author as an User entity.
     *
     * @return \User\Model\Entity\User
     */
    protected function _getAuthor()
    {
        if ($this->has('user')) {
            return $this->get('user');
        } elseif (!empty($this->created_by)) {
            $user = TableRegistry::get('User.Users')
                ->find()
                ->where(['id' => $this->created_by])
                ->first();

            if ($user) {
                return $user;
            }
        }

        return new User([
            'username' => __d('node', 'unknown'),
            'name' => __d('node', 'Unknown'),
            'web' => __d('node', '(no website)'),
            'email' => __d('node', 'Unknown'),
        ]);
    }

    /**
     * Set defaults content settings based on parent content type.
     *
     * You can provide a NodeType entity to fetch defaults values. By default if none
     * is provided it automatically fetches the information from the corresponding
     * Content Type.
     *
     * @param bool|\Node\Model\Entity\NodeType $type False for auto fetch or a
     *  NodeType entity to extract information from
     * @return void
     * @throws Cake\Error\FatalErrorException When content type was not found for
     *  this content node.
     */
    public function setDefaults($type = false)
    {
        if (!$type) {
            if (!$this->has('node_type_slug') && !$this->has('id')) {
                throw new FatalErrorException(__d('node', "Node::setDefaults() was unable to get Content Type information."));
            }

            if (!$this->has('node_type_slug')) {
                $nodeTypeSlug = TableRegistry::get('Node.Nodes')->find()
                    ->select(['node_type_slug'])
                    ->where(['id' => $this->get('id')])
                    ->first();
                $nodeTypeSlug = $nodeTypeSlug->node_type_slug;
            } else {
                $nodeTypeSlug = $this->get('node_type_slug');
            }

            $type = TableRegistry::get('Node.NodeTypes')->find()
                ->where(['slug' => $nodeTypeSlug])
                ->first();
        }

        if (!($type instanceof NodeType) || !$type->has('defaults')) {
            throw new FatalErrorException(__d('node', "Node::setDefaults() was unable to get Content Type defaults values."));
        }

        $this->set('language', $type->defaults['language']);
        $this->set('comment_status', $type->defaults['comment_status']);
        $this->set('status', $type->defaults['status']);
        $this->set('promote', $type->defaults['promote']);
        $this->set('sticky', $type->defaults['sticky']);
    }
}
