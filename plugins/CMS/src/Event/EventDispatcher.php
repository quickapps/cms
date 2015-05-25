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
namespace CMS\Event;

use Cake\Event\Event;
use Cake\Event\EventManager;

/**
 * Provides trigger() method for dispatching events.
 *
 * @link http://book.quickappscms.org/2.0/developers/events-system.html
 */
class EventDispatcher
{

    /**
     * Holds a list of all instances.
     *
     * @var array
     */
    protected static $_instances = [];

    /**
     * Holds a list of all the events that were fired.
     *
     * @var array
     */
    protected $_log = [];

    /**
     * EventManager used by this instance.
     *
     * @var \Cake\Event\EventManager
     */
    protected $_eventManager = [];

    /**
     * Constructor.
     *
     */
    public function __construct()
    {
        $this->_eventManager = new EventManager();
    }

    /**
     * Gets or sets event manager instance associated to this dispatcher.
     *
     * @param \Cake\Event\EventManager|null $eventManager The instance to set
     * @return \Cake\Event\EventManager
     */
    public function eventManager(EventManager $eventManager = null)
    {
        if ($eventManager !== null) {
            $this->_eventManager = $eventManager;
        }
        return $this->_eventManager;
    }

    /**
     * Gets an instance of this class.
     *
     * @param string $name Name of the Event Dispatcher instance to get, if does not
     *  exists a new instance will be created and registered
     * @return \CMS\Event\EventDispatcher
     */
    public static function instance($name = 'default')
    {
        if (!isset(static::$_instances[$name])) {
            static::$_instances[$name] = new EventDispatcher();
        }
        return static::$_instances[$name];
    }

    /**
     * Trigger the given event name.
     *
     * ### Usage:
     *
     * ```php
     * EventDispatcher::instance()->trigger('GetTime', $arg0, $arg1, ..., $argn);
     * ```
     *
     * Your Event Listener must implement:
     *
     * ```php
     * public function implementedEvents()
     * {
     *     return ['GetTime' => 'handlerForGetTime'];
     * }
     *
     * public function handlerForGetTime(Event $event, $arg0, $arg1, ..., $argn)
     * {
     *     // logic
     * }
     * ```
     *
     * You can provide a subject to use by passing an array as first arguments where
     * the first element is the event name and the second one is the subject:
     *
     * ```php
     * EventDispatcher::instance()
     *     ->trigger(['GetTime', new MySubject()], $arg0, $arg1, ..., $argn);
     * ```
     *
     * If no subject is given an instance of "EventDispatcher" class will be used by
     * default.
     *
     * @param array|string $eventName The event name to trigger
     * @return \Cake\Event\Event The event object that was triggered
     */
    public function trigger($eventName)
    {
        $data = func_get_args();
        array_shift($data);
        $event = $this->_prepareEvent($eventName, $data);
        $this->_log($event->name());
        $this->_eventManager->dispatch($event);
        return $event;
    }

    /**
     * Similar to "trigger()" but this method expects that data is given as an
     * associative array instead of function arguments.
     *
     * ### Usage:
     *
     * ```php
     * EventDispatcher::instance()->triggerArray('myEvent', [$data1, $data2]);
     * ```
     *
     * Which is equivalent to:
     *
     * ```php
     * EventDispatcher::instance()->trigger('myEvent', $data1, $data2);
     * ```
     *
     * @param array|string $eventName The event name to trigger
     * @param array $data Information to be passed to event listener
     * @return \Cake\Event\Event The event object that was triggered
     */
    public function triggerArray($eventName, array $data = [])
    {
        $event = $this->_prepareEvent($eventName, $data);
        $this->_log($event->name());
        $this->_eventManager->dispatch($event);
        return $event;
    }

    /**
     * Retrieves the number of times an event was triggered, or the complete
     * list of events that were triggered.
     *
     * @param string|null $eventName The name of the event, if null returns the entire
     *  list of event that were fired
     * @param bool $sort If first argument is null set this to true to sort the list.
     *  Defaults to true
     * @return int|array
     */
    public function triggered($eventName = null, $sort = true)
    {
        if ($eventName === null) {
            if ($sort) {
                arsort($this->_log, SORT_NATURAL);
            }
            return $this->_log;
        }
        if (isset($this->_log[$eventName])) {
            return $this->_log[$eventName];
        }
        return 0;
    }

    /**
     * Prepares the event object to be triggered.
     *
     * @param array|string $eventName The event name to trigger
     * @param array $data Data to be passed to event listener method
     * @return \Cake\Event\Event
     */
    protected function _prepareEvent($eventName, array $data = [])
    {
        if (is_array($eventName)) {
            list($eventName, $subject) = $eventName;
        } else {
            $subject = new EventDispatcher();
        }

        return new Event($eventName, $subject, $data);
    }

    /**
     * Logs the given event.
     *
     * @param string $eventName The event name to log
     * @return void
     */
    protected function _log($eventName)
    {
        if (isset($this->_log[$eventName])) {
            $this->_log[$eventName]++;
        } else {
            $this->_log[$eventName] = 1;
        }
    }
}
