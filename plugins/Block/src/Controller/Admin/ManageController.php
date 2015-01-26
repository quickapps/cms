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
        $front = $back = $unused = $frontIds = $backIds = [];
        $frontThemeName = $backThemeName = null;

        if ($this->request->isPost()) {
            if (!empty($this->request->data['regions'])) {
                foreach ($this->request->data['regions'] as $theme => $regions) {
                    foreach ($regions as $region => $ids) {
                        $ordering = 0;
                        foreach ($ids as $id) {
                            $blockRegion = $this
                                ->Blocks
                                ->BlockRegions
                                ->newEntity(['id' => $id, 'theme' => $theme, 'region' => $region, 'ordering' => $ordering]);
                            $this->Blocks->BlockRegions->save($blockRegion);
                            $ordering++;
                        }
                    }
                }

                $this->Flash->success(__d('block', 'Blocks ordering updated!'));
            }
            $this->redirect(['plugin' => 'Block', 'controller' => 'manage', 'action' => 'index']);
        }

        foreach (['front', 'back'] as $type) {
            $theme = option("{$type}_theme");
            ${"{$type}ThemeName"} = Plugin::info($theme)['human_name'];
            $regions = Plugin::info($theme, true)['composer']['extra']['regions'];
            foreach ($regions as $regionSlug => $regionName) {
                $blocks = $this->Blocks->find()
                    ->matching('BlockRegions', function ($q) use ($regionSlug, $theme) {
                        return $q->where([
                            'BlockRegions.theme' => $theme,
                            'BlockRegions.region' => $regionSlug,
                        ]);
                    })
                    ->where(['Blocks.status' => 1])
                    ->all()
                    ->filter(function ($block) {
                        // we have to remove all blocks that belongs to a disabled plugin
                        if ($block->handler === 'Block') {
                            return true;
                        }
                        foreach (listeners() as $listener) {
                            if (str_starts_with($listener, "Block.{$block->handler}")) {
                                return true;
                            }
                        }
                        return false;
                    })
                    ->sortBy(function ($block) {
                        return $block->region->ordering;
                    }, SORT_ASC);
                ${$type}[$regionName] = $blocks;

                foreach ($blocks as $block) {
                    ${"{$type}Ids"}[] = $block->id;
                }
            }
        }

        $notIn = array_unique(array_merge($frontIds, $backIds));
        $notIn = empty($notIn) ? ['0'] : $notIn;
        $unused = $this->Blocks->find()
            ->where([
                'OR' => [
                    'Blocks.id NOT IN' => $notIn,
                    'Blocks.status' => 0,
                ]
            ])
            ->all()
            ->filter(function ($block) {
                // we have to remove all blocks that belongs to a disabled plugin
                if ($block->handler === 'Block') {
                    return true;
                }

                foreach (listeners() as $listener) {
                    if (str_starts_with($listener, "Block.{$block->handler}")) {
                        return true;
                    }
                }

                return false;
            });

        $this->set(compact('frontThemeName', 'backThemeName', 'front', 'back', 'unused'));
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

        if (!empty($this->request->data)) {
            $data = $this->_prepareData();
            $data['handler'] = 'Block';
            $block = $this->Blocks->patchEntity($block, $data);
            $block->calculateDelta();

            if ($this->Blocks->save($block, ['validate' => 'custom'])) {
                $this->Flash->success(__d('block', 'Block created.'));
                $this->redirect(['plugin' => 'Block', 'controller' => 'manage', 'action' => 'edit', $block->id]);
            } else {
                $this->Flash->danger(__d('block', 'Block could not be created, please check your information.'));
            }
        }

        $this->_setLanguages();
        $this->_setRoles();
        $this->_setRegions();
        $this->set('block', $block);
        $this->Breadcrumb
            ->push('/admin/system/structure')
            ->push(__d('block', 'Manage Blocks'), ['plugin' => 'Block', 'controller' => 'manage', 'action' => 'index'])
            ->push(__d('block', 'Create New Block'), '#');
    }

    /**
     * Edits the given block by ID.
     *
     * @param string $id Block's ID
     * @return void
     * @throws \Cake\ORM\Exception\RecordNotFoundException if no block is not found
     */
    public function edit($id)
    {
        $this->loadModel('Block.Blocks');
        $block = $this->Blocks->get($id, ['contain' => ['BlockRegions', 'Roles']]);
        $block->accessible('handler', false);

        if (!empty($this->request->data)) {
            $data = $this->_prepareData($block);
            $block->accessible('id', false);
            $block = $this->Blocks->patchEntity($block, $data);
            $validate = $block->handler != 'Block' ? 'default' : 'custom';

            if ($this->Blocks->save($block, ['validate' => $validate])) {
                $this->Flash->success(__d('block', 'Block updated!'));
                $this->redirect(['plugin' => 'Block', 'controller' => 'manage', 'action' => 'edit', $block->id]);
            } else {
                $this->Flash->danger(__d('block', 'Block could not be updated, please check your information.'));
            }
        }

        $this->set('block', $block);
        $this->_setLanguages();
        $this->_setRoles();
        $this->_setRegions($block);
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
            ->where(['id' => $id, 'handler' => 'Block'])
            ->first();

        if ($block) {
            if ($this->Blocks->delete($block)) {
                $this->Flash->success(__d('block', 'Block was successfully removed!'));
            } else {
                $this->Flash->danger(__d('block', 'Block could not be removed'));
            }
        } else {
            $this->Flash->warning(__d('block', 'Block not found!'));
        }

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
        $original = $this->Blocks->get($id);
        $new = $this->Blocks->newEntity($original->toArray());
        $new->set([
            'id' => null,
            'copy_id' => $original->id,
            'delta' => null,
        ]);
        $new->calculateDelta();

        if ($this->Blocks->save($new)) {
            $this->Flash->success(__d('block', 'Block has been duplicated, it can be found under the "Unused or Unassigned" section.'));
        } else {
            $this->Flash->danger(__d('block', 'Block could not be duplicated, please try again.'));
        }

        $this->redirect($this->referer() . '#unused-blocks');
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
        $regions = Plugin::collection(true)
            ->match(['isTheme' => true])
            ->map(function ($theme, $key) use ($block) {
                $value = '';

                if ($block !== null && $block->has('region')) {
                    foreach ($block->region as $blockRegion) {
                        if ($blockRegion->theme == $key) {
                            $value = $blockRegion->region;
                            break;
                        }
                    }
                }

                return [
                    'theme' => $key,
                    'description' => $theme['composer']['description'],
                    'regions' => (array)$theme['composer']['extra']['regions'],
                    'value' => $value,
                ];
            });
        $this->set('regions', $regions);
    }

    /**
     * Prepares incoming data from Form's POST.
     *
     * Any input field that is not a column in the "blocks" table will be moved
     * to the "settings" column. For example, `random_name` becomes `settings.random_name`.
     *
     * @param null|\Block\Model\Entity\Block $block Optional entity to properly
     * fetch associated data when updating
     * @param array $ignore List of key to ignore, will not be moved under `settings`
     * @return array
     */
    protected function _prepareData($block = null, $ignore = [])
    {
        $this->loadModel('Block.Blocks');
        $ignore = array_merge($ignore, ['region', 'roles']);
        $data = ['region' => []];
        $columns = array_merge($this->Blocks->schema()->columns(), $ignore);

        foreach ($this->request->data as $coulumn => $value) {
            if (in_array($coulumn, $columns)) {
                if ($coulumn == 'region') {
                    foreach ($value as $theme => $region) {
                        if (!$block) {
                            $data[$coulumn][] = [
                                'theme' => $theme,
                                'region' => $region
                            ];
                        } else {
                            $tmp = [
                                'theme' => $theme,
                                'region' => $region
                            ];

                            foreach ($block->region as $blockRegion) {
                                if ($blockRegion->theme == $theme) {
                                    $tmp['id'] = $blockRegion->id;
                                    break;
                                }
                            }
                            $data[$coulumn][] = $tmp;
                        }
                    }
                } else {
                    $data[$coulumn] = $value;
                }
            } else {
                $data['settings'][$coulumn] = $value;
            }
        }

        return $data;
    }
}
