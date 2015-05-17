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
namespace CMS\Test\TestCase\Event;

use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\TestSuite\TestCase;
use CMS\Event\EventDispatcher;

/**
 * EventDispatcherTest class.
 */
class EventDispatcherTest extends TestCase
{

    /**
     * EventManager instance.
     *
     * @var \Cake\Event\EventManager
     */
    protected $_eventManager = null;

    /**
     * setUp().
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->_eventManager = EventManager::instance();
        if (!$this->_eventManager->listeners('Test.hook')) {
            $this->_eventManager->on('Test.hook', function ($event) {
                return 'event response';
            });
        }
    }

    /**
     * test triggered() method.
     *
     * @return void
     */
    public function testTriggered()
    {
        $this->assertTrue(EventDispatcher::instance()->triggered('unexisting') === 0);
        EventDispatcher::instance()->trigger('Test.hook');
        $this->assertTrue(EventDispatcher::instance()->triggered('Test.hook') === 1);
    }

    /**
     * test trigger() method.
     *
     * @return void
     */
    public function testTrigger()
    {
        $return = EventDispatcher::instance()->trigger('Test.hook');
        $this->assertTrue($return instanceof Event);
        $this->assertEquals($return->result, 'event response');
    }
}
