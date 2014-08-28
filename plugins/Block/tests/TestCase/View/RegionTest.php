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
namespace Block\Test\TestCase\View;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use Block\View\Helper\BlockHelper;
use Block\View\Helper\RegionHelper;
use Block\View\Region;

/**
 * RegionTest class.
 */
class RegionTest extends TestCase {

	public $regions = [];

	public function setUp() {
		parent::setUp();
		$View = new View();
		$View->theme = option('front_theme');
		$View->Region = new RegionHelper($View);
		$options = ['fixMissing' => false];

		$this->regions = [
			new Region($View, 'left-sidebar', $options),
			new Region($View, 'right-sidebar', $options),
			new Region($View, 'footer', $options),
		];
	}

	public function testGetName() {
		$this->assertEquals('left-sidebar', $this->regions[0]->getName());
		$this->assertEquals('right-sidebar', $this->regions[1]->getName());
		$this->assertEquals('footer', $this->regions[2]->getName());
	}

}
