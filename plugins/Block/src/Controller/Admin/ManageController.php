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
namespace Block\Controller\Admin;

use Block\Controller\AppController;
use Locale\Utility\LocaleToolbox;
use QuickApps\Core\Plugin;

/**
 * Block manager controller.
 *
 * Allow CRUD for blocks.
 *
 * @property \Block\Model\Table\BlocksTable $Blocks
 * @property \Block\Model\Table\BlocksTable $BlockRegions
 * @property \Menu\Controller\Component\BreadcrumbComponent $Breadcrumb
 */
class ManageController extends AppController
{

    /**
     * Shows a list of all the nodes.
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Block.Blocks');
        if ($this->request->isPost()) {
            if ($this->_reorder()) {
                $this->Flash->success(__d('block', 'Blocks ordering updated!'));
            }
            $this->redirect(['plugin' => 'Block', 'controller' => 'manage', 'action' => 'index']);
        }

        $front = $this->Blocks->inFrontTheme();
        $back = $this->Blocks->inBackTheme();
        $unused = $this->Blocks->unused();

        $this->set(compact('front', 'back', 'unused'));
        $this->title(__d('block', 'Site Blocks'));
        $this->Breadcrumb
            ->push('/admin/system/structure')
            ->push(__d('block', 'Manage Blocks'), '#');
    }

    /**
     * Creates a new custom block.
     *
     * @return void
     */
    public function add()
    {
        $this->loadModel('Block.Blocks');
        $block = $this->Blocks->newEntity();
        $block->set('region', []);

        if ($this->request->data()) {
            $block = $this->_patchEntity($block);

            if (!$block->errors()) {
                $block->calculateDelta();
                if ($this->Blocks->save($block)) {
                    $this->Flash->success(__d('block', 'Block created.'));
                    $this->redirect(['plugin' => 'Block', 'controller' => 'manage', 'action' => 'edit', $block->id]);
                } else {
                    $this->Flash->danger(__d('block', 'Block could not be created, please check your information.'));
                }
            } else {
                $this->Flash->danger(__d('block', 'Block could not be created, please check your information.'));
            }
        }

        $this->_setLanguages();
        $this->_setRoles();
        $this->_setRegions();
        $this->set('block', $block);
        $this->title(__d('block', 'Create New Block'));
        $this->Breadcrumb
            ->push('/admin/system/structure')
            ->push(__d('block', 'Manage Blocks'), ['plugin' => 'Block', 'controller' => 'manage', 'action' => 'index'])
            ->push(__d('block', 'Create New Block'), '#');
    }

    /**
     * Edits the given block by ID.
     *
     * The event `Block.<handler>.validate` will be automatically triggered,
     * so custom block's (those handled by plugins <> "Block") can be validated
     * before persisted.
     *
     * @param string $id Block's ID
     * @return void
     * @throws \Cake\ORM\Exception\RecordNotFoundException if no block is not found
     */
    public function edit($id)
    {
        $this->loadModel('Block.Blocks');
        $block = $this->Blocks->get($id, ['flatten' => true, 'contain' => ['BlockRegions', 'Roles']]);

        if ($this->request->data()) {
            $block->accessible('id', false);
            $block->accessible('handler', false);
            $block = $this->_patchEntity($block);

            if (!$block->errors()) {
                if ($this->Blocks->save($block)) {
                    $this->Flash->success(__d('block', 'Block updated!'));
                    $this->redirect($this->referer());
                } else {
                    $this->Flash->success(__d('block', 'Your information could not be saved, please try again.'));
                }
            } else {
                $this->Flash->danger(__d('block', 'Block could not be updated, please check your information.'));
            }
        }

        $this->set(compact('block'));
        $this->_setLanguages();
        $this->_setRoles();
        $this->_setRegions($block);
        $this->title(__d('block', 'Editing Block'));
        $this->Breadcrumb
            ->push('/admin/system/structure')
            ->push(__d('block', 'Manage Blocks'), ['plugin' => 'Block', 'controller' => 'manage', 'action' => 'index'])
            ->push(__d('block', 'Editing Block'), '#');
    }

    /**
     * Deletes the given block by ID.
     *
     * Only custom blocks can be deleted (those with "Block" has handler).
     *
     * @param string $id Block's ID
     * @return void Redirects to previous page
     * @throws \Cake\ORM\Exception\RecordNotFoundException if no record can be found
     *  given a primary key value
     * @throws \InvalidArgumentException When $primaryKey has an incorrect number
     *  of elements
     */
    public function delete($id)
    {
        $this->loadModel('Block.Blocks');
        $block = $this->Blocks->find()
            ->where(['id' => $id])
            ->first();

        if ($block &&
            ($block->handler == 'Block' || !empty($block->copy_id))
        ) {
            if ($this->Blocks->delete($block)) {
                $this->Flash->success(__d('block', 'Block was successfully removed!'));
            } else {
                $this->Flash->danger(__d('block', 'Block could not be removed'));
            }
        } else {
            $this->Flash->warning(__d('block', 'Block not found!'));
        }

        $this->title(__d('block', 'Delete Block'));
        $this->redirect($this->referer());
    }

