<?php
/**
 * Vocabularies Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Taxonomy.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class VocabulariesController extends TaxonomyAppController {
	public $name = 'Vocabularies';
	public $uses = array('Taxonomy.Vocabulary');
	public $helpers = array('Menu.Menu');

	public function beforeFilter() {
		if ($this->action == 'admin_terms') {
			$this->QuickApps->disableSecurity();
		}

		parent::beforeFilter();
	}

	public function admin_index() {
		$this->setCrumb('/admin/taxonomy/');
		$this->set('results', $this->paginate('Vocabulary'));
	}

	public function admin_add() {
		if (isset($this->data['Vocabulary'])) {
			if ($this->Vocabulary->saveAll($this->data)) {
				$vocabulary = $this->Vocabulary->read();

				$this->flashMsg(__t('Vocabulary has been saved. Now you can add terms.'), 'success');
				$this->redirect("/admin/taxonomy/vocabularies/terms/{$vocabulary['Vocabulary']['slug']}");
			} else {
				$this->flashMsg(__t('Vocabulary could not be saved. Please, try again.'), 'error');
			}
		}

		$types = ClassRegistry::init('Node.NodeType')->find('list');

		$this->setCrumb(
			'/admin/taxonomy/',
			array(__t('Add vocabulary'))
		);
		$this->title(__t('Add Vocabulary'));
	}

	public function admin_move($id, $dir = 'up') {
		$dir = $dir != 'up' ? 'down' : 'up';

		$this->Vocabulary->move($id, $dir);
		$this->redirect($this->referer());
	}

	public function admin_edit($slug) {
		$vocabulary = $this->Vocabulary->findBySlug($slug) or $this->redirect('/admin/taxonomy/');

		if (isset($this->data['Vocabulary']['id'])) {
			if ($this->Vocabulary->save($this->data)) {
				$vocabulary = $this->Vocabulary->read();

				$this->flashMsg(__t('Vocabulary has been saved'), 'success');
				$this->redirect("/admin/taxonomy/vocabularies/edit/{$vocabulary['Vocabulary']['slug']}");
			} else {
				$this->flashMsg(__t('Vocabulary could not be saved. Please, try again.'), 'error');
			}
		}

		$this->data = $vocabulary;
		$types = ClassRegistry::init('Node.NodeType')->find('list');

		$this->setCrumb(
			'/admin/taxonomy/',
			array($vocabulary['Vocabulary']['title'])
		);
		$this->title(__t('Editing Vocabulary "%s"', $vocabulary['Vocabulary']['title']));
		$this->set('types', $types);
	}

	public function admin_delete($id) {
		if ($this->Vocabulary->delete($id)) {
			$this->Vocabulary->Term->Behaviors->detach('Tree');
			$this->Vocabulary->Term->Behaviors->attach('Tree',
				array(
					'parent' => 'parent_id',
					'left' => 'lft',
					'right' => 'rght',
					'scope' => "Term.vocabulary_id = '{$id}'"
				)
			);

			$terms = $this->Vocabulary->Term->find('all', array('order' => 'lft ASC', 'conditions' => array('Term.vocabulary_id' => $id)));

			foreach ($terms as $term) {
				ClassRegistry::init('NodesTerms')->deleteAll(
					array(
						'term_id' => $term['Term']['id']
					)
				);

				$this->Vocabulary->Term->removeFromTree($term['Term']['id'], true);
			}
		}

		$this->redirect($this->referer());
	}

	// vocabulary terms
	public function admin_terms($id) {
		$this->Vocabulary->recursive = -1;
		$vocabulary = $this->Vocabulary->findBySlug($id) or $this->redirect('/admin/taxonomy/');

		if (isset($this->data['Term']['sorting'])) {
			$items = json_decode(trim($this->data['Term']['sorting']));

			$this->Vocabulary->Term->Behaviors->detach('Tree');
			unset($items[0]);

			foreach ($items as $key => &$item) {
				$item->parent_id = $item->parent_id == 'root' ? 0 : (int) $item->parent_id;
				$item->left--;
				$item->right--;
				$data['Term'] = array(
					'id' => $item->item_id,
					'parent_id' => $item->parent_id,
					'lft' => $item->left,
					'rght' => $item->right
				);
				$data = Hash::filter($data);

				if (!empty($data)) {
					$this->Vocabulary->Term->save($data, false);
				}
			}

			die('ok');
		} elseif (isset($this->data['Term']['name'])) {
			// new term
			$data = $this->data;
			$data['Term']['vocabulary_id'] = $vocabulary['Vocabulary']['id'];

			$this->Vocabulary->Term->Behaviors->detach('Tree');
			$this->Vocabulary->Term->Behaviors->attach('Tree', array('parent' => 'parent_id', 'left' => 'lft', 'right' => 'rght', 'scope' => "Term.vocabulary_id = {$vocabulary['Vocabulary']['id']}"));

			if ($this->Vocabulary->Term->save($data)) {
				$this->flashMsg(__t('Term has been created.'), 'success');
				$this->redirect($this->referer());
			} else {
				$this->flashMsg(__t('Term could not be saved. Please, try again.'), 'error');
			}
		}

		$results = $this->Vocabulary->Term->find('threaded',
			array(
				'conditions' => array('Term.vocabulary_id' => $vocabulary['Vocabulary']['id']),
				'order' => 'lft ASC'
			)
		);
		$parents = $this->Vocabulary->Term->generateTreeList("Term.vocabulary_id = '{$vocabulary['Vocabulary']['id']}'", null, null, '&nbsp;&nbsp;|- ');

		$this->set('results', $results);
		$this->set('parents', $parents);
		$this->setCrumb(
			'/admin/taxonomy/',
			array($vocabulary['Vocabulary']['title'], '/admin/taxonomy/vocabularies/edit/' . $vocabulary['Vocabulary']['slug']),
			array(__t('Terms'))
		);
		$this->title(__t('%s Terms', $vocabulary['Vocabulary']['title']));
	}

	public function admin_delete_term($slug) {
		$term = $this->Vocabulary->Term->findBySlug($slug) or $this->redirect('/admin/taxonomy');

		$this->Vocabulary->Term->Behaviors->detach('Tree');
		$this->Vocabulary->Term->Behaviors->attach('Tree',
			array(
				'parent' => 'parent_id',
				'left' => 'lft',
				'right' => 'rght',
				'scope' => "Term.vocabulary_id = '{$term['Term']['vocabulary_id']}'"
			)
		);
		$this->Vocabulary->Term->removeFromTree($term['Term']['id'], true);
		$this->loadModel('NodesTerms');
		$this->NodesTerms->deleteAll(array('NodesTerms.term_id' => $term['Term']['id']));
		$this->redirect($this->referer());
	}

	public function admin_edit_term($slug) {
		$this->Vocabulary->Term->bindModel(array('belongsTo' => array('Vocabulary' => array('className' => 'Texonomy.Vocabulary'))));

		$term = $this->Vocabulary->Term->findBySlug($slug) or $this->redirect('/admin/taxonomy');

		if (isset($this->data['Term'])) {
			if ($this->Vocabulary->Term->save($this->data)) {
				$this->flashMsg(__t('Term has been saved.'), 'success');

				$term = $this->Vocabulary->Term->read();
			} else {
				$this->flashMsg(__t('Term could not be saved. Please, try again.'), 'error');
			}

			$this->redirect("/admin/taxonomy/vocabularies/edit_term/{$term['Term']['slug']}");
		}

		$this->data = $term;

		$this->setCrumb(
			'/admin/taxonomy/',
			array($term['Vocabulary']['title'], '/admin/taxonomy/vocabularies/edit/' . $term['Vocabulary']['slug']),
			array(__t('Terms'), '/admin/taxonomy/vocabularies/terms/' . $term['Vocabulary']['slug']),
			array(__t('Editing term'))
		);
		$this->title(__t('Editing term "%s"', $term['Term']['name']));
	}
}