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
namespace Block\Test\TestCase\View;

use Block\View\Helper\BlockHelper;
use Block\View\Region;
use Cake\ORM\Entity;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * RegionTest class.
 */
class RegionTest extends TestCase
{

    /**
     * Fixtures.
     *
     * @var array
     */
    public $fixtures = [
        'app.plugins',
        'app.blocks',
        'app.block_regions',
    ];

    /**
     * Regions used for testing
     *
     * @var array
     */
    public $regions = [];

    /**
     * setUp.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $View = new View();
        $View->theme = option('front_theme');
        $options = ['fixMissing' => false];

        $this->regions = [
            new Region($View, 'left-sidebar', $options),
            new Region($View, 'right-sidebar', $options),
            new Region($View, 'footer', $options),
        ];

        $this->regions[0]->blocks(collection([
            new Entity(['region' => new Entity(['region' => 'left-sidebar'])]),
            new Entity(['region' => new Entity(['region' => 'left-sidebar'])]),
            new Entity(['region' => new Entity(['region' => 'left-sidebar'])]),
        ]));

        $this->regions[1]->blocks(collection([
            new Entity(['region' => new Entity(['region' => 'right-sidebar'])]),
            new Entity(['region' => new Entity(['region' => 'right-sidebar'])]),
        ]));

        $this->regions[2]->blocks(collection([
            new Entity(['region' => new Entity(['region' => 'footer'])]),
            new Entity(['region' => new Entity(['region' => 'footer'])]),
            new Entity(['region' => new Entity(['region' => 'footer'])]),
            new Entity(['region' => new Entity(['region' => 'footer'])]),
            new Entity(['region' => new Entity(['region' => 'footer'])]),
        ]));
    }

    /**
     * test name() method.
     *
     * @return void
     */
    public function testName()
    {
        $this->assertEquals('left-sidebar', $this->regions[0]->name());
        $this->assertEquals('right-sidebar', $this->regions[1]->name());
        $this->assertEquals('footer', $this->regions[2]->name());
    }

    /**
     * test theme() method.
     *
     * @return void
     */
    public function testGetTheme()
    {
        $this->assertEquals('FrontendTheme', $this->regions[0]->theme('name'));
        $this->assertEquals('Frontend Theme', $this->regions[0]->theme('human_name'));
    }

    /**
     * test count() method.
     *
     * @return void
     */
    public function testCount()
    {
        $this->assertEquals(3, $this->regions[0]->count());
        $this->assertEquals(2, $this->regions[1]->count());
        $this->assertEquals(5, $this->regions[2]->count());
    }

    /**
     * test merge() method.
     *
     * @return void
     */
    public function testMerge()
    {
        $this->setUp();
        $this->assertEquals(5, $this->regions[0]->merge($this->regions[1], false)->count());

        $this->setUp();
        $this->assertEquals(7, $this->regions[2]->merge($this->regions[1], false)->count());

        $this->setUp();
        $this->assertEquals(3, $this->regions[0]->merge($this->regions[0], false)->count());

        $this->setUp();
        $this->assertEquals(5, $this->regions[2]->merge($this->regions[2], false)->count());
    }

    /**
     * test homogenize() method.
     *
     * @return void
     */
    public function testHomogenize()
    {
        $this->regions[0]->merge($this->regions[1], false);
        $this->regions[0]->homogenize();
        $regions = [];
        foreach ($this->regions[0]->blocks() as $block) {
            $regions[] = $block->region->region;
        }
        $this->assertEquals(['left-sidebar'], array_unique($regions));
    }
}
