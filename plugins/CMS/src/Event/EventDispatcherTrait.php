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
use CMS\Event\EventDispatcher;

/**
 * Provides trigger() method for dispatching events.
 *
 * @see CMS\Event\EventDispatcher
 */
trait EventDispatcherTrait
{

    /**
     * Triggers the given event name. This method provides a shortcut for:
     *
     * ```php
     * $this->trigger('EventName', $arg1, $arg2, ..., $argn);
     * ```
     *
     * You can provide a subject to use by passing an array as first arguments where
     * the first element is the event name and the second one is the subject, if no
     * subject is given `$this` will be used by default:
     *
     * ```php
     * $this->trigger(['GetTime', new MySubject()], $arg_0, $arg_1, ..., $arg_n);
     * ```
     *
     * You can also indicate an EventDispatcher instance to use by prefixing the
     * event name with `<InstanceName>::`, for instance:
     *
     * ```php
     * $this->trigger('Blog::EventName', $arg1, $arg2, ..., $argn);
     * ```
     *
     * This will use the EventDispacher instance named `Blog` and will trigger the
     * event `EventName` within that instance.
     *
     * @param array|string $eventName The event name to trigger
     * @return \Cake\Event\Event The event object that was fired
     * @see CMS\Event\EventDispatcher::trigger()
     */
    public function trigger($eventName)
    {
        if (is_string($eventName)) {
            $eventName = [$eventName, $this];
        }

        $data = func_get_args();
        array_shift($data);
        if (strpos($eventName[0], '::') > 0) {
            list($instance, $eventName[0]) = explode('::', $eventName[0]);

            return EventDispatcher::instance($instance)->triggerArray($eventName, $data);
        }

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
     * @return \CMS\Event\EventDispatcher
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
     * @see CMS\Event\EventDispatcher::triggered()
     */
    public function triggered($eventName = null)
    {
        return EventDispatcher::instance()->triggered($eventName);
    }
}
