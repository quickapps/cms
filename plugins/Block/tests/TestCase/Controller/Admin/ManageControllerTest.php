<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since     2.0.0
 * @author     Christopher Castro <chris@quickapps.es
 * @link     http://www.quickappscms.org
 * @license     http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Block\Test\TestCase\Controller\Admin;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;

/**
 * ManageControllerTest class.
 */
class ManageControllerTest extends IntegrationTestCase
{

    /**
     * Controller being tested.
     *
     * @var \Cake\Controller\Controller
     */
    public $Controller;

    /**
     * Fixtures.
     *
     * @var array
     */
    public $fixtures = [
        'app.blocks',
        'app.block_regions',
        'app.blocks_roles',
        'app.permissions',
        'app.acos',
        'app.roles',
        'app.plugins',
        'app.menu_links',
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
        $this->get('/admin/block/manage');
        $this->assertResponseOk();
    }

    /**
     * test add action.
     *
     * @return void
     */
    public function testAdd()
    {
        $this->post('/admin/block/manage/add', [
            'title' => 'test block',
            'description' => 'this is a test block',
            'status' => 1,
            'body' => 'What a block!',
            'locale' => '',
            'region' => [],
            'visibility' => 'except',
            'pages' => ''
        ]);
        $block = TableRegistry::get('Block.Blocks')
            ->find()
            ->where(['title' => 'test block'])
            ->limit(1)
            ->first();

        $this->assertNotEmpty($block);
    }

    /**
     * test edit action.
     *
     * @return void
     */
    public function testEdit()
    {
        $this->get('/admin/block/manage/edit/1');
        $this->assertResponseOk();
    }

    /**
     * test edit + save action.
     *
     * @return void
     */
    public function testEditSave()
    {
        $data = [
            'title' => 'New Title!!',
            'description' => 'this is a test block',
            'status' => 1,
            'body' => 'What a block!',
            'visibility' => 'only',
            'pages' => '/',
        ];
        $this->post('/admin/block/manage/edit/1', $data);
        $query = TableRegistry::get('Block.Blocks')
            ->find()
            ->where(['title' => $data['title']]);
        $this->assertEquals(1, $query->count());
    }

    /**
     * test that non-custom blocks cannot be deleted.
     *
     * @return void
     */
    public function testDeleteNonCustom()
    {
        $this->get('/admin/block/manage/delete/1');
        $block = TableRegistry::get('Block.Blocks')
            ->find()
            ->where(['id' => 1])
            ->limit(1)
            ->first();
        $this->assertNotEmpty($block);
    }

    /**
     * test duplicate action.
     *
     * @return void
     */
    public function testDuplicate()
    {
        $this->get('/admin/block/manage/duplicate/1');
        $block = TableRegistry::get('Block.Blocks')
            ->find()
            ->where(['copy_id' => 1])
            ->limit(1)
            ->first();
        $this->assertNotEmpty($block);
    }
}
