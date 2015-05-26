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

use Cake\ORM\Query;
use Content\Controller\AppController;
use Content\Error\ContentCreateException;
use Content\Error\ContentDeleteException;
use Content\Error\ContentEditException;
use Content\Error\ContentNotFoundException;
use Content\Error\ContentTranslateException;
use Content\Error\ContentTypeNotFoundException;
use Locale\Utility\LocaleToolbox;

/**
 * Content manager controller.
 *
 * Provides full CRUD for contents.
 *
 * @property \Content\Model\Table\ContentsTable $Contents
 * @property \Content\Model\Table\ContentTypesTable $ContentTypes
 * @property \Content\Model\Table\ContentRevisionsTable $ContentRevisions
 */
class ManageController extends AppController
{

    /**
     * An array containing the names of helpers controllers uses.
     *
     * @var array
     */
    public $helpers = ['Paginator'];

    /**
     * Shows a list of all the contents.
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Content.Contents');
        $this->Contents->Author->unbindFieldable();
        $this->Contents->ModifiedBy->unbindFieldable();
        $contents = $this->Contents
            ->find('all', ['fieldable' => false])
            ->contain(['ContentTypes', 'Author', 'ModifiedBy']);

        if (!empty($this->request->query['filter']) &&
            $contents instanceof Query
        ) {
            $this->Contents->search($this->request->query['filter'], $contents);
        }

        $this->title(__d('content', 'Contents List'));
        $this->set('contents', $this->paginate($contents));
        $this->Breadcrumb->push('/admin/content/manage');
    }

    /**
     * Content-type selection screen.
     *
     * User must select which content type wish to create.
     *
     * @return void
     */
    public function create()
    {
        $this->loadModel('Content.ContentTypes');
        $types = $this->ContentTypes->find()
            ->select(['id', 'slug', 'name', 'description'])
            ->all();

        $this->title(__d('content', 'Create New Content'));
        $this->set('types', $types);
        $this->Breadcrumb
            ->push('/admin/content/manage')
            ->push(__d('content', 'Create new content'), '');
    }

    /**
     * Shows the "new content" form.
     *
     * @param string $typeSlug Content type slug. e.g.: "article", "product-info"
     * @return void
     * @throws \Content\Error\ContentTypeNotFoundException When content type was not
     *  found
     * @throws \Content\Error\ContentCreateException When current user is not allowed
     *  to create contents of this type
     */
    public function add($typeSlug)
    {
        $this->loadModel('Content.ContentTypes');
        $this->loadModel('Content.Contents');
        $this->Contents->unbindComments();
        $type = $this->ContentTypes->find()
            ->where(['slug' => $typeSlug])
            ->limit(1)
            ->first();

        if (!$type) {
            throw new ContentTypeNotFoundException(__d('content', 'The specified content type ({0}) does not exists.', $type));
        }

        if (!$type->userAllowed('create')) {
            throw new ContentCreateException(__d('content', 'You are not allowed to create contents of this type ({0}).', $type->name));
        }

        if ($this->request->data()) {
            $data = $this->request->data();
            $data['content_type_slug'] = $type->slug;
            $data['content_type_id'] = $type->id;
            $content = $this->Contents->newEntity($data);

            if ($this->Contents->save($content)) {
                if (!$type->userAllowed('publish')) {
                    $this->Flash->warning(__d('content', 'Content created, but awaiting moderation before publishing it.'));
                } else {
                    $this->Flash->success(__d('content', 'Content created!.'));
                }
                $this->redirect(['plugin' => 'Content', 'controller' => 'manage', 'action' => 'edit', 'prefix' => 'admin', $content->id]);
            } else {
                $this->Flash->danger(__d('content', 'Something went wrong, please check your information.'));
            }
        } else {
            $content = $this->Contents->newEntity(['content_type_slug' => $type->slug]);
            $content->setDefaults($type);
        }

        $content->set('content_type', $type);
        $content = $this->Contents->attachFields($content);
        $languages = LocaleToolbox::languagesList();
        $roles = $this->Contents->Roles->find('list');

        $this->title(__d('content', 'Create New Content <small>({0})</small>', $type->slug));
        $this->set(compact('content', 'type', 'languages', 'roles'));
        $this->Breadcrumb
            ->push('/admin/content/manage')
            ->push(__d('content', 'Create new content'), ['plugin' => 'Content', 'controller' => 'manage', 'action' => 'create'])
            ->push($type->name, '');
    }

