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
namespace Content\Controller\Admin;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;
use Content\Controller\AppController;
use Field\Controller\FieldUIControllerTrait;

/**
 * Controller for Field UI Manager.
 *
 * Allows to attach, detach and configure Fields to `Content Types`.
 */
class FieldsController extends AppController
{

    use FieldUIControllerTrait {
        beforeRender as protected _beforeRender;
    }

    /**
     * Name of the table managed by Field UI API.
     *
     * @var string
     */
    protected $_manageTable = 'Content.Contents';

    /**
     * Name of the bundle within the table being managed.
     *
     * @var string
     */
    protected $_bundle = '';

    /**
     * Constructor.
     *
     * We tweak Field UI here, as contents are polymorphic we need to dynamically
     * change `$_manageTable` property according to `content_type`.
     *
     * @param \Cake\Network\Request $request Request object for this controller. Can
     *  be null for testing, but expect that features that use the request
     *  parameters will not work.
     * @param \Cake\Network\Response $response Response object for this controller.
     */
    public function __construct($request = null, $response = null)
    {
        parent::__construct($request, $response);
        $validTypes = quickapps('content_types');

        if (!isset($request->query['type']) ||
            !in_array($request->query['type'], $validTypes)
        ) {
            $this->redirect(['plugin' => 'System', 'controller' => 'dashboard', 'prefix' => 'admin']);
        } else {
            // allows to manage polymorphic entities
            $this->_bundle = $request->query['type'];

            // Make $_GET['type'] persistent
            Router::addUrlFilter(function ($params, $request) {
                if (isset($request->query['type'])) {
                    $params['type'] = $request->query['type'];
                }

                return $params;
            });
        }
    }

    /**
     * Before every action of this controller.
     *
     * We sets appropriate breadcrumbs based on current action being requested.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @return void
     */
    public function beforeRender(Event $event)
    {
        $this->_beforeRender($event);
        $this->loadModel('Content.ContentTypes');
        $this->Breadcrumb->push('/admin/content/types');
        $contentType = $this->ContentTypes
            ->find()
            ->where(['slug' => $this->request->query['type']])
            ->first();

        switch ($this->request->action) {
            case 'index':
                $this->Breadcrumb
                    ->push('/admin/content/manage')
                    ->push(__d('content', 'Content Types'), '/admin/content/types')
                    ->push(__d('content', 'Type "{0}"', $contentType->name), '/admin/content/types/edit/' . $contentType->slug)
                    ->push(__d('content', 'Fields'), ['plugin' => 'Content', 'controller' => 'fields', 'action' => 'index', 'prefix' => 'admin']);
                break;

            case 'configure':
                $this->Breadcrumb
                    ->push('/admin/content/manage')
                    ->push(__d('content', 'Content Types'), '/admin/content/types')
                    ->push(__d('content', 'Type "{0}"', $contentType->name), '/admin/content/types/edit/' . $contentType->slug)
                    ->push(__d('content', 'Fields'), ['plugin' => 'Content', 'controller' => 'fields', 'action' => 'index', 'prefix' => 'admin'])
                    ->push(__d('content', 'Configure Field "{0}"', $this->viewVars['instance']->label), '#');
                break;

            case 'attach':
                $this->Breadcrumb
                    ->push('/admin/content/manage')
                    ->push(__d('content', 'Content Types'), '/admin/content/types')
                    ->push(__d('content', 'Type "{0}"', $contentType->name), '/admin/content/types/edit/' . $contentType->slug)
                    ->push(__d('content', 'Attach New Field'), '');
                break;

            case 'viewModeList':
                $this->Breadcrumb
                    ->push('/admin/content/manage')
                    ->push(__d('content', 'Content Types'), '/admin/content/types')
                    ->push(__d('content', 'Type "{0}"', $contentType->name), '/admin/content/types/edit/' . $contentType->slug)
                    ->push(__d('content', 'View Mode "{0}"', $this->viewVars['viewModeInfo']['name']), '');
                break;

            case 'viewModeEdit':
                $this->Breadcrumb
                    ->push('/admin/content/manage')
                    ->push(__d('content', 'Content Types'), '/admin/content/types')
                    ->push(__d('content', 'Type "{0}"', $contentType->name), '/admin/content/types/edit/' . $contentType->slug)
                    ->push(__d('content', 'View Mode "{0}"', $this->viewVars['viewModeInfo']['name']), ['plugin' => 'Content', 'controller' => 'fields', 'action' => 'view_mode_list', 'prefix' => 'admin', $this->viewVars['viewMode']])
                    ->push(__d('content', 'Field: {0}', $this->viewVars['instance']->label), '');
                break;
        }
    }
}
