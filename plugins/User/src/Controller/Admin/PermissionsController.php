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
namespace User\Controller\Admin;

use User\Controller\AppController;
use QuickApps\Core\Plugin;

/**
 * Permissions manager controller.
 *
 * Provides full CRUD for permissions.
 */
class PermissionsController extends AppController {

/**
 * Shows tree list of ACOS grouped by plugin.
 *
 * @return void
 */
	public function index() {
		$this->loadModel('User.Acos');
		$tree = $this->Acos
			->find('threaded')
			->order(['lft' => 'ASC'])
			->all();
		$this->set(compact('tree'));
	}

/**
 * Shows the permissions table for the given ACO.
 *
 * @return void
 */
	public function aco($aco_id) {
		$this->loadModel('User.Acos');
		$aco = $this->Acos->get($aco_id, ['contain' => ['Roles']]);

		if (!empty($this->request->data['roles'])) {
			$aco = $this->Acos->patchEntity($aco, $this->request->data);
			$save = $this->Acos->save($aco);

			if (!$this->request->isAjax()) {
				if ($save) {
					$this->Flash->success(__d('user', 'Permissions were successfully saved!'));
				} else {
					$this->Flash->danger(__d('user', 'Permissions could not be saved'));
				}
			}
		}

		$roles = $this->Acos->Roles->find('list');
		$this->set(compact('aco', 'roles'));
	}

}