    /**
     * Edit form for the given content.
     *
     * @param int $id Content's ID
     * @param false|int $revisionId Fill form with content's revision information
     * @return void
     * @throws \Content\Error\ContentNotFoundException When content type, or when
     *  content content was not found
     * @throws \Content\Error\ContentEditException When user is not allowed to edit
     *  contents of this type
     */
    public function edit($id, $revisionId = false)
    {
        $this->loadModel('Content.Contents');
        $this->Contents->unbindComments();
        $content = false;

        if (intval($revisionId) > 0 && !$this->request->data()) {
            $this->loadModel('Content.ContentRevisions');
            $revision = $this->ContentRevisions->find()
                ->where(['id' => $revisionId, 'content_id' => $id])
                ->first();

            if ($revision) {
                $content = $revision->data;

                if (!empty($content->_fields)) {
                    // Merge previous data for each field, we just load the data (metadata keeps to the latests configured).
                    $_fieldsRevision = $content->_fields;
                    $content = $this->Contents->attachFields($content);
                    $content->_fields = $content->_fields->map(function ($field, $key) use ($_fieldsRevision) {
                        $fieldRevision = $_fieldsRevision[$field->name];
                        if ($fieldRevision) {
                            $field->set('value', $fieldRevision->value);
                            $field->set('extra', $fieldRevision->extra);
                        }
                        return $field;
                    });
                }
            }
        } else {
            $content = $this->Contents
                ->find()
                ->where(['Contents.id' => $id])
                ->contain([
                    'Roles',
                    'Translations',
                    'ContentRevisions',
                    'ContentTypes',
                    'TranslationOf',
                ])
                ->first();
        }

        if (!$content || empty($content->content_type)) {
            throw new ContentNotFoundException(__d('content', 'The requested page was not found.'));
        }

        if (!$content->content_type->userAllowed('edit')) {
            throw new ContentEditException(__d('content', 'You are not allowed to create contents of this type ({0}).', $content->content_type->name));
        }

        if (!empty($this->request->data)) {
            if (empty($this->request->data['regenerate_slug'])) {
                $this->Contents->behaviors()->Sluggable->config(['on' => 'create']);
            } else {
                unset($this->request->data['regenerate_slug']);
            }

            $content->accessible([
                'id',
                'content_type_id',
                'content_type_slug',
                'translation_for',
                'created_by',
            ], false);

            $content = $this->Contents->patchEntity($content, $this->request->data());
            if ($this->Contents->save($content, ['atomic' => true, 'associated' => ['Roles']])) {
                $this->Flash->success(__d('content', 'Content updated!'));
                $this->redirect("/admin/content/manage/edit/{$id}");
            } else {
                $this->Flash->danger(__d('content', 'Something went wrong, please check your information.'));
            }
        }

        $languages = LocaleToolbox::languagesList();
        $roles = $this->Contents->Roles->find('list');

        $this->title(__d('content', 'Editing Content: {0} <small>({1})</small>', $content->title, $content->content_type_slug));
        $this->set(compact('content', 'languages', 'roles'));
        $this->Breadcrumb
            ->push('/admin/content/manage/index')
            ->push(__d('content', 'Editing content'), '#');
    }

