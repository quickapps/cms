<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Node\Controller\Admin;

use Cake\Network\Exception\NotFoundException;
use Locale\Utility\LocaleToolbox;
use Node\Controller\AppController;

/**
 * Node manager controller.
 *
 * Provides full CRUD for nodes.
 */
class ManageController extends AppController
{

    /**
     * An array containing the names of helpers controllers uses.
     *
     * @var array
     */
    public $helpers = [
        'Paginator' => [
            'className' => 'QuickApps\View\Helper\PaginatorHelper',
            'templates' => 'System.paginator-templates',
        ],
    ];

    /**
     * Shows a list of all the nodes.
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Node.Nodes');
        $nodes = $this->Nodes->find()->contain(['NodeTypes', 'Author']);

        if (!empty($this->request->query['filter'])) {
            $this->Nodes->search($this->request->query['filter'], $nodes);
        }

        $this->set('nodes', $this->paginate($nodes));
        $this->Breadcrumb->push('/admin/node/manage');
    }

    /**
     * Node-type selection screen.
     *
     * User must select which content type wish to create.
     *
     * @return void
     */
    public function create()
    {
        $this->loadModel('Node.NodeTypes');
        $types = $this->NodeTypes->find()
            ->select(['id', 'slug', 'name', 'description'])
            ->all();
        $this->set('types', $types);
        $this->Breadcrumb
            ->push('/admin/node/manage')
            ->push(__d('node', 'Create new content'), '');
    }

    /**
     * Shows the "new node" form.
     *
     * @param string $type Node type slug. e.g.: "article", "product-info"
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When content type was not
     *  found
     */
    public function add($type = false)
    {
        if (!$type) {
            $this->redirect(['plugin' => 'Node', 'controller' => 'manage', 'action' => 'create', 'prefix' => 'admin']);
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
                $this->Flash->success(__d('node', 'Content created!.'));
                $this->redirect(['plugin' => 'Node', 'controller' => 'manage', 'action' => 'edit', 'prefix' => 'admin', $node->id]);
            } else {
                $this->Flash->danger(__d('node', 'Something went wrong, please check your information.'));
            }
        } else {
            $node = $this->Nodes->newEntity(['node_type_slug' => $type->slug]);
            $node->setDefaults($type);
            $node->set('node_type', $type);
        }

        $node = $this->Nodes->attachEntityFields($node);
        $languages = LocaleToolbox::languagesList();
        $roles = $this->Nodes->Roles->find('list');

