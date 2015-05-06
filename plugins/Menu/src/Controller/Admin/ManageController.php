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
namespace Menu\Controller\Admin;

use Menu\Controller\AppController;

/**
 * Menu manager controller.
 *
 * Allow CRUD for menus.
 */
class ManageController extends AppController
{

    /**
     * Shows a list of all the menus.
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Menu.Menus');
        $menus = $this->Menus->find()->all();

        $this->title(__d('menu', 'Menus List'));
        $this->set('menus', $menus);
        $this->Breadcrumb->push('/admin/menu/manage');
    }

    /**
     * Adds a new menu.
     *
     * @return void
     */
    public function add()
    {
        $this->loadModel('Menu.Menus');
        $menu = $this->Menus->newEntity();

        if ($this->request->data()) {
            $data = $this->request->data();
            $data['handler'] = 'Menu';
            $menu = $this->Menus->patchEntity($menu, $data, [
                'fieldList' => [
                    'title',
                    'handler',
                    'description',
                    'settings',
                ],
            ]);

            if ($this->Menus->save($menu, ['atomic' => true])) {
                $this->Flash->success(__d('menu', 'Menu has been created, now you can start adding links!'));
                $this->redirect(['plugin' => 'Menu', 'controller' => 'links', 'action' => 'add', $menu->id]);
            } else {
                $this->Flash->danger(__d('menu', 'Menu could not be created, please check your information'));
            }
        }

        $this->title(__d('menu', 'Create New Menu'));
        $this->set('menu', $menu);
        $this->Breadcrumb
            ->push('/admin/menu/manage')
            ->push(__d('menu', 'Creating new menu'), '#');
    }

    /**
     * Edits the given menu by ID.
     *
     * The event `Block.<handler>.validate` will be automatically triggered,
     * so custom menu's (those handled by plugins <> "Menu") can be validated
     * before persisted.
     *
     * @param int $id Menu's ID
     * @return void
     */
    public function edit($id)
    {
        $this->loadModel('Menu.Menus');
        $menu = $this->Menus->get($id);

        if ($this->request->data()) {
            $menu = $this->Menus->patchEntity($menu, $this->request->data(), [
                'fieldList' => [
                    'title',
                    'description',
                    'settings',
                ],
                'validate' => false
            ]);

            if ($this->Menus->save($menu, ['atomic' => true])) {
                $this->Flash->success(__d('menu', 'Menu has been saved!'));
                $this->redirect($this->referer());
            } else {
                $this->Flash->danger(__d('menu', 'Menu could not be saved, please check your information'));
            }
        }

        $this->title(__d('menu', 'Editing Menu'));
        $this->set('menu', $menu);
        $this->Breadcrumb
            ->push('/admin/menu/manage')
            ->push(__d('menu', 'Editing menu {0}', $menu->title), '#');
    }

    /**
     * Removes the given menu by ID.
     *
     * Only custom menus (those created using administration page) can be removed.
     *
     * @param int $id Menu's ID
     * @return void Redirects to previous page
     */
    public function delete($id)
    {
        $this->loadModel('Menu.Menus');
        $menu = $this->Menus->get($id);

        if ($menu->handler === 'Menu' && $this->Menus->delete($menu)) {
            $this->Flash->success(__d('menu', 'Menu has been successfully deleted!'));
        } else {
            $this->Flash->danger(__d('menu', 'Menu could not be deleted, please try again'));
        }

        $this->title(__d('menu', 'Delete Menu'));
        $this->redirect($this->referer());
    }
}
