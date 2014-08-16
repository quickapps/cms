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
 * Roles manager controller.
 *
 * Provides full CRUD for roles.
 */
class RolesController extends AppController {

/**
 * Shows a list of all available roles.
 *
 * @return void
 */
	public function index() {
		$this->loadModel('User.Acos');
		$roles = $this->Acos->Roles->find()->all();
		$this->set(compact('roles'));
	}

/**
 * Edits the given role.
 *
 * @return void
 */
	public function edit($id) {

	}

}
