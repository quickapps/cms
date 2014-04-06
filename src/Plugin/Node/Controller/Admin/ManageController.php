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

use Node\Controller\NodeAppController;

/**
 * Node manager controller.
 *
 * Allow CRUD for nodes.
 */
class ManageController extends NodeAppController {

/**
 * Shows a list of all the nodes.
 *
 * @return void
 */
	public function index() {
		$this->loadModel('Node.Nodes');
		$nodes = $this->Nodes->find('threaded')
			->contain(['NodeTypes', 'Author'])
			->all();
		$this->set('nodes', $nodes);
	}

/**
 * Node type selection screen.
 *
 * User must select which content type wish to create.
 *
 * @return void
 */
	public function create() {
		$this->loadModel('Node.NodeTypes');
		$types = $this->NodeTypes->find()
			->select(['id', 'slug', 'name', 'description'])
			->where(['status' => 1])
			->all();
		$this->set('types', $types);
	}

/**
 * Shows the "new node" form.
 *
 * @param string $type Node type slug. e.g.: "article"
 * @return void
 */
	public function add($type) {
		$this->loadModel('Node.NodeTypes');
		$this->loadModel('Node.Nodes');
		$this->Nodes->unbindComments();

		$type = $this->NodeTypes->find()
			->where(['slug' => $type])
			->first();

		if (!$type) {
			throw new \Cake\Error\NotFoundException(__('The requested page was not found.'));
		}

		$node = $this->Nodes->newEntity(['node_type_slug' => $type->slug]);

		if ($this->request->data) {
			$node = $this->Nodes->newEntity($this->request->data);
			$node->set('node_type_slug', $type->slug);
			$node->set('node_type_id', $type->id);

			if ($this->Nodes->save($node)) {
				$this->alert(__d('node', 'Content created!.'), 'success');
				$this->redirect(['plugin' => 'node', 'controller' => 'manage', 'action' => 'edit', 'prefix' => 'admin', $node->id]);
			} else {
				$this->alert(__d('node', 'Something went wrong, please check your information.'), 'danger');
			}
		}

		$this->_setLanguages();
		$this->set('node', $this->Nodes->attachEntityFields($node));
		$this->set('type', $type);
	}

/**
 * Edit form for the given node.
 *
 * @param integer $id Node ID
 * @param false|integer $revision_id Fill form with revision information
 * @return void
 */
	public function edit($id, $revision_id = false) {
		$this->loadModel('Node.Nodes');

		if ($revision_id && !$this->request->data) {
			$this->loadModel('Node.NodeRevisions');
			$node = $this->NodeRevisions->find()
				->where(['id' => $revision_id, 'node_id' => $id])
				->first();
			$node = $node ? unserialize($node->data) : false;
		} else {
			$node = $this->Nodes->find()
				->where(['id' => $id])
				->first();
		}

		if (!$node) {
			throw new \Cake\Error\NotFoundException(__('The requested page was not found.'));
		}

		if ($this->request->data) {
			if (!$this->request->data['regenerate_slug']) {
				$this->Nodes->slugOn('insert');
			}

			unset($this->request->data['regenerate_slug']);
			$node->set($this->request->data);

			if ($this->Nodes->save($node, ['atomic' => true])) {
				$this->alert('Information was saved!', 'success');
				$this->redirect(['plugin' => 'node', 'controller' => 'manage', 'action' => 'edit', 'prefix' => 'admin', $id]);
			} else {
				$this->alert('Something went wrong, please check your information.', 'danger');
			}
		}

		$this->_setLanguages();
		$this->set('node', $node);
	}

/**
 * Removes the given revision of the given node.
 *
 * @param string $node_slug
 * @param integer $revision_id
 * @return void
 */
	public function delete_revision($node_slug, $revision_id) {
	}

/**
 * Sets a view variable holding a list of available languages.
 * Useful when rendering select boxes in node's edit forms.
 *
 * @return void
 */
	protected function _setLanguages() {
		$languages = [];

		foreach (\Cake\Core\Configure::read('QuickApps.languages') as $code => $data) {
			$languages[$code] = $data['native'];
		}

		$this->set('languages', $languages);
	}

}
