<?php
/**
 * Types Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Node.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
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

        if (!$nodeType || in_array($id, Configure::read('coreNodeTypes'))) {
            $this->redirect('/admin/node/types');
        }

        if ($this->NodeType->delete($id)) {
            $this->flashMsg(__t('Content type has been deleted'), 'success');
        }

        $this->redirect($this->referer());
    }

    //node type display settings
    public function admin_display($typeId, $view_mode = false) {
        if (!$view_mode && !isset($this->data['NodeType']['viewModes'])) {
            $this->redirect("/admin/node/types/display/{$typeId}/default");
        }

        $this->loadModel('Field.Field');

        if (isset($this->data['NodeType']['viewModes'])) { # set available view modes
            $this->Field->setViewModes($this->data['NodeType']['viewModes'], array('Field.belongsTo' => "NodeType-{$typeId}"));
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

        $fields = @Set::sort((array)$fields, '{n}.Field.settings.display.' . $view_mode . '.ordering', 'asc');
        $__viewModes = Set::extract('{n}.Field.settings.display', $fields);
        $viewModes = array();

        foreach ($__viewModes as $key => $vm) {
            $viewModes = array_merge($viewModes, array_keys($vm));
        }

        $nodeType['NodeType']['viewModes'] = array_unique($viewModes);
        $this->data = $nodeType;

        $this->set('result', $fields);
        $this->set('view_mode', $view_mode);
        $this->set('typeId', $typeId);
        $this->setCrumb('/admin/node/types');
        $this->setCrumb(array($nodeType['NodeType']['name'], '/admin/node/types/edit/' . $nodeType['NodeType']['id']));
        $this->setCrumb(array(__t('Display'), ''));
        $this->title(__t('Display Settings'));
    }

    public function admin_field_settings($id) {
        if (isset($this->data['Field'])) {
            $this->loadModel('Field.Field');

            if ($this->Field->save($this->data)) {
                $this->redirect($this->referer());
            }
        }

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

        $this->data = $this->NodeType->Field->findById($id) or $this->redirect('/admin/node/types');
        $ntID = substr($this->data['Field']['belongsTo'], strpos($this->data['Field']['belongsTo'], '-')+1);
        $nodeType = $this->NodeType->findById($ntID) or $this->redirect('/admin/node/types');

        $this->setCrumb('/admin/node/types');
        $this->setCrumb(array($nodeType['NodeType']['name'], '/admin/node/types/edit/' . $nodeType['NodeType']['id']));
        $this->setCrumb(array(__t('Fields'), '/admin/node/types/fields/' . $nodeType['NodeType']['id']));
        $this->title(__t('Field Settings'));
        $this->set('result', $this->data);
    }

    public function admin_field_formatter($id, $view_mode = 'default') {
        $this->loadModel('Field.Field');

        $field = $this->Field->findById($id) or $this->redirect($this->referer());
        $ntID = substr($field['Field']['belongsTo'], strpos($field['Field']['belongsTo'], '-')+1);
        $nodeType = $this->NodeType->findById($ntID) or $this->redirect('/admin/node/types');

        if (isset($this->data['Field'])) {
            if ($this->data['Field']['display_hidden']) {
                $data = $this->data;
                $data['Field']['settings']['display'][$view_mode]['type'] = 'hidden';
                $this->data = $data;
            }

            if ($this->Field->save($this->data)) {
                $this->flashMsg(__t('Field has been saved.'), 'success');
                $this->redirect($this->referer());
            } else {
                $this->flashMsg(__t('Field could not be saved. Please, try again.'), 'error');
            }
        }

        $this->data = $field;

        $this->set('view_mode', $view_mode);
        $this->setCrumb('/admin/node/types');
        $this->setCrumb(array($nodeType['NodeType']['name'], '/admin/node/types/edit/' . $nodeType['NodeType']['id']));
        $this->setCrumb(array(__t('Display'), '/admin/node/types/display/' . $nodeType['NodeType']['id']));
        $this->setCrumb(array(__t('Field display settings'), ''));
        $this->title(__t('Field Display Settings'));
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
            $data = $this->data;
            $data['Field']['name'] = !empty($data['Field']['name']) ? 'field_' . $data['Field']['name'] : '';

            $this->NodeType->Behaviors->attach('Field.Fieldable', array('belongsTo' => "NodeType-{$typeId}"));

            if ($field_id = $this->NodeType->attachFieldInstance($data)) {
                $this->redirect("/admin/node/types/field_settings/{$field_id}");
            }

            $this->flashMsg(__t('Field could not be created. Please, try again.'), 'error');
        }

        $this->set('result', $nodeType);
        $this->set('field_modules', $this->hook('field_info', $this, array('collectReturn' => false)));
        $this->setCrumb('/admin/node/types');
        $this->setCrumb( array($nodeType['NodeType']['name'], '/admin/node/types/edit/' . $nodeType['NodeType']['id']));
        $this->setCrumb( array(__t('Fields'), '/admin/node/types/fields/' . $nodeType['NodeType']['id']));
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