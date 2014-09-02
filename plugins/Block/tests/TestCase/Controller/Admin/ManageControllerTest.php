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
class ManageControllerTest extends ControllerTestCase {

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
	public function setUp() {
		parent::setUp();
		$this->Controller = $this->generate('Block.Admin/Manage', ['components' => ['Auth', 'Flash']]);
	}

/**
 * test index action.
 *
 * @return void
 */
	public function testIndex() {
		$vars = $this->testAction('/admin/block/manage', ['return' => 'vars']);
		$this->assertTrue(!empty($vars));
	}

/**
 * test add action.
 *
 * @return void
 */
	public function testAddFail() {
		$this->Controller
			->Flash
			->expects($this->once())
			->method('__call')
			->with($this->equalTo('danger'));

		$this->testAction('/admin/block/manage/add', [
			'method' => 'POST',
			'data' => ['a' => 'b'],
		]);
	}

/**
 * test edit action.
 *
 * @return void
 */
	public function testEdit() {
		$vars = $this->testAction('/admin/block/manage/edit/1', ['return' => 'vars']);
		$this->assertTrue(
			isset($vars['block']) &&
			($vars['block'] instanceof \Block\Model\Entity\Block) &&
			$vars['block']->id === 1
		);
	}

}
