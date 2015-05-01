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
use Cake\Event\EventManager;

/**
 * Provides trigger() method for dispatching events.
 *
 * @link http://api.quickappscms.org/book/developers/events-system.html
 */
class EventDispatcher
{

    /**
     * Holds a list of all the events that were fired.
     *
     * @var array
     */
    protected static $_log = [];

    /**
     * Trigger the given event name.
     *
     * You can provide a context to use by passing an array as first arguments where
     * the first element is the event name and the second one is the context:
     *
     * ```php
     * EventDispatcher::trigger(['GetTime', new ContextObject()], ['arg0' => 'val0', ...]);
     * ```
     *
     * If no context is given an instance of "EventDispatcher" class will be used by
     * default.
     *
     * @param array|string $eventName The event name to trigger
     * @param array $args Associative array of argument to pass to the Event handler method
     * @return \Cake\Event\Event The event object that was fired
     */
    public static function trigger($eventName, $args = [])
    {
        if (is_array($eventName)) {
            list($eventName, $context) = $eventName;
        } else {
            $context = new EventDispatcher();
        }

        static::_log($eventName);
        $event = new Event($eventName, $context, $args);
        EventManager::instance()->dispatch($event);
        return $event;
    }

    /**
     * Retrieve the number of times an event was triggered, or the complete list
     * of events that were triggered.
     *
     * @param string|null $eventName The name of the event, if null returns the entire
     *  list of event that were fired
     * @param bool $sort If first argument is null set this to true to sort the list.
     *  Defaults to true
     * @return int|array
     */
    public static function triggered($eventName = null, $sort = true)
    {
        if ($eventName === null) {
            if ($sort) {
                arsort(static::$_log, SORT_NATURAL);
            }
            return static::$_log;
        }
        if (isset(static::$_log[$eventName])) {
            return static::$_log[$eventName];
        }
        return 0;
    }

    /**
     * Logs the given event.
     *
     * @param string $eventName The event name to log
     * @return void
     */
    protected static function _log($eventName)
    {
        if (isset(static::$_log[$eventName])) {
            static::$_log[$eventName]++;
        } else {
            static::$_log[$eventName] = 1;
        }
    }
}
