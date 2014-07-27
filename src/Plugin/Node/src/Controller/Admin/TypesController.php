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

use Cake\Error\NotFoundException;
use Node\Controller\NodeAppController;

/**
 * Controller for Node Types handling.
 *
 */
class TypesController extends NodeAppController {

/**
 * List of registered node-types.
 *
 * @return void
 */
	public function index() {
		$this->loadModel('Node.NodeTypes');
		$types = $this->NodeTypes->find()
			->select(['id', 'slug', 'name', 'description'])
			->where(['status' => 1])
			->all();
		$this->set('types', $types);
		$this->Breadcrumb->push();
	}

	public function edit($slug) {
		$this->loadModel('Node.NodeTypes');
		$type = $this->NodeTypes->find()
			->where(['slug' => $slug, 'status' => 1])
			->first();

		if (!$type) {
			throw new NotFoundException(__d('node', 'Content type was not found!'));
		}

		if ($this->request->data) {

		}

		$this->set('type', $type);
		$this->Breadcrumb->push();
	}

}
