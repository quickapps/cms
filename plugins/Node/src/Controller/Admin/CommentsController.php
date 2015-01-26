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
namespace Node\Controller\Admin;

use Cake\Routing\Router;
use Comment\Controller\CommentUIControllerTrait;
use Comment\Model\Entity\Comment;
use Node\Controller\AppController;

/**
 * Controller for Field UI Manager.
 *
 * Allows to approved, detach and configure Fields to `Node Types`.
 */
class CommentsController extends AppController
{

    use CommentUIControllerTrait {
        beforeRender as protected _beforeRender;
    }

    /**
     * Name of the table managed by Comment UI API.
     *
     * @var string
     */
    protected $_manageTable = 'nodes';

    /**
     * Renders the description of the entity to which comment is attached to.
     *
     * @param \Comment\Model\Entity\Comment $comment Comment entity
     * @return string
     */
    protected function _inResponseTo(Comment $comment)
    {
        $this->loadModel('Node.Nodes');
        $this->Nodes->unbindFieldable();
        $node = $this->Nodes->get($comment->entity_id);

        if ($node) {
            $out = __d('node', '<a href="{0}" target="_blank">{1}</a>', Router::url(['plugin' => 'Node', 'controller' => 'manage', 'action' => 'edit', $node->id]), $node->title);
            $out .= '<br />';
            $out .= __d('node', '<a href="{0}" target="_blank">{1}</a>', Router::url($node->url), 'View content');
            return $out;
        }

        return __d('node', '-- Unknow --');
    }

    /**
     * Before every action of this controller.
     *
     * We sets appropriate breadcrumbs based on current action being requested.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @return void
     */
    public function beforeRender(\Cake\Event\Event $event)
    {
        $this->_beforeRender($event);
        $this->Breadcrumb
            ->push('/admin/node/manage')
            ->push(__d('node', 'Comments'), '#');
    }
}
