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
namespace Content\Controller\Admin;

use Cake\Network\Exception\NotFoundException;
use Content\Controller\AppController;
use Locale\Utility\LocaleToolbox;

/**
 * Controller for Content Types handling.
 *
 * Provides full CRUD for content types.
 */
class TypesController extends AppController
{

    /**
     * List of registered content-types.
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Content.ContentTypes');
        $types = $this->ContentTypes->find()
            ->select(['id', 'slug', 'name', 'description'])
            ->all();

        $this->title(__d('content', 'Content Types'));
        $this->set('types', $types);
        $this->Breadcrumb
            ->push('/admin/content/types')
            ->push(__d('content', 'Content Types'), '#');
    }

    /**
     * Create new content type.
     *
     * @return void
     */
    public function add()
    {
        $this->loadModel('Content.ContentTypes');
        $this->loadModel('User.Roles');
        $type = $this->ContentTypes->newEntity();

        if ($this->request->data()) {
            $type = $this->ContentTypes->patchEntity($type, $this->request->data());
            $errors = $type->errors();
            $success = empty($errors);

            if ($success) {
                $success = $this->ContentTypes->save($type);
                if ($success) {
                    $this->Flash->success(__d('content', 'Content type created, now attach some fields.'));
                    $this->redirect(['plugin' => 'Content', 'controller' => 'fields', 'type' => $type->slug]);
                }
            }

            if (!$success) {
                $this->Flash->danger(__d('content', 'Content type could not be created, check your information.'));
            }
        }

        $roles = $this->Roles->find('list');
        $this->title(__d('content', 'Define New Content Type'));
        $this->set(compact('type', 'roles'));
        $this->set('languages', LocaleToolbox::languagesList());
        $this->Breadcrumb
            ->push('/admin/content/manage')
            ->push(__d('content', 'Content Types'), '/admin/content/types')
            ->push(__d('content', 'Creating Content Type'), '#');
    }

    /**
     * Edit content type settings.
     *
     * @param string $slug Content type's slug
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When content type was not
     *  found.
     */
    public function edit($slug)
    {
        $this->loadModel('Content.ContentTypes');
        $this->loadModel('User.Roles');

        $type = $this->ContentTypes
            ->find()
            ->where(['slug' => $slug])
            ->first();

        if (!$type) {
            throw new NotFoundException(__d('content', 'Content type was not found!'));
        }

        if ($this->request->data()) {
            $type->accessible(['id', 'slug'], false);
            $data = $this->request->data();
            $type = $this->ContentTypes->patchEntity($type, $data);

            if ($this->ContentTypes->save($type, ['associated' => ['Roles']])) {
                $this->Flash->success(__d('content', 'Content type updated!'));
                $this->redirect(['plugin' => 'Content', 'controller' => 'types', 'action' => 'edit', $type->slug]);
            } else {
                $this->Flash->danger(__d('content', 'Content type could not be updated, check your information.'));
            }
        } else {
            // fix for auto-fill "defaults.*" by FormHelper
            $this->request->data = $type->toArray();
        }

        $roles = $this->Roles->find('list');
        $this->title(__d('content', 'Configure Content Type'));
        $this->set(compact('type', 'roles'));
        $this->set('languages', LocaleToolbox::languagesList());
        $this->Breadcrumb
            ->push('/admin/content/manage')
            ->push(__d('content', 'Content Types'), '/admin/content/types')
            ->push(__d('content', 'Editing "{0}" Content Type', $type->name), '');
    }

    /**
     * Remove content type.
     *
     * All existing contents will not be removed.
     *
     * @param string $slug Content type's slug
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When content type was not
     *  found.
     */
    public function delete($slug)
    {
        $this->loadModel('Content.ContentTypes');
        $type = $this->ContentTypes->find()
            ->where(['slug' => $slug])
            ->first();

        if (!$type) {
            throw new NotFoundException(__d('content', 'Content type was not found!'));
        }

        if ($this->ContentTypes->delete($type)) {
            $this->Flash->success(__d('content', 'Content was deleted!'));
        } else {
            $$this->Flash->danger(__d('content', 'Content type could not be deleted, please try again.'));
        }

        $this->title(__d('content', 'Delete Content Type'));
        $this->redirect($this->referer());
    }
}
