<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Menu\Controller\Admin;

use Menu\Controller\AppController;

/**
 * Block manager controller.
 *
 * Allow CRUD for menus.
 */
class LinksController extends AppController {

/**
 * Shows menu's links as a sortable tree.
 *
 * @param integer $id Menu's ID for which render its links tree
 * @return void
 */
	public function menu($id) {
		$this->loadModel('Menu.Menus');
		$menu = $this->Menus->get($id);
		$links = $this->Menus->MenuLinks->find()
			->where(['menu_id' => $menu->id])
			->order(['lft' => 'ASC'])
			->all()
			->map(function ($link) {
				$link->set('expanded', true);
				return $link;
			})
			->nest('id', 'parent_id');

		if (!empty($this->request->data['tree_order'])) {
			$items = json_decode($this->request->data['tree_order']);

			if ($items) {
				unset($items[0]);
				$entities = [];

				foreach ($items as $key => $item) {
					$link = $this->Menus->MenuLinks->newEntity([
						'id' => $item->item_id,
						'parent_id' => intval($item->parent_id),
						'lft' => ($item->left - 1),
						'rght' => ($item->right - 1),
					]);
					$link->isNew(false);
					$link->dirty('id', false);
					$entities[] = $link;
				}

				$this->Menus->MenuLinks->connection()->transactional(function () use ($entities) {
					foreach ($entities as $entity) {
						$this->Menus->MenuLinks->save($entity, ['validate' => false, 'atomic' => false]);
					}
				});
				// don't trust "left" and "right" values coming from user's POST
				$this->Menus->MenuLinks->addBehavior('Tree', ['scope' => ['menu_id' => $menu->id]]);
				$this->Menus->MenuLinks->recover();
				$this->Flash->success(__d('menu', 'Menu has been reordered'));
			} else {
				$this->Flash->danger(__d('menu', 'Invalid information, check you have JavaScript enabled'));
			}

			$this->redirect($this->referer());
		}

		$disabledIds = $links
			->match(['status' => false])
			->extract('id')
			->toArray();

		$this->set(compact('menu', 'links', 'disabledIds'));
		$this->Breadcrumb
			->push('/admin/menu/manage')
			->push(__d('menu', 'Editing menu'), ['plugin' => 'Menu', 'controller' => 'manage', 'action' => 'edit', $menu->id])
			->push(__d('menu', 'Links'), '#');
	}

/**
 * Add a new link to the given menu.
 *
 * @param integer $id Menu's ID for which add a link
 * @return void
 */
	public function add($menu_id) {
		$this->loadModel('Menu.Menus');
		$menu = $this->Menus->get($menu_id);
		$link = $this->Menus->MenuLinks->newEntity();
		$link->set([
			'activation' => 'auto',
			'status' => 1,
			'menu_id' => $menu_id
		]);
		$this->Menus->MenuLinks->addBehavior('Tree', ['scope' => ['menu_id' => $menu->id]]);

		if ($this->request->data) {
			$link = $this->Menus->MenuLinks->patchEntity($link, $this->request->data, [
				'fieldList' => [
					'parent_id',
					'title',
					'url',
					'description',
					'target',
					'expanded',
					'active',
					'activation',
					'status'
				]
			]);

			if ($this->Menus->MenuLinks->save($link)) {
				$this->Menus->MenuLinks->recover();
				$this->Flash->success(__d('menu', 'Link successfully created!'));

				if (!empty($this->request->data['action_add'])) {
					$this->redirect(['plugin' => 'Menu', 'controller' => 'links', 'action' => 'add', $menu_id]);
				} elseif (!empty($this->request->data['action_menu'])) {
					$this->redirect(['plugin' => 'Menu', 'controller' => 'links', 'action' => 'menu', $menu_id]);
				}
			} else {
				$this->Flash->danger(__d('menu', 'Link could not be saved, please check your information'));
			}
		}

		$parentsTree = $this->Menus->MenuLinks
			->find('treeList', ['spacer' => '--'])
			->map(function($link) {
				if (strpos($link, '-') !== false) {
					$link = str_replace_last('-', '- ', $link);
				}
				return $link;
			});
		$this->set(compact('menu', 'link', 'parentsTree'));
		$this->Breadcrumb
			->push('/admin/menu/manage')
			->push(__d('menu', 'Editing menu'), ['plugin' => 'Menu', 'controller' => 'manage', 'action' => 'edit', $menu_id])
			->push(__d('menu', 'Links'), ['plugin' => 'Menu', 'controller' => 'links', 'action' => 'menu', $menu_id])
			->push(__d('menu', 'Add new link'), '#');
	}

/**
 * Edits the given menu link by ID.
 *
 * @param integer $id Link's ID
 * @return void
 */
	public function edit($id) {
		$this->loadModel('Menu.MenuLinks');
		$link = $this->MenuLinks->get($id, ['contain' => ['Menus']]);

		if (!empty($this->request->data)) {
			$link = $this->MenuLinks->patchEntity($link, $this->request->data, [
				'fieldList' => [
					'title',
					'url',
					'description',
					'target',
					'expanded',
					'active',
					'activation',
					'status'
				]
			]);

			if ($this->MenuLinks->save($link, ['associated' => false])) {
				$this->Flash->success(__d('menu', 'Link has been updated'));
				$this->redirect($this->referer());
			} else {
				$this->Flash->danger(__d('menu', 'Link could not be updated, please check your information'));
			}
		}

		$this->set('link', $link);
		$this->Breadcrumb
			->push('/admin/menu/manage')
			->push(__d('menu', 'Editing menu'), ['plugin' => 'Menu', 'controller' => 'manage', 'action' => 'edit', $link->menu_id])
			->push(__d('menu', 'Links'), ['plugin' => 'Menu', 'controller' => 'links', 'action' => 'menu', $link->menu_id])
			->push(__d('menu', 'Editing link'), '#');
	}

/**
 * Deletes the given link.
 *
 * @param integer $id Link's ID
 * @return void
 */
	public function delete($id) {
		$this->loadModel('Menu.MenuLinks');
		$link = $this->MenuLinks->get($id);
		$this->MenuLinks->addBehavior('Tree', ['scope' => ['menu_id' => $link->menu_id]]);
		$this->MenuLinks->removeFromTree($link);

		if ($this->MenuLinks->delete($link)) {
			$this->Flash->success(__d('menu', 'Link successfully removed!'));
		} else {
			$this->Flash->danger(__d('menu', 'Link could not be removed, please try again'));
		}

		$this->redirect($this->referer());
	}

}
