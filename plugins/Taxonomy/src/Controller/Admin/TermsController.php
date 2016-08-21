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
namespace Taxonomy\Controller\Admin;

use Taxonomy\Controller\AppController;

/**
 * Taxonomy terms controller.
 *
 * Allow CRUD for vocabulary's terms.
 */
class TermsController extends AppController
{

    /**
     * Shows a tree list of all terms within a vocabulary.
     *
     * @param int $id Vocabulary's ID for which render its terms
     * @return void
     */
    public function vocabulary($id)
    {
        $this->loadModel('Taxonomy.Vocabularies');
        $vocabulary = $this->Vocabularies->get($id);
        $terms = $this->Vocabularies->Terms
            ->find()
            ->where(['vocabulary_id' => $id])
            ->order(['lft' => 'ASC'])
            ->all()
            ->map(function ($term) {
                $term->set('expanded', true);

                return $term;
            })
            ->nest('id', 'parent_id');

        if (!empty($this->request->data['tree_order'])) {
            $items = json_decode($this->request->data['tree_order']);

            if ($items) {
                unset($items[0]);
                $entities = [];

                foreach ($items as $key => $item) {
                    $term = $this->Vocabularies->Terms->newEntity([
                        'id' => $item->item_id,
                        'parent_id' => intval($item->parent_id),
                        'lft' => ($item->left - 1),
                        'rght' => ($item->right - 1),
                    ], ['validate' => false]);
                    $term->isNew(false);
                    $term->dirty('id', false);
                    $entities[] = $term;
                }

                $this->Vocabularies->Terms->unbindSluggable();
                $this->Vocabularies->Terms->connection()->transactional(function () use ($entities) {
                    foreach ($entities as $entity) {
                        $this->Vocabularies->Terms->save($entity, ['atomic' => false]);
                    }
                });
                // don't trust "left" and "right" values coming from user's POST
                $this->Vocabularies->Terms->addBehavior('Tree', ['scope' => ['vocabulary_id' => $vocabulary->id]]);
                $this->Vocabularies->Terms->recover();
                $this->Flash->success(__d('taxonomy', 'Vocabulary terms tree has been reordered'));
            } else {
                $this->Flash->danger(__d('taxonomy', 'Invalid information, check you have JavaScript enabled'));
            }

            $this->redirect($this->referer());
        }

        $this->title(__d('taxonomy', 'Vocabulary’s Terms'));
        $this->set(compact('vocabulary', 'terms'));
        $this->Breadcrumb
            ->push('/admin/system/structure')
            ->push(__d('taxonomy', 'Taxonomy'), '/admin/taxonomy/manage')
            ->push(__d('taxonomy', 'Vocabularies'), ['plugin' => 'Taxonomy', 'controller' => 'vocabularies', 'action' => 'index'])
            ->push("\"{$vocabulary->name}\"", ['plugin' => 'Taxonomy', 'controller' => 'vocabularies', 'action' => 'edit', $vocabulary->id])
            ->push(__d('taxonomy', 'Terms'), '#');
    }

