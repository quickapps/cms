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
namespace System\Controller\Admin;

use System\Controller\AppController;

/**
 * Structure controller.
 *
 */
class StructureController extends AppController
{

    /**
     * Index.
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Menu.MenuLinks');
        $links = $this->MenuLinks
            ->find('threaded')
            ->where([
                'parent_id IN' => $this->MenuLinks
                    ->find()
                    ->select(['id'])
                    ->where(['url' => '/admin/system/structure'])
            ])
            ->all();

        $this->title(__d('system', 'Site’s Structure'));
        $this->set('links', $links);
        $this->Breadcrumb->push('/admin/system/structure');
    }
}
