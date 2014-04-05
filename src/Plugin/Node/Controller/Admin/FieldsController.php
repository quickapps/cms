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
namespace Node\Controller\Admin;

use Cake\Core\Configure;
use Cake\Routing\Router;
use Field\Utility\FieldUIControllerTrait;
use Node\Controller\NodeAppController;

/**
 * Controller for Field UI Manager.
 *
 * Allows to attach, detach and configure Fields to `Node Types`.
 */
class FieldsController extends NodeAppController {

	use FieldUIControllerTrait;

/**
 * Name of the table managed by Field UI API.
 *
 * @var string
 */
	protected $_manageTable = 'nodes_';

/**
 * Constructor.
 *
 * We tweak Field UI here, as nodes are polymorphic we need to dynamically change
 * `$_manageTable` property according to `node_type`.
 *
 * @param \Cake\Network\Request $request Request object for this controller. Can be null for testing,
 *  but expect that features that use the request parameters will not work.
 * @param \Cake\Network\Response $response Response object for this controller.
 */
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
		$validTypes = Configure::read('QuickApps._snapshot.node_types');

		if (
			!isset($request->query['type']) ||
			!in_array($request->query['type'], $validTypes)
		) {
			$this->redirect(['plugin' => 'system', 'controller' => 'dashboard', 'prefix' => 'admin']);
		}

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