    /**
     * Translate the given content to a different language.
     *
     * @param int $contentId Content's ID
     * @return void
     * @throws \Content\Error\ContentNotFoundException When content type, or when
     *  content content was not found
     * @throws \Content\Error\ContentTranslateException When user is not allowed to
     *  translate contents of this type
     */
    public function translate($contentId)
    {
        $this->loadModel('Content.Contents');
        $content = $this->Contents->get($contentId, ['contain' => 'ContentTypes']);

        if (!$content || empty($content->content_type)) {
            throw new ContentNotFoundException(__d('content', 'The requested page was not found.'));
        }

        if (!$content->content_type->userAllowed('translate')) {
            throw new ContentTranslateException(__d('content', 'You are not allowed to translate contents of this type ({0}).', $content->content_type->name));
        }

        if (!$content->language || $content->translation_for) {
            $this->Flash->danger(__d('content', 'You cannot translate this content.'));
            $this->redirect(['plugin' => 'Content', 'controller' => 'manage', 'action' => 'index']);
        }

        $translations = $this->Contents
            ->find()
            ->where(['translation_for' => $content->id])
            ->all();
        $languages = LocaleToolbox::languagesList();
        $illegal = array_merge([$content->language], $translations->extract('language')->toArray());

        foreach ($languages as $code => $name) {
            if (in_array($code, $illegal)) {
                unset($languages[$code]);
            }
        }

        if (!empty($languages) &&
            !empty($this->request->data['language']) &&
            !empty($this->request->data['title']) &&
            $this->request->data['language'] !== $content->language
        ) {
            $this->Contents->unbindFieldable(); // fix, wont trigger fields validation
            $newContent = $this->Contents->newEntity([
                'content_type_id' => $content->get('content_type_id'),
                'content_type_slug' => $content->get('content_type_slug'),
                'title' => $content->get('title'),
                'status' => false,
                'title' => $this->request->data['title'],
                'translation_for' => $content->id,
                'language' => $this->request->data['language'],
            ]);

            if ($this->Contents->save($newContent)) {
                $this->Flash->success(__d('content', 'Translation successfully created and was marked as unpublished. Complete the translation before publishing.'));
                $this->redirect(['plugin' => 'Content', 'controller' => 'manage', 'action' => 'edit', $newContent->id]);
            } else {
                $this->Flash->set(__d('content', 'Translation could not be created'), [
                    'element' => 'System.installer_errors',
                    'params' => ['errors' => $newContent->errors()],
                ]);
            }
        }

        $this->title(__d('content', 'Translate Content'));
        $this->set(compact('content', 'translations', 'languages'));
        $this->Breadcrumb
            ->push('/admin/content/manage')
            ->push(__d('content', 'Translating content'), '');
    }

    /**
     * Deletes the given content by ID.
     *
     * @param int $contentId Content's ID
     * @return void
     */
    public function delete($contentId)
    {
        $this->loadModel('Content.Contents');
        $content = $this->Contents->get($contentId, ['contain' => ['ContentTypes']]);

        if (!$content || empty($content->content_type)) {
            throw new ContentNotFoundException(__d('content', 'The requested page was not found.'));
        }

        if (!$content->content_type->userAllowed('translate')) {
            throw new ContentDeleteException(__d('content', 'You are not allowed to delete contents of this type ({0}).', $content->content_type->name));
        }

        if ($this->Contents->delete($content, ['atomic' => true])) {
            $this->Flash->success(__d('content', 'Content was successfully removed!'));
        } else {
            $this->Flash->danger(__d('content', 'Unable to remove this content, please try again.'));
        }

        $this->title(__d('content', 'Delete Content'));
        $this->redirect($this->referer());
    }

    /**
     * Removes the given revision of the given content.
     *
     * @param int $contentId Content's ID
     * @param int $revisionId Revision's ID
     * @return void Redirects to previous page
     */
    public function deleteRevision($contentId, $revisionId)
    {
        $this->loadModel('Content.ContentRevisions');
        $revision = $this->ContentRevisions->find()
            ->where(['id' => $revisionId, 'content_id' => $contentId])
            ->first();

        if ($this->ContentRevisions->delete($revision, ['atomic' => true])) {
            $this->Flash->success(__d('content', 'Revision was successfully removed!'));
        } else {
            $this->Flash->danger(__d('content', 'Unable to remove this revision, please try again.'));
        }

        $this->title(__d('content', 'Editing Content Revision'));
        $this->redirect($this->referer());
    }
}