        $this->set(compact('node', 'type', 'languages', 'roles'));
        $this->Breadcrumb
            ->push('/admin/node/manage')
            ->push(__d('node', 'Create new content'), ['plugin' => 'Node', 'controller' => 'manage', 'action' => 'create'])
            ->push($type->name, '');
    }

    /**
     * Edit form for the given node.
     *
     * @param int $id Node's ID
     * @param false|int $revisionId Fill form with node's revision information
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When content type, or when
     *  content node was not found
     */
    public function edit($id, $revisionId = false)
    {
        $this->loadModel('Node.Nodes');
        $this->Nodes->unbindComments();
        $node = false;

        if ($revisionId && !$this->request->data) {
            $this->loadModel('Node.NodeRevisions');
            $revision = $this->NodeRevisions->find()
                ->where(['id' => $revisionId, 'node_id' => $id])
                ->first();

            if ($revision) {
                $node = $revision->data;

                if (!empty($node->_fields)) {
                    // Merge previous data for each field, we just load the data (metadata keeps to the latests configured).
                    $_fieldsRevision = $node->_fields;
                    $node = $this->Nodes->attachEntityFields($node);
                    $node->_fields = $node->_fields->map(function ($field, $key) use ($_fieldsRevision) {
                        $fieldRevision = $_fieldsRevision[$field->name];
                        if ($fieldRevision) {
                            $field->set('value', $fieldRevision->value);
                            $field->set('raw', $fieldRevision->raw);
                        }
                        return $field;
                    });
                }
            }
        } else {
            $node = $this->Nodes->find()
                ->where(['Nodes.id' => $id])
                ->contain([
                    'Roles',
                    'Translations',
                    'NodeRevisions',
                    'NodeTypes',
                    'TranslationOf',
                ])
                ->first();
        }

        if (!$node || empty($node->node_type)) {
            throw new NotFoundException(__d('node', 'The requested page was not found.'));
        }

        if (!empty($this->request->data)) {
            if (!$this->request->data['regenerate_slug']) {
                $this->Nodes->behaviors()->Sluggable->config(['on' => 'create']);
            }

            unset($this->request->data['regenerate_slug']);
            $node->accessible([
                'id',
                'node_type_id',
                'node_type_slug',
                'translation_for',
                'created_by',
            ], false);
            $node = $this->Nodes->patchEntity($node, $this->request->data);

            if ($this->Nodes->save($node, ['atomic' => true, 'associated' => ['Roles']])) {
                $this->Flash->success(__d('node', 'Information was saved!'));
                $this->redirect("/admin/node/manage/edit/{$id}");
            } else {
                $this->Flash->danger(__d('node', 'Something went wrong, please check your information.'));
            }
        }

        $languages = LocaleToolbox::languagesList();
        $roles = $this->Nodes->Roles->find('list');
        $this->set(compact('node', 'languages', 'roles'));
        $this->Breadcrumb
            ->push('/admin/node/manage')
            ->push(__d('node', 'Editing content'), '#');
    }

    /**
     * Translate the given node to a different language.
     *
     * @param int $nodeId Node's ID
     * @return void
     */
    public function translate($nodeId)
    {
        $this->loadModel('Node.Nodes');
        $node = $this->Nodes->get($nodeId, ['contain' => 'NodeTypes']);

        if (!$node->language || $node->translation_for) {
            $this->Flash->danger(__d('node', 'You cannot translate this content.'));
            $this->redirect(['plugin' => 'Node', 'controller' => 'manage', 'action' => 'index']);
        }

        $translations = $this->Nodes
            ->find()
            ->where(['translation_for' => $node->id])
            ->all();
        $languages = LocaleToolbox::languagesList();
        $illegal = array_merge([$node->language], $translations->extract('language')->toArray());
        foreach ($languages as $code => $name) {
            if (in_array($code, $illegal)) {
                unset($languages[$code]);
            }
        }

        if (!empty($languages) &&
            !empty($this->request->data['language']) &&
            !empty($this->request->data['title']) &&
            $this->request->data['language'] !== $node->language
        ) {
            $newNode = $this->Nodes->newEntity($node->toArray(), [
                'fieldList' => [
                    'node_type_id',
                    'node_type_slug',
                    'title',
                ]
            ]);
            $newNode->set('status', false);
            $newNode->set('title', $this->request->data['title']);
            $newNode->set('translation_for', $node->id);
            $newNode->set('language', $this->request->data['language']);

            if ($this->Nodes->save($newNode)) {
                $this->Flash->success(__d('node', 'Translation successfully created and was marked as unpublished. Complete the translation before publishing.'));
                $this->redirect(['plugin' => 'Node', 'controller' => 'manage', 'action' => 'edit', $newNode->id]);
            } else {
                $this->Flash->set(__d('system', 'Translation could not be created'), [
                    'element' => 'System.installer_errors',
                    'params' => ['errors' => $newNode->errors()],
                ]);
            }
        }

        $this->set(compact('node', 'translations', 'languages'));
        $this->Breadcrumb
            ->push('/admin/node/manage')
            ->push(__d('node', 'Translating content'), '');
    }

    /**
     * Deletes the given node by ID.
     *
     * @param int $nodeId Node's ID
     * @return void
     */
    public function delete($nodeId)
    {
        $this->loadModel('Node.Nodes');
        $node = $this->Nodes->get($nodeId);

        if ($this->Nodes->delete($node, ['atomic' => true])) {
            $this->Flash->success(__d('node', 'Content was successfully removed!'));
        } else {
            $this->Flash->danger(__d('node', 'Unable to remove this content, please try again.'));
        }

        $this->redirect($this->referer());
    }

    /**
     * Removes the given revision of the given node.
     *
     * @param int $nodeId Node's ID
     * @param int $revisionId Revision's ID
     * @return void Redirects to previous page
     */
    public function deleteRevision($nodeId, $revisionId)
    {
        $this->loadModel('Node.NodeRevisions');
        $revision = $this->NodeRevisions->find()
            ->where(['id' => $revisionId, 'node_id' => $nodeId])
            ->first();

        if ($this->NodeRevisions->delete($revision, ['atomic' => true])) {
            $this->Flash->success(__d('node', 'Revision was successfully removed!'));
        } else {
            $this->Flash->danger(__d('node', 'Unable to remove this revision, please try again.'));
        }

        $this->redirect($this->referer());
    }
}
