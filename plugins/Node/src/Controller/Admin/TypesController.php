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
 * Controller for Node Types handling.
 *
 * Provides full CRUD for content types.
 */
class TypesController extends AppController
{

    /**
     * List of registered node-types.
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Node.NodeTypes');
        $types = $this->NodeTypes->find()
            ->select(['id', 'slug', 'name', 'description'])
            ->all();
        $this->set('types', $types);
        $this->Breadcrumb->push('/admin/node/types');
    }

    /**
     * Create new content type.
     *
     * @return void
     */
    public function add()
    {
        $this->loadModel('Node.NodeTypes');

        if ($this->request->data) {
            $type = $this->NodeTypes->newEntity($this->request->data);

            if ($this->NodeTypes->save($type)) {
                $this->Flash->success(__d('node', 'Content type created, now attach some fields.'));
                $this->redirect(['plugin' => 'Node', 'controller' => 'fields', 'type' => $type->slug]);
            } else {
                $this->Flash->danger(__d('node', 'Content type could not be created, check your information.'));
            }
        } else {
            $type = $this->NodeTypes->newEntity();
        }

        $this->set('type', $type);
        $this->set('languages', LocaleToolbox::languagesList());
        $this->Breadcrumb
            ->push('/admin/node/types')
            ->push(__d('node', 'Creating Content Type'), '#');
    }

    /**
     * Edit content type settings.
     *
     * @param string $slug Node type's slug
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When content type was not
     *  found.
     */
    public function edit($slug)
    {
        $this->loadModel('Node.NodeTypes');
        $type = $this->NodeTypes->find()
            ->where(['slug' => $slug])
            ->first();

        if (!$type) {
            throw new NotFoundException(__d('node', 'Content type was not found!'));
        }

        if ($this->request->data) {
            $type->accessible('*', true);
            $type->accessible(['id', 'slug'], false);
            $type->set($this->request->data);

            if ($this->NodeTypes->save($type)) {
                $this->Flash->success(__d('node', 'Content type updated!'));
                $this->redirect(['plugin' => 'Node', 'controller' => 'types', 'action' => 'edit', $type->slug]);
            } else {
                $this->Flash->danger(__d('node', 'Content type could not be updated, check your information.'));
            }
        } else {
            // fix for auto-fill "defaults.*" by FormHelper
            $this->request->data = $type->toArray();
        }

        $this->set('type', $type);
        $this->set('languages', LocaleToolbox::languagesList());
        $this->Breadcrumb
            ->push('/admin/node/types')
            ->push(__d('node', 'Editing "{0}" Content Type', $type->name), '');
    }

    /**
     * Remove content type.
     *
     * All existing contents will not be removed.
     *
     * @param string $slug Node type's slug
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When content type was not
     *  found.
     */
    public function delete($slug)
    {
        $this->loadModel('Node.NodeTypes');
        $type = $this->NodeTypes->find()
            ->where(['slug' => $slug])
            ->first();

        if (!$type) {
            throw new NotFoundException(__d('node', 'Content type was not found!'));
        }

        if ($this->NodeTypes->delete($type)) {
            $this->Flash->success(__d('node', 'Content was deleted!'));
        } else {
            $$this->Flash->danger(__d('node', 'Content type could not be deleted, please try again.'));
        }

        $this->redirect($this->referer());
    }
}
