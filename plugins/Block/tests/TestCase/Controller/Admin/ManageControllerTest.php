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
namespace Block\Test\TestCase\Controller\Admin;

use Cake\ORM\TableRegistry;
use CMS\TestSuite\IntegrationTestCase;

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
        //'app.acos',
        'app.block_regions',
        'app.blocks',
        'app.blocks_roles',
        'app.comments',
        'app.content_revisions',
        'app.contents',
        'app.contents_roles',
        'app.content_type_permissions',
        'app.content_types',
        'app.eav_attributes',
        'app.eav_values',
        'app.entities_terms',
        'app.field_instances',
        'app.languages',
        'app.menu_links',
        'app.menus',
        'app.options',
        'app.permissions',
        'app.plugins',
        'app.roles',
        'app.search_datasets',
        'app.terms',
        'app.users',
        'app.users_roles',
        'app.vocabularies',
    ];

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
        $query = TableRegistry::get('Block.Blocks')->find()->where(['title' => 'test block']);
        $this->assertEquals(1, $query->count());
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
        $query = TableRegistry::get('Block.Blocks')->find()->where(['title' => $data['title']]);
        $this->assertEquals(1, $query->count());
    }

    /**
     * test that widget blocks cannot be deleted through administrator section.
     *
     * @return void
     */
    public function testDeleteWidget()
    {
        $this->get('/admin/block/manage/delete/1');
        $query = TableRegistry::get('Block.Blocks')->find()->where(['id' => 1]);
        $this->assertEquals(1, $query->count());
    }

    /**
     * test duplicate action.
     *
     * @return void
     */
    public function testDuplicate()
    {
        $this->get('/admin/block/manage/duplicate/1');
        $query = TableRegistry::get('Block.Blocks')->find()->where(['copy_id' => 1]);
        $this->assertEquals(1, $query->count());
    }
}
