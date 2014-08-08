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

use Block\Controller\AppController;

/**
 * Block manager controller.
 *
 * Allow CRUD for menus.
 */
class ManageController extends AppController {

/**
 * Shows a list of all the nodes.
 *
 * @return void
 */
	public function index() {
		$this->loadModel('Menu.Menus');
		$menus = $this->Menus->find()->all();

		$this->set('menus', $menus);
		$this->Breadcrumb->push('/admin/menu/manage');
	}

}
