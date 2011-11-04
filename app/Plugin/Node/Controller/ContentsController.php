<?php
/**
 * Contents Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Plugin.Node.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class ContentsController extends NodeAppController {
    public $name = 'Contents';
    public $uses = array('Node.Node');

    public function admin_index() {
        if (isset($this->data['Node']['update'])) {
            if (isset($this->data['Items']['id'])) {
                $update = (!in_array($this->data['Node']['update'], array('delete', 'clear_cache')));

                switch ($this->data['Node']['update']) {
                    case 'publish':
                        default:
                            $data = array('field' => 'status', 'value' => 1);
                    break;

                    case 'unpublish':
                        $data = array('field' => 'status', 'value' => 0);
                    break;

                    case 'promote':
                        $data = array('field' => 'promote', 'value' => 1);
                    break;

                    case 'demote':
                        $data = array('field' => 'promote', 'value' => 0);
                    break;

                    case 'sticky':
                        $data = array('field' => 'sticky', 'value' => 1);
                    break;

                    case 'unsticky':
                        $data = array('field' => 'sticky', 'value' => 0);
                    break;
                }

                foreach ($this->data['Items']['id'] as $id) {
                    if ($update) { # update node
                        $this->Node->id = $id;
                        $this->Node->saveField($data['field'], $data['value'], false);
                    } else { # delete/clear_cache
                        $this->Node->id = $id;
                        $slug = $this->Node->field('slug');

                        switch ($this->data['Node']['update']) {
                            case 'delete':
                                $this->requestAction("/admin/node/contents/delete/{$slug}");
                            break;

                            case 'clear_cache':
                                if ($slug) {
                                    $this->requestAction("/admin/node/contents/clear_cache/{$slug}");
                                }
                            break;
                        }
                    }
                }
            }

            $this->redirect($this->referer());
        }

        $paginationScope = array();

        if (isset($this->data['Node']['filter']) || $this->Session->check('Node.filter')) {
            if (isset($this->data['Node']['filter']) && empty($this->data['Node']['filter'])) {
                $this->Session->delete('Node.filter');
            } else {
                $filter = isset($this->data['Node']['filter']) ? $this->data['Node']['filter'] : $this->Session->read('Node.filter');

                foreach ($filter as $field => $value) {
                    if ($value !== '') {
                        $field = str_replace('|', '.', $field);
                        $field = strpos($field, 'Node.title') !== false || strpos($field, 'Node.language') !== false ? "{$field} LIKE" : $field;
                        $value = str_replace('*', '%', $value);
                        $paginationScope[$field] = $value;
                    }
                }

                $this->Session->write('Node.filter', $filter);
            }
        }

        $results = $this->paginate('Node', $paginationScope);

        $this->__setLangVar();
        $this->title(__t('Contents'));
        $this->set('results', $results);
        $this->set('types', $this->Node->NodeType->find('list'));
    }

    public function admin_edit($slug = null) {
        $this->Node->recursive = 1;

        $data = $this->Node->findBySlug($slug) or $this->redirect('/admin/node/contents');
        $_data = array();

        if (!empty($this->data)) {
            $_data = $this->data;

            if ($this->Node->saveAll($_data)) {
                $n = $this->Node->read();

                $this->flashMsg(__t('Content has been saved'), 'success');
                $this->redirect('/admin/node/contents/edit/' . $n['Node']['slug']);
            } else {
                $this->flashMsg(__t('Content could not be saved. Please, try again.'), 'error');
            }
        }

        if (empty($data['NodeType']['id'])) {
            $this->flashMsg(__t("<b>Content type not found.</b><br/>You can't edit this undefined type of content."), 'alert');
        } else {
            $this->loadModel('User.Role');
            $this->__setLangVar();

            $this->Node->recursive = 2;
            $this->data = Set::merge((array)$this->Node->findBySlug($slug), $_data);

            $this->set('roles', $this->Role->find('list'));
            $this->set('vocabularies', $this->__typeTerms($this->data['NodeType']));
        }

        $this->setCrumb('/admin/node/contents');
        $this->title(__t('Editing Content'));
    }

    public function admin_create() {
        $types = $this->Node->NodeType->find('all', array('conditions' => array('NodeType.status' => 1)));

        $this->title(__t('Add Content'));
        $this->setCrumb('/admin/node/contents');
        $this->setCrumb(array(__t('Select content type'), ''));
        $this->set('types', $types);
    }

    public function admin_add($node_type_id) {
        $_data = array();

        if (!empty($this->data)) {
            $_data = $this->data;

            $this->Node->Behaviors->attach('Field.Fieldable', array('belongsTo' => 'NodeType-' . $node_type_id));

            if ($this->Node->saveAll($_data, array('validate' => 'first'))) {
                $Node = $this->Node->read();

                $this->flashMsg(__t('Content has been saved'), 'success');
                $this->redirect('/admin/node/contents/edit/' . $Node['Node']['slug']);
            } else {
                $this->flashMsg(__t('Content could not be saved. Please, try again.'), 'error');
            }
        }

        $this->Node->NodeType->bindModel(
            array(
                'hasMany' =>  array(
                    'Field' => array(
                        'className' => 'Field.Field',
                        'foreignKey' => false,
                        'order' => array('ordering' => 'ASC'),
                        'conditions' => array('Field.belongsTo' => "NodeType-{$node_type_id}")
                    )
                )
            )
        );

        $type = $this->Node->NodeType->findById($node_type_id) or $this->redirect('/admin/node/contents');

        // Fix
        foreach ($type['Field'] as &$f) {
            $f['settings'] = is_string($f['settings']) ? unserialize($f['settings']) : $f['settings'];
        }

        // load default options
        $type['Node'] = array(
            'comment' => $type['NodeType']['default_comment'],
            'language' => $type['NodeType']['default_language'],
            'status' => $type['NodeType']['default_status'],
            'promote' => $type['NodeType']['default_promote'],
            'sticky' => $type['NodeType']['default_sticky']
        );
        $this->data = Set::merge($type, $_data);

        $this->loadModel('User.Role');
        $this->__setLangVar();
        $this->set('roles', $this->Role->find('list'));
        $this->set('vocabularies', $this->__typeTerms($type));
        $this->setCrumb('/admin/node/contents');
        $this->title(__t('Add Content'));
    }

    public function admin_delete($slug) {
        $this->Node->recursive = -1;
        $node = $this->Node->findBySlug($slug);

        if (empty($node)) {
            $this->redirect('/admin/node/contents');
        }

        $del = $this->Node->delete($node['Node']['id']);

        if ($del) {
            $this->loadModel('NodesTerms');
            $this->NodesTerms->deleteAll(array('NodesTerms.node_id' => $node['Node']['id']));
        }

        if (isset($this->request->params['requested'])) {
            return $del;
        } else {
            $this->redirect($this->referer());
        }
    }

    public function admin_clear_cache($slug) {
        $del = Cache::delete("node_{$slug}");

        if(isset($this->request->params['requested'])) {
            return $del;
        } else {
            $this->redirect($this->referer());
        }
    }

    private function __setLangVar() {
        $langs = array();

        foreach (Configure::read('Variable.languages') as $l) {
            $langs[$l['Language']['code']] = $l['Language']['native'];
        }

        $this->set('languages', $langs);
    }

    private function __typeTerms($type) {
        if (!isset($type['Vocabulary']) || empty($type['Vocabulary'])) {
            return false;
        }

        $_vocabularies = $type['Vocabulary'];
        $vocabularies = array();

        $this->loadModel('Taxonomy.Term');

        foreach ($_vocabularies as $v) {
            $this->Term->Behaviors->detach('Tree');
            $this->Term->Behaviors->attach('Tree', array('parent' => 'parent_id', 'left' => 'lft', 'right' => 'rght', 'scope' => "Term.vocabulary_id = {$v['id']}" ));

            $terms = array();
            $terms = $this->Term->generateTreeList("Term.vocabulary_id = {$v['id']}", "{n}.Term.id", "{n}.Term.name",'&nbsp;&nbsp;&nbsp;&nbsp;');
            $vocabularies[$v['title']] = $terms;
        }

        return $vocabularies;
    }
}