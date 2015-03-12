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
namespace Menu\Test\TestCase\View;

use Cake\TestSuite\TestCase;
use Menu\View\Helper\MenuHelper;
use QuickApps\View\View;

/**
 * MenuHelperTest class.
 */
class MenuHelperTest extends TestCase
{

    /**
     * Fixtures.
     *
     * @var array
     */
    public $fixtures = [
        'app.menus',
        'app.menu_links',
        'app.blocks',
        'app.block_regions',
    ];

    /**
     * MenuHelper instance.
     *
     * @var \Menu\View\Helper\MenuHelper
     */
    public $helper = null;

    /**
     * setUp.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $view = new View();
        $view->theme = option('front_theme');
        $this->helper = new MenuHelper($view);
    }

    /**
     * test render() method.
     *
     * @return void
     */
    public function testRender()
    {
        $items = [
            [
                'title' => 'Link 1', 'url' => 'http://www.example.com/link1', 'expanded' => true,
                'children' => [
                    ['title' => 'Link 1.1', 'url' => 'http://www.example.com/link1.1'],
                    ['title' => 'Link 1.2', 'url' => 'http://www.example.com/link1.2'],
                ]
            ],
            ['title' => 'Link 2', 'url' => 'http://www.example.com/link2']
        ];

        /*
        $expected = [
            ['ul' => true],
                ['li' => true],
                    ['a' => ['href' => '']], ['span' => true], 'Link 1', '/span', '/a',
                    ['ul' => true],
                        ['li' => true],
                            ['a' => ['href' => 'http://www.example.com/link1.1']], ['span' => true], 'Link 1.1', '/span', '/a',
                        '/li',
                        ['li' => true],
                            ['a' => ['href' => 'http://www.example.com/link1.2']], ['span' => true], 'Link 1.2', '/span', '/a',
                        '/li',
                    '/ul',
                '/li',

                ['li' => true],
                    ['a' => ['href' => 'http://www.example.com/link2']], ['span' => true], 'Link 2', '/span', '/a',
                '/li',
            '/ul'
        ];
        */

        $result = $this->helper->render($items);
        $this->assertTextContains('href="http://www.example.com/link1"', $result);
        $this->assertTextContains('href="http://www.example.com/link1.1"', $result);
        $this->assertTextContains('href="http://www.example.com/link1.2"', $result);
        $this->assertTextContains('href="http://www.example.com/link2"', $result);
    }
}
