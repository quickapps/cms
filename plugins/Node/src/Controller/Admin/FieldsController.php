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

use Cake\Core\Configure;
use Cake\Routing\Router;
use Field\Controller\FieldUIControllerTrait;
use Node\Controller\AppController;

/**
 * Controller for Field UI Manager.
 *
 * Allows to attach, detach and configure Fields to `Node Types`.
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
    protected $_manageTable = 'nodes:';

    /**
     * Constructor.
     *
     * We tweak Field UI here, as nodes are polymorphic we need to dynamically change
     * `$_manageTable` property according to `node_type`.
     *
     * @param \Cake\Network\Request $request Request object for this controller. Can be
     *  null for testing, but expect that features that use the request parameters
     *  will not work.
     * @param \Cake\Network\Response $response Response object for this controller.
     */
    public function __construct($request = null, $response = null)
    {
        parent::__construct($request, $response);
        $validTypes = quickapps('node_types');

        if (!isset($request->query['type']) ||
            !in_array($request->query['type'], $validTypes)
        ) {
            $this->redirect(['plugin' => 'System', 'controller' => 'dashboard', 'prefix' => 'admin']);
        } else {
            // allows to manage polymorphic entities
            $this->_manageTable .= $request->query['type'];

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
    public function beforeRender(\Cake\Event\Event $event)
    {
        $this->_beforeRender($event);
        $this->loadModel('Node.NodeTypes');
        $this->Breadcrumb->push('/admin/node/types');
        $nodeType = $this->NodeTypes->find()
            ->where(['slug' => $this->request->query['type']])
            ->first();

        switch ($this->request->action) {
            case 'index':
                $this->Breadcrumb
                    ->push($nodeType->name, '#')
                    ->push(__d('node', 'Fields'), ['plugin' => 'Node', 'controller' => 'fields', 'action' => 'index', 'prefix' => 'admin']);
                break;

            case 'configure':
                $this->Breadcrumb
                    ->push($nodeType->name, '#')
                    ->push(__d('node', 'Fields'), ['plugin' => 'Node', 'controller' => 'fields', 'action' => 'index', 'prefix' => 'admin'])
                    ->push(__d('node', 'Configure Field {0}', $this->viewVars['instance']->label), '#');
                break;

            case 'attach':
                $this->Breadcrumb
                    ->push($nodeType->name, '#')
                    ->push(__d('node', 'Attach New Field'), '');
                break;

            case 'view_mode_list':
                $this->Breadcrumb
                    ->push($nodeType->name, '#')
                    ->push(__d('node', '{0} View Mode', $this->viewVars['viewModeInfo']['name']), '');
                break;

            case 'view_mode_edit':
                $this->Breadcrumb
                    ->push($nodeType->name, '#')
                    ->push(__d('node', '{0} View Mode', $this->viewVars['viewModeInfo']['name']), ['plugin' => 'Node', 'controller' => 'fields', 'action' => 'view_mode_list', 'prefix' => 'admin', $this->viewVars['viewMode']])
                    ->push(__d('node', 'Field: {0}', $this->viewVars['instance']->label), '');
                break;
        }
    }
}
