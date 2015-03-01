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
namespace Locale\Test\TestCase\Controller\Admin;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

/**
 * ManageControllerTest class.
 */
class ManageControllerTest extends IntegrationTestCase
{

    /**
     * Fixtures.
     *
     * @var array
     */
    public $fixtures = [
        'app.acos',
        'app.block_regions',
        'app.blocks',
        'app.blocks_roles',
        'app.comments',
        'app.entities_terms',
        'app.field_instances',
        'app.field_values',
        'app.languages',
        'app.menu_links',
        'app.menus',
        //'app.node_revisions',
        //'app.nodes',
        //'app.node_types',
        'app.options',
        'app.permissions',
        'app.plugins',
        'app.roles',
        //'app.search_datasets',
        //'app.terms',
        //'app.users',
        'app.users_roles',
        //'app.vocabularies',
    ];

    /**
     * setUp().
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->session(mockUserSession());
    }

    /**
     * test index action.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->get('/admin/locale/manage/');
        $this->assertResponseOk();
    }

    /**
     * test edit action.
     *
     * @return void
     */
    public function testEdit()
    {
        $this->get('/admin/locale/manage/edit/1');
        $this->assertResponseOk();
    }

    /**
     * test move action.
     *
     * @return void
     */
    public function testMove()
    {
        $this->get('/admin/locale/manage/move/1/down');
        $ids = TableRegistry::get('Locale.Languages')
            ->find()
            ->select(['id', 'ordering'])
            ->order(['ordering' => 'ASC'])
            ->extract('id')
            ->toArray();
        $this->assertEquals([2, 1], $ids);
    }
}
