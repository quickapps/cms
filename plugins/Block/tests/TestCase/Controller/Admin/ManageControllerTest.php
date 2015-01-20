<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace Block\Test\TestCase\Controller\Admin;

use Cake\TestSuite\ControllerTestCase;

/**
 * ManageControllerTest class.
 */
class ManageControllerTest extends ControllerTestCase
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
        $this->Controller = $this->generate('Block.Admin/Manage', ['components' => ['Auth', 'Flash']]);
    }

/**
 * test index action.
 *
 * @return void
 */
    public function testIndex()
    {
        $vars = $this->testAction('/admin/block/manage', ['return' => 'vars']);
        $this->assertNotEmpty($vars['front']);
        $this->assertNotEmpty($vars['back']);
    }

/**
 * test add action.
 *
 * @return void
 */
    public function testAdd()
    {
        $this->testAction('/admin/block/manage/add', [
            'method' => 'POST',
            'data' => [
                'title' => 'test block',
                'description' => 'this is a test block',
                'status' => 1,
                'body' => 'What a block!',
                'locale' => '',
                'region' => [],
                'visibility' => 'except',
                'pages' => ''
            ],
        ]);
        $block = $this->Controller->Blocks
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
        $vars = $this->testAction('/admin/block/manage/edit/1', ['return' => 'vars']);
        $this->assertNotEmpty($vars['block']);
    }

/**
 * test edit + save action.
 *
 * @return void
 */
    public function testEditSave()
    {
        $this->testAction('/admin/block/manage/edit/1', [
            'method' => 'POST',
            'data' => [
                'title' => 'New Title',
                'description' => 'this is a test block',
                'status' => 1,
                'body' => 'What a block!',
                'visibility' => 'only',
                'pages' => '/',
            ],
        ]);
        $block = $this->Controller->Blocks->get(1);
        $this->assertEquals('New Title', $block->title);
    }

/**
 * test that non-custom blocks cannot be deleted.
 *
 * @return void
 */
    public function testDeleteNonCustom()
    {
        $this->testAction('/admin/block/manage/delete/1');
        $block = $this->Controller->Blocks
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
        $this->testAction('/admin/block/manage/duplicate/1');
        $block = $this->Controller->Blocks
            ->find()
            ->where(['copy_id' => 1])
            ->limit(1)
            ->first();
        $this->assertNotEmpty($block);
    }
}
