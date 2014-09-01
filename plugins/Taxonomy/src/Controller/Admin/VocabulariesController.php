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
namespace Taxonomy\Controller\Admin;

use Taxonomy\Controller\AppController;

/**
 * Vocabularies manager controller.
 *
 * Allow CRUD for vocabularies.
 */
class VocabulariesController extends AppController {

/**
 * Shows a list of all vocabularies.
 *
 * @return void
 */
	public function index() {
		$this->loadModel('Taxonomy.Vocabularies');
		$vocabularies = $this->Vocabularies
			->find()
			->order(['ordering' => 'ASC'])
			->all();

		$this->set(compact('vocabularies'));
		$this->Breadcrumb
			->push('/admin/system/structure')
			->push(__d('taxonomy', 'Taxonomy'), '/admin/taxonomy/manage')
			->push(__d('taxonomy', 'Vocabularies'), '#');
	}

/**
 * Adds a new menu.
 *
 * @return void
 */
	public function add() {
		$this->loadModel('Taxonomy.Vocabularies');
		$vocabulary = $this->Vocabularies->newEntity();

		if ($this->request->data) {
			$vocabulary = $this->Vocabularies->patchEntity($vocabulary, $this->request->data, [
				'fieldList' => [
					'name',
					'description',
				],
			]);

			if ($this->Vocabularies->save($vocabulary, ['atomic' => true])) {
				$this->Flash->success(__d('taxonomy', 'Vocabulary has been created, now you can start adding terms!'));
				$this->redirect(['plugin' => 'Taxonomy', 'controller' => 'terms', 'action' => 'add', $vocabulary->id]);
			} else {
				$this->Flash->danger(__d('taxonomy', 'Vocabulary could not be created, please check your information'));
			}
		}

		$this->set('vocabulary', $vocabulary);
		$this->Breadcrumb
			->push('/admin/system/structure')
			->push(__d('taxonomy', 'Taxonomy'), '/admin/taxonomy/manage')
			->push(__d('taxonomy', 'Vocabularies'), ['plugin' => 'Taxonomy', 'controller' => 'vocabularies', 'action' => 'index'])
			->push(__d('taxonomy', 'Crating new vocabulary'), '#');
	}

/**
 * Edits the given vocabulary by ID.
 *
 * @param integer $id Vocabulary's ID
 * @return void
 */
	public function edit($id) {
		$this->loadModel('Taxonomy.Vocabularies');
		$vocabulary = $this->Vocabularies->get($id);

		if ($this->request->data) {
			$vocabulary = $this->Vocabularies->patchEntity($vocabulary, $this->request->data, [
				'fieldList' => [
					'name',
					'description',
				],
			]);

			if ($this->Vocabularies->save($vocabulary, ['atomic' => true])) {
				$this->Flash->success(__d('taxonomy', 'Vocabulary has been saved!'));
				$this->redirect($this->referer());
			} else {
				$this->Flash->danger(__d('taxonomy', 'Vocabulary could not be saved, please check your information'));
			}
		}

		$this->set('vocabulary', $vocabulary);
		$this->Breadcrumb
			->push('/admin/system/structure')
			->push(__d('taxonomy', 'Taxonomy'), '/admin/taxonomy/manage')
			->push(__d('taxonomy', 'Vocabularies'), ['plugin' => 'Taxonomy', 'controller' => 'vocabularies', 'action' => 'index'])
			->push(__d('taxonomy', 'Editing vocabulary'), '#');
	}

/**
 * Removes the given vocabulary by ID.
 *
 * @param integer $id Vocabulary's ID
 * @return void Redirects to previous page
 */
	public function delete($id) {
		$this->loadModel('Taxonomy.Vocabularies');
		$vocabulary = $this->Vocabularies->get($id, [
			'conditions' => [
				'locked' => 0
			]
		]);

		if ($this->Vocabularies->delete($vocabulary)) {
			$this->Flash->success(__d('taxonomy', 'Vocabulary has been successfully deleted!'));
		} else {
			$this->Flash->danger(__d('taxonomy', 'Vocabulary could not be deleted, please try again'));
		}

		$this->redirect($this->referer());
	}

}