    /**
     * Adds a new terms within the given vocabulary.
     *
     * @param int $vocabularyId Vocabulary's ID
     * @return void
     */
    public function add($vocabularyId)
    {
        $this->loadModel('Taxonomy.Vocabularies');
        $vocabulary = $this->Vocabularies->get($vocabularyId);
        $term = $this->Vocabularies->Terms->newEntity(['vocabulary_id' => $vocabulary->id], ['validate' => false]);
        $this->Vocabularies->Terms->addBehavior('Tree', ['scope' => ['vocabulary_id' => $vocabulary->id]]);

        if ($this->request->data()) {
            $term = $this->Vocabularies->Terms->patchEntity($term, $this->request->data, [
                'fieldList' => [
                    'parent_id',
                    'name',
                ]
            ]);

            if ($this->Vocabularies->Terms->save($term)) {
                $this->Flash->success(__d('taxonomy', 'Term has been created.'));
                if (!empty($this->request->data['action_vocabulary'])) {
                    $this->redirect(['plugin' => 'Taxonomy', 'controller' => 'terms', 'action' => 'vocabulary', $vocabulary->id]);
                } elseif (!empty($this->request->data['action_add'])) {
                    $this->redirect(['plugin' => 'Taxonomy', 'controller' => 'terms', 'action' => 'add', $vocabulary->id]);
                }
            } else {
                $this->Flash->danger(__d('taxonomy', 'Term could not be created, please check your information.'));
            }
        }

        $parentsTree = $this->Vocabularies->Terms
            ->find('treeList', ['spacer' => '--'])
            ->map(function ($link) {
                if (strpos($link, '-') !== false) {
                    $link = str_replace_last('-', '- ', $link);
                }

                return $link;
            });

        $this->title(__d('taxonomy', 'Create New Term'));
        $this->set(compact('vocabulary', 'term', 'parentsTree'));
        $this->Breadcrumb
            ->push('/admin/system/structure')
            ->push(__d('taxonomy', 'Taxonomy'), '/admin/taxonomy/manage')
            ->push(__d('taxonomy', 'Vocabularies'), ['plugin' => 'Taxonomy', 'controller' => 'vocabularies', 'action' => 'index'])
            ->push("\"{$vocabulary->name}\"", ['plugin' => 'Taxonomy', 'controller' => 'vocabularies', 'action' => 'edit', $vocabulary->id])
            ->push(__d('taxonomy', 'Terms'), ['plugin' => 'Taxonomy', 'controller' => 'terms', 'action' => 'vocabulary', $vocabulary->id])
            ->push(__d('taxonomy', 'Add new term'), '#');
    }

    /**
     * Edits the given vocabulary's term by ID.
     *
     * @param int $id Term's ID
     * @return void
     */
    public function edit($id)
    {
        $this->loadModel('Taxonomy.Terms');
        $term = $this->Terms->get($id, ['contain' => ['Vocabularies']]);
        $vocabulary = $term->vocabulary;

        if ($this->request->data()) {
            $term = $this->Terms->patchEntity($term, $this->request->data(), ['fieldList' => ['name']]);
            $errors = $term->errors();

            if (empty($errors)) {
                $this->Terms->save($term, ['associated' => false]);
                $this->Flash->success(__d('taxonomy', 'Term has been updated'));
                $this->redirect($this->referer());
            } else {
                $this->Flash->danger(__d('taxonomy', 'Term could not be updated, please check your information'));
            }
        }

        $this->title(__d('taxonomy', 'Editing Term'));
        $this->set('term', $term);
        $this->Breadcrumb
            ->push('/admin/system/structure')
            ->push(__d('taxonomy', 'Taxonomy'), '/admin/taxonomy/manage')
            ->push(__d('taxonomy', 'Vocabularies'), ['plugin' => 'Taxonomy', 'controller' => 'vocabularies', 'action' => 'index'])
            ->push("\"{$vocabulary->name}\"", ['plugin' => 'Taxonomy', 'controller' => 'vocabularies', 'action' => 'edit', $vocabulary->id])
            ->push(__d('taxonomy', 'Terms'), ['plugin' => 'Taxonomy', 'controller' => 'terms', 'action' => 'vocabulary', $vocabulary->id])
            ->push(__d('taxonomy', 'Editing term'), '#');
    }

    /**
     * Deletes the given term.
     *
     * @param int $id Term's ID
     * @return void
     */
    public function delete($id)
    {
        $this->loadModel('Taxonomy.Terms');
        $term = $this->Terms->get($id);
        $this->Terms->addBehavior('Tree', ['scope' => ['vocabulary_id' => $term->vocabulary_id]]);
        $this->Terms->removeFromTree($term);

        if ($this->Terms->delete($term)) {
            $this->Flash->success(__d('taxonomy', 'Term successfully removed!'));
        } else {
            $this->Flash->danger(__d('taxonomy', 'Term could not be removed, please try again'));
        }

        $this->title(__d('taxonomy', 'Delete Term'));
        $this->redirect($this->referer());
    }
}
