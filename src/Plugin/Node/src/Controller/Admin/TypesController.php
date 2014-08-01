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
 * Controller for Node Types handling.
 *
 * Provides full CRUD for content types.
 */
class TypesController extends AppController {

/**
 * List of registered node-types.
 *
 * @return void
 */
	public function index() {
		$this->loadModel('Node.NodeTypes');
		$types = $this->NodeTypes->find()
			->select(['id', 'slug', 'name', 'description'])
			->all();
		$this->set('types', $types);
		$this->Breadcrumb->push('/admin/node/types');
	}

/**
 * Edit content type settings.
 *
 * @return void
 */
	public function edit($slug) {
		$this->loadModel('Node.NodeTypes');
		$type = $this->NodeTypes->find()
			->where(['slug' => $slug])
			->first();

		if (!$type) {
			throw new NotFoundException(__d('node', 'Content type was not found!'));
		}

		if ($this->request->data) {
			$type->set($this->request->data);

			if ($this->NodeTypes->save($type)) {
				$this->alert(__d('node', 'Content type updated!'));
				$this->redirect(['plugin' => 'Node', 'controller' => 'types', 'action' => 'edit', $type->slug]);
			} else {
				$this->alert(__d('node', 'Content type could not be updated, check your information.'), 'danger');
			}
		}

		$this->set('type', $type);
		$this->set('languages', LocaleToolbox::languagesList());
		$this->Breadcrumb->push('/admin/node/types');
		$this->Breadcrumb->push(__d('node', 'Editing "%s" Content Type', $type->name), '');
	}

/**
 * Remove content content type.
 *
 * All existing contents will not be removed.
 *
 * @return void
 */
	public function delete($slug) {
		$this->loadModel('Node.NodeTypes');
		$type = $this->NodeTypes->find()
			->where(['slug' => $slug])
			->first();

		if (!$type) {
			throw new NotFoundException(__d('node', 'Content type was not found!'));
		}

		if ($this->NodeTypes->delete($type)) {
			$this->alert(__d('node', 'Content was deleted!'));
		} else {
			$this->alert(__d('node', 'Content type could not be deleted, please try again.'), 'danger');
		}

		$this->redirect($this->referer());
	}

}
