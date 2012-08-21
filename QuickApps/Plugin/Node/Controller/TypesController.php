<?php
/**
 * Types Controller
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Node.Controller
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class TypesController extends NodeAppController {
	public $name = 'Types';
	public $uses = array('Node.NodeType');

	public function admin_index() {
		$this->set('results', $this->paginate('NodeType'));
		$this->title(__t('Content Types'));
	}

	public function admin_edit($id) {
		if (!empty($this->data['NodeType']['id'])) {
			if ($this->NodeType->save($this->data)) {
				$this->flashMsg(__t('Content type has been saved!'), 'success');
				$this->redirect("/admin/node/types/edit/{$this->data['NodeType']['new_id']}");
			} else {
				$this->flashMsg(__t('Content type could not be saved'), 'error');
			}
		}

		$nodeType = $this->NodeType->findById($id) or $this->redirect('/admin/node/types');
		$nodeType['NodeType']['new_id'] = $nodeType['NodeType']['id'];
		$this->data = $nodeType;

		$this->__setLangVar();
		$this->setCrumb('/admin/node/types');
		$this->title(__t('Editing Type'));
	}

	public function admin_add() {
		if (!empty($this->data['NodeType'])) {
			$data = $this->data;
			$data['NodeType']['status'] = 1;
			$data['NodeType']['module'] = $data['NodeType']['base'] = 'Node';

			if ($this->NodeType->save($data)) {
				$this->redirect('/admin/node/types/fields/' . $this->NodeType->id);
			}
		}

		$this->__setLangVar();
		$this->setCrumb('/admin/node/types');
		$this->title(__t('Add Content Type'));
	}

	public function admin_delete($id) {
		$nodeType = $this->NodeType->findById($id);

		if ($this->NodeType->delete($id)) {
			$this->flashMsg(__t('Content type has been deleted'), 'success');
		}

		$this->redirect($this->referer());
	}

	// node type display settings
	public function admin_display($typeId, $display = false) {
		if (!$display && !isset($this->data['NodeType']['displayModes'])) {
			$this->redirect("/admin/node/types/display/{$typeId}/default");
		}

		$this->loadModel('Field.Field');

		if (isset($this->data['NodeType']['displayModes'])) {
			// set view mode available
			$this->Field->setViewModes($this->data['NodeType']['displayModes'], array('Field.belongsTo' => "NodeType-{$typeId}"));
			$this->redirect($this->referer());
		}

		$this->NodeType->recursive = -1;
		$nodeType = $this->NodeType->findById($typeId) or $this->redirect('/admin/node/types');
		$fields = $this->Field->find('all',
			array(
				'conditions' => array(
					'Field.belongsTo' => "NodeType-{$nodeType['NodeType']['id']}"
				)
			)
		);

		$fields = @Hash::sort((array)$fields, '{n}.Field.settings.display.' . $display . '.ordering', 'asc');
		$__displayModes = (array)Hash::extract($fields, '{n}.Field.settings.display');
		$displayModes = array();

		foreach ($__displayModes as $key => $vm) {
			$displayModes = array_merge($displayModes, array_keys($vm));
		}

		$nodeType['NodeType']['displayModes'] = array_unique($displayModes);
		$this->data = $nodeType;

		$this->set('result', $fields);
		$this->set('display', $display);
		$this->set('typeId', $typeId);
		$this->setCrumb(
			'/admin/node/types',
			array($nodeType['NodeType']['name'], '/admin/node/types/edit/' . $nodeType['NodeType']['id']),
			array(__t('Display'))
		);
		$this->title(__t('Display Settings'));
	}

	public function admin_field_settings($id) {
		$this->NodeType->bindModel(
			array(
				'hasMany' => array(
					'Field' => array(
						'className' => 'Field.Field',
						'foreignKey' => false,
						'conditions' => array('Field.belongsTo' => 'Node') // bridge
					)
				)
			)
		);

		if (isset($this->data['Field'])) {
			if ($this->NodeType->Field->save($this->data)) {
				$this->flashMsg(__t('Field has been saved'));
				$this->redirect($this->referer());
			} else {
				$this->flashMsg(__t('Field could not be saved'), 'error');
			}
		}

		$this->data = $this->NodeType->Field->findById($id) or $this->redirect('/admin/node/types');
		$ntID = substr($this->data['Field']['belongsTo'], strpos($this->data['Field']['belongsTo'], '-')+1);
		$nodeType = $this->NodeType->findById($ntID) or $this->redirect('/admin/node/types');

		$this->setCrumb(
			'/admin/node/types',
			array($nodeType['NodeType']['name'], '/admin/node/types/edit/' . $nodeType['NodeType']['id']),
			array(__t('Fields'), '/admin/node/types/fields/' . $nodeType['NodeType']['id'])
		);
		$this->title(__t('Field Settings'));
		$this->set('result', $this->data);
	}

	public function admin_field_formatter($id) {
		$this->loadModel('Field.Field');

		$display = isset($this->request->params['named']['display']) ? $this->request->params['named']['display'] : false;
		$displayModes = array_keys(QuickApps::displayModes('Node'));

		if (!in_array($display, $displayModes)) {
			$this->redirect($this->referer());
		}

		$field = $this->Field->findById($id) or $this->redirect($this->referer());
		$ntID = substr($field['Field']['belongsTo'], strpos($field['Field']['belongsTo'], '-')+1);
		$nodeType = $this->NodeType->findById($ntID) or $this->redirect('/admin/node/types');

		if (isset($this->data['Field'])) {
			if ($this->Field->save($this->data)) {
				$this->flashMsg(__t('Field has been saved.'), 'success');
				$this->redirect($this->referer());
			} else {
				$this->flashMsg(__t('Field could not be saved. Please, try again.'), 'error');
			}
		} else {
			$this->data = $field;
		}

		$this->setCrumb(
			'/admin/node/types',
			array($nodeType['NodeType']['name'], '/admin/node/types/edit/' . $nodeType['NodeType']['id']),
			array(__t('Display'), '/admin/node/types/display/' . $nodeType['NodeType']['id']),
			array(__t('Field display settings'))
		);
		$this->title(__t('Field Display Settings'));
		$this->set('display', $display);
	}

	public function admin_fields($typeId = false) {
		$this->NodeType->bindModel(
			array(
				'hasMany' => array(
					'Field' => array(
						'className' => 'Field.Field',
						'foreignKey' => false,
						'order' => array('ordering' => 'ASC'),
						'conditions' => array('Field.belongsTo' => "NodeType-{$typeId}") // bridge
					)
				)
			)
		);

		$nodeType = $this->NodeType->findById($typeId) or $this->redirect('/admin/node/types');

		if (isset($this->data['Field'])) {
			$this->NodeType->Behaviors->attach('Field.Fieldable', array('belongsTo' => "NodeType-{$typeId}"));

			if ($field_id = $this->NodeType->attachFieldInstance($this->data)) {
				$this->redirect("/admin/node/types/field_settings/{$field_id}");
			}

			$this->flashMsg(__t('Field could not be created. Please, try again.'), 'error');
		}

		$field_modules = QuickApps::field_info();

		foreach ($field_modules as $key => $field) {
			if (isset($field['max_instances']) &&
				$field['max_instances'] === 0
			) {
				unset($field_modules[$key]);
			}
		}

		$this->set('result', $nodeType);
		$this->set('field_modules', $field_modules);
		$this->setCrumb(
			'/admin/node/types',
			array($nodeType['NodeType']['name'], '/admin/node/types/edit/' . $nodeType['NodeType']['id']),
			array(__t('Fields'), '/admin/node/types/fields/' . $nodeType['NodeType']['id'])
		);
		$this->title(__t('Fields'));
	}

	private function __setLangVar() {
		$langs = array();

		foreach (Configure::read('Variable.languages') as $l) {
			$langs[$l['Language']['code']] = $l['Language']['native'];
		}

		$this->set('languages', $langs);
	}
}