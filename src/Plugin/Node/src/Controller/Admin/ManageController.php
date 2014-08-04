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
use Cake\Error\NotFoundException;
use Locale\Utility\LocaleToolbox;
use Node\Controller\AppController;

/**
 * Node manager controller.
 *
 * Provides full CRUD for nodes.
 */
class ManageController extends AppController {

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
		$this->Breadcrumb->push('/admin/node/manage');
	}

/**
 * Node-type selection screen.
 *
 * User must select which content type wish to create.
 *
 * @return void
 */
	public function create() {
		$this->loadModel('Node.NodeTypes');
		$types = $this->NodeTypes->find()
			->select(['id', 'slug', 'name', 'description'])
			->all();
		$this->set('types', $types);
		$this->Breadcrumb->push('/admin/node/manage');
		$this->Breadcrumb->push([
			['title' => __d('node', 'Create new content'), 'url' => '#']
		]);
	}

/**
 * Shows the "new node" form.
 *
 * @param string $type Node type slug. e.g.: "article", "product-info"
 * @return void
 */
	public function add($type = false) {
		if (!$type) {
			$this->redirect(['plugin' => 'node', 'controller' => 'manage', 'action' => 'create', 'prefix' => 'admin']);
		}

		$this->loadModel('Node.NodeTypes');
		$this->loadModel('Node.Nodes');
		$this->Nodes->unbindComments();
		$type = $this->NodeTypes->find()
			->where(['slug' => $type])
			->first();

		if (!$type) {
			throw new NotFoundException(__d('node', 'The specified content type does not exists.'));
		}

		if ($this->request->data) {
			$data = $this->request->data;
			$data['node_type_slug'] = $type->slug;
			$data['node_type_id'] = $type->id;
			$node = $this->Nodes->newEntity($data);

			if ($this->Nodes->save($node)) {
				$this->alert(__d('node', 'Content created!.'), 'success');
				$this->redirect(['plugin' => 'node', 'controller' => 'manage', 'action' => 'edit', 'prefix' => 'admin', $node->id]);
			} else {
				$this->alert(__d('node', 'Something went wrong, please check your information.'), 'danger');
			}
		} else {
			$node = $this->Nodes->newEntity(['node_type_slug' => $type->slug]);
			$node->setDefaults($type);
		}

		$node = $this->Nodes->attachEntityFields($node);
		$this->set('node', $node);
		$this->set('type', $type);
		$this->set('languages', LocaleToolbox::languagesList());
		$this->Breadcrumb->push('/admin/node/manage');
		$this->Breadcrumb->push([
			['title' => __d('node', 'Create new content'), 'url' => ['plugin' => 'Node', 'controller' => 'manage', 'action' => 'create']],
			['title' => $type->name, 'url' => '#'],
		]);
	}

/**
 * Edit form for the given node.
 *
 * @param integer $id Node ID
 * @param false|integer $revision_id Fill form with node's revision information
 * @return void
 */
	public function edit($id, $revision_id = false) {
		$this->loadModel('Node.Nodes');
		$this->Nodes->unbindComments();

		if ($revision_id && !$this->request->data) {
			$this->loadModel('Node.NodeRevisions');
			$revision = $this->NodeRevisions->find()
				->where(['id' => $revision_id, 'node_id' => $id])
				->first();
			$node = $revision->data;

			if (!empty($node->_fields)) {
				// Merge previous data for each field, we just load the data (metadata keeps to the latests configured).
				$_fieldsRevision = $node->_fields;
				$node = $this->Nodes->attachEntityFields($node);
				$node->_fields = $node->_fields->map(function ($field, $key) use ($_fieldsRevision) {
					$fieldRevision = $_fieldsRevision[$field->name];
					if ($fieldRevision) {
						$field->set('value', $fieldRevision->value);
						$field->set('extra', $fieldRevision->extra);
					}
					return $field;
				});
			}
		} else {
			$node = $this->Nodes->find()
				->where(['id' => $id])
				->contain([
					'Translations',
					'NodeRevisions',
				])
				->first();
		}

		if (!$node) {
			throw new NotFoundException(__d('node', 'The requested page was not found.'));
		}

		if (!empty($this->request->data)) {
			if (!$this->request->data['regenerate_slug']) {
				$this->Nodes->slugConfig(['on' => 'insert']);
			}

			unset($this->request->data['regenerate_slug']);
			$node->set($this->request->data);

			if ($this->Nodes->save($node, ['atomic' => true])) {
				$this->alert(__d('node', 'Information was saved!'), 'success');
				$this->redirect("/admin/node/manage/edit/{$id}");
			} else {
				$this->alert(__d('node', 'Something went wrong, please check your information.'), 'danger');
			}
		}

		$this->set('node', $node);
		$this->set('languages', LocaleToolbox::languagesList());
		$this->Breadcrumb->push('/admin/node/manage');
		$this->Breadcrumb->push(__d('node', 'Editing content'), '#');
	}

/**
 * Deletes the given node by ID.
 *
 * @param integer $node_id
 * @return void
 */
	public function delete($node_id) {
		$this->loadModel('Node.Nodes');
		$node = $this->Nodes->get($node_id);

		if ($this->Nodes->delete($node, ['atomic' => true])) {
			$this->alert(__d('node', 'Content was successfully removed!'), 'success');
		} else {
			$this->alert(__d('node', 'Unable to remove this content, please try again.'), 'danger');
		}

		$this->redirect($this->referer());
	}

/**
 * Removes the given revision of the given node.
 *
 * @param integer $node_id
 * @param integer $revision_id
 * @return void Redirects to previous page
 */
	public function delete_revision($node_id, $revision_id) {
		$this->loadModel('Node.NodeRevisions');
		$revision = $this->NodeRevisions->find()
			->where(['id' => $revision_id, 'node_id' => $node_id])
			->first();

		if ($this->NodeRevisions->delete($revision, ['atomic' => true])) {
			$this->alert(__d('node', 'Revision was successfully removed!'), 'success');
		} else {
			$this->alert(__d('node', 'Unable to remove this revision, please try again.'), 'danger');
		}

		$this->redirect($this->referer());
	}

}
