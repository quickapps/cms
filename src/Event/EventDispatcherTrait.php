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
namespace QuickApps\Event;

use Cake\Event\Event;
use QuickApps\Event\EventDispatcher;

/**
 * Provides trigger() method for dispatching events.
 *
 * @see QuickApps\Event\EventDispatcher
 */
trait EventDispatcherTrait
{

    /**
     * Triggers the given event name. This method provides a shortcut for:
     *
     * ```php
     * EventDispatcher::instance()->trigger('EventName', $arg1, $arg2, ..., $argn);
     * ```
     *
     * You can provide a subject to use by passing an array as first arguments where
     * the first element is the event name and the second one is the subject:
     *
     * ```php
     * $this->trigger(['GetTime', new MySubject()], $arg_0, $arg_1, ..., $arg_n);
     * ```
     *
     * If no subject is given `$this` will be used by default.
     *
     * @param array|string $eventName The event name to trigger
     * @return \Cake\Event\Event The event object that was fired
     * @see QuickApps\Event\EventDispatcher::trigger()
     */
    public function trigger($eventName)
    {
        if (is_string($eventName)) {
            $eventName = [$eventName, $this];
        }

        $data = func_get_args();
        array_shift($data);
        return EventDispatcher::instance()->triggerArray($eventName, $data);
    }

    /**
     * Gets an instance of the given Event Dispatcher name.
     *
     * ### Usage:
     *
     * ```php
     * $this->eventDispatcher('myDispatcher')
     *     ->trigger('MyEventName', $argument)
     *     ->result;
     * ```
     *
     * @param string $name Name of the dispatcher to get, defaults to 'default'
     * @return \QuickApps\Event\EventDispatcher
     */
    public function eventDispatcher($name = 'default')
    {
        return EventDispatcher::instance($name);
    }

    /**
     * Retrieves the number of times an event was triggered, or the complete list
     * of events that were triggered.
     *
     * @param string|null $eventName The name of the event, if null returns the
     *  entire list of event that were fired
     * @return int|array
     * @see QuickApps\Event\EventDispatcher::triggered()
     */
    public function triggered($eventName = null)
    {
        return EventDispatcher::instance()->triggered($eventName);
    }
}
