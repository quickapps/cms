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
namespace Taxonomy\Controller\Admin;

use Taxonomy\Controller\AppController;

/**
 * Taxonomy manager controller.
 *
 * Redirects to Vocabularies controller.
 */
class ManageController extends AppController
{

	/**
	 * Shows a list of all vocabularies.
	 *
	 * @return void
	 */
    public function index()
    {
        $this->redirect(['plugin' => 'Taxonomy', 'controller' => 'vocabularies', 'action' => 'index']);
    }
}
