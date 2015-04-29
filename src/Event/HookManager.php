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
 * Provides trigger() & alter() methods.
 *
 * QuickAppsCMS's event system is built over
 * [cake's event system](http://book.cakephp.org/3.0/en/core-libraries/events.html),
 * and allows plugins to communicate with the entire system or other plugins.
 *
 * QuickAppsCMS's Event system is composed of three primary elements:
 *
 * - `Event Listener`: An event listeners class implementing the EventListener
 *    interface.
 * - `Event Handler`: A method in your your listener class which take care of a
 *    single event.
 * - `Event`: Name of the event. e.g.: `FormHelper.input`.
 *
 * An Event Listener class, may listen to many Events. But a Event Handler can only
 * responds to a single Event.
 *
 * Your `Event Listener` class must implement `\Cake\Event\EventListener` interface
 * and provide the `implementedEvents()` method. This method must return an
 * associative array with all Event names that the class will handle. For example:
 * `User.beforeLogin` Event name will respond to:
 *
 * ```php
 * $this->trigger('User.beforeLogin', ...);
 * ```
 *
 * You can provide an unlimited number of arguments which are treated by value.
 *
 * ***
 *
 * ## "Hello World!" Example:
 *
 * ```php
 * // Event Listener Class
 *
 * namespace Event;
 *
 * use Cake\Event\Event;
 * use Cake\Event\EventListenerInterface;
 *
 * class MyEventListener implements EventListenerInterface {
 *     public function implementedEvents() {
 *           return [
 *               'Hello' => 'world',
 *           ];
 *     }
 *
 *      public function world(Event $event, $byValue) {
 *         return $byValue . ' world!';
 *     }
 * }
 * ```
 *
 * ***
 *
 * ```php
 * // Wherever you are able to use event() & alter()
 *
 * $hello = 'Hello';
 *
 * echo $this->trigger('Hello', $hello); // out: "Hello world!"
 * echo $this->trigger('Hello', 'hellooo'); // out: "hellooo world!"
 * ```
 *
 * ## Recommended Reading
 *
 * As QuickAppsCMS's hook system is built on top of CakePHP's events system we
 * highly recommend you to take a look at this part of CakePHP's book:
 *
 * [CakePHP's Events System](http://book.cakephp.org/3.0/en/core-libraries/events.html)
 */
class HookManager
{

    /**
     * Holds a list of all the events that were fired.
     *
     * @var array
     */
    protected static $_log = [];

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
     * Trigger the given event name.
     *
     * You can provide a context to use by passing an array as first arguments where
     * the first element is the event name and the second one is the context:
     *
     * ```php
     * HookManager::trigger(['GetTime', new ContextObject()], ['arg0' => 'val0', ...]);
     * ```
     *
     * If no context is given an instance of "HookManager" class will be used by default.
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
            $context = new HookManager();
        }

        static::_log($eventName);
        $event = new Event($eventName, $context, $args);
        EventManager::instance()->dispatch($event);
        return $event;
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