    /**
     * Edits the given block by ID.
     *
     * @param string $id Block's ID
     * @return void Redirects to previous page
     * @throws \Cake\ORM\Exception\RecordNotFoundException if no block is not found
     */
    public function duplicate($id)
    {
        $this->loadModel('Block.Blocks');
        $original = $this->Blocks->get((int)$id);
        $new = $this->Blocks->newEntity($original->toArray());
        $new->set(['copy_id' => $original->id, 'delta' => null]);
        $new->unsetProperty('id');
        $new->isNew(true);
        $new->calculateDelta();

        if ($this->Blocks->save($new)) {
            $this->Flash->success(__d('block', 'Block has been duplicated, it can be found under the "Unused or Unassigned" section.'));
        } else {
            $this->Flash->danger(__d('block', 'Block could not be duplicated, please try again.'));
        }

        $this->title(__d('block', 'Duplicate Block'));
        $this->redirect($this->referer() . '#unused-blocks');
    }

    /**
     * Reorders blocks based on the order provided via POST.
     *
     * @return bool True on success, false otherwise
     */
    protected function _reorder()
    {
        if (!empty($this->request->data['regions'])) {
            foreach ($this->request->data['regions'] as $theme => $regions) {
                foreach ($regions as $region => $ids) {
                    $ordering = 0;
                    foreach ($ids as $id) {
                        $blockRegion = $this->Blocks
                            ->BlockRegions
                            ->newEntity([
                                'id' => $id,
                                'theme' => $theme,
                                'region' => $region,
                                'ordering' => $ordering
                            ]);
                        $blockRegion->isNew(false);
                        $this->Blocks->BlockRegions->save($blockRegion);
                        $ordering++;
                    }
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Sets "languages" variable for later use in FormHelper.
     *
     * @return void
     */
    protected function _setLanguages()
    {
        $this->set('languages', LocaleToolbox::languagesList());
    }

    /**
     * Sets "roles" variable for later use in FormHelper.
     *
     * @return void
     */
    protected function _setRoles()
    {
        $this->loadModel('Block.Blocks');
        $roles = $this->Blocks->Roles->find('list');
        $this->set('roles', $roles);
    }

    /**
     * Sets "regions" variable for later use in FormHelper.
     *
     * This variable is used to properly fill inputs in the "Theme Region"
     * section of the add/edit form.
     *
     * @param null|\Block\Model\Entity\Block $block If a block entity is provided it
     *  will be used to guess which regions has been already selected in each theme,
     *  so we can properly show the selectbox in the form with the corrects
     *  options selected.
     * @return void
     */
    protected function _setRegions($block = null)
    {
        $regions = Plugin::get()
            ->filter(function ($plugin) {
                return $plugin->isTheme;
            })
            ->map(function ($theme) use ($block) {
                $value = '';
                if ($block !== null && $block->has('region')) {
                    foreach ($block->region as $blockRegion) {
                        if ($blockRegion->theme == $theme->name) {
                            $value = $blockRegion->region;
                            break;
                        }
                    }
                }

                return [
                    'theme_machine_name' => $theme->name,
                    'theme_human_name' => $theme->human_name,
                    'description' => $theme->composer['description'],
                    'regions' => (array)$theme->composer['extra']['regions'],
                    'value' => $value,
                ];
            });
        $this->set('regions', $regions);
    }

    /**
     * Prepares incoming data from Form's POST and patches the given entity.
     *
     * @param \Block\Model\Entity\Block $block BLock to patch with incoming
     *  POST data
     * @return \Cake\Datasource\EntityInterface Patched entity
     */
    protected function _patchEntity($block)
    {
        $this->loadModel('Block.Blocks');
        $data = ['region' => []];

        foreach ($this->request->data() as $column => $value) {
            if ($column == 'region') {
                foreach ($value as $theme => $region) {
                    $tmp = ['theme' => $theme, 'region' => $region];
                    foreach ((array)$block->region as $blockRegion) {
                        if ($blockRegion->theme == $theme) {
                            $tmp['id'] = $blockRegion->id;
                            break;
                        }
                    }
                    $data[$column][] = $tmp;
                }
            } else {
                $data[$column] = $value;
            }
        }

        if ($block->isNew()) {
            $data['handler'] = 'Block';
            $block->set('handler', 'Block');
        }

        $validator = $block->handler !== 'Block' ? 'widget' : 'custom';
        return $this->Blocks->patchEntity($block, $data, ['validate' => $validator, 'entity' => $block]);
    }
}
