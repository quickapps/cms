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
 *     $this->trigger('User.beforeLogin', ...);
 *
 * When using the `alert()` method Event names are prefixed with with the `Alter.`
 * word. For example, the Event name `Alter.FormHelper.textarea` will respond to:
 *
 *     $this->alter('FormHelper.textarea', $arg_0, $arg_1, ..., $arg_14);
 *
 * When using `alter()` you can provide **up to 15 arguments by reference**
 *
 * ---
 *
 * In the other hand, when using the `trigger()` method no prefixes are added to
 * the Event name so for example, the event name `Say.HelloWorld` will respond to:
 *
 *     $this->trigger('Say.HelloWorld', $arg_0, $arg_1, ..., $arg_n);
 *
 * You can provide an unlimited number of arguments which are treated by value,
 * and NOT by reference as `alter()` does.
 *
 * ***
 *
 * ## "Hello World!" Example:
 *
 *     // Event Listener Class
 *
 *     namespace Event;
 *
 *     class MyEventListener extends EventListener {
 *         public function implementedEvents() {
 *               return [
 *                   'Alter.Hello' => 'alterWorld',
 *                   'Hello' => 'world',
 *               ];
 *         }
 *
 *         public function alterWorld(Event $event, &$byReference) {
 *             // Remember the "&" for referencing
 *             $byReference .= ' World!';
 *         }
 *
 *          public function world(Event $event, $byValue) {
 *             return $byValue . ' world!';
 *         }
 *     }
 *
 * ***
 *
 *     // Wherever you are able to use event() & alter()
 *
 *     $hello = 'Hello';
 *     $this->alter('Hello', $hello);
 *     echo $hello; // out: "Hello World!"
 *     echo $this->trigger('Hello', $hello); // out: "Hello World! world!"
 *     echo $this->trigger('Hello', 'hellooo'); // out: "hellooo world!"
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
     * @param string $eventName The name of the event, if null returns the entire
     *  list of event that were fired
     * @param bool $sort If first argument is null set this to true to sort the list.
     *  Defaults to true
     * @return int|array
     */
    public static function triggered($eventName = null, $sort = true)
    {
        if (!$eventName) {
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
     *     HookManager::trigger(['GetTime', new ContextObject()], ['arg0' => 'val0', ...]);
     *
     * If no context is given an instance of "Hook" class will be used by default.
     *
     * @param array $eventName The event name to trigger
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
     * Similar to "trigger()" but aimed to alter the given arguments.
     *
     * You can provide **up to 15 arguments**, which are automatically
     * passed to you event listener method by reference. For example:
     *
     *     $arg_0 = 'data 0';
     *     $arg_1 = 'data 1';
     *     ...
     *     $arg_14 = 'data 14';
     *     $this->alter('MyHook', $arg_0, $arg_1, ..., $arg_14);
     *
     * Note that passing arguments as values will produce `Fatal Error`:
     *
     *     $this->alter('MyHook', 'data 0', 'data 1', ..., 'data 14');
     *     // Fatal Error
     *
     * Event names are prefixed with the `Alter.` word. For instance, in your
     * `Event Listener` class you must do as below:
     *
     *     // note the `Alter.` prefix
     *     public function implementedEvents() {
     *         return ['Alter.MyHook' => 'alterHandler'];
     *     }
     *
     *     // now you are able to get arguments by reference
     *     public function alterHandler(Event $event, &$arg_0, &$arg_1, ..., &$arg_14) {
     *         // stuff here
     *     }
     *
     * You can provide a context to use by passing an array as first arguments where
     * the first element is the event name and the second one is the context:
     *
     *     HookManager::alter(['AlterTime', new ContextObject()], $arg0, $arg1, ...);
     *
     * If no context is given an instance of "Hook" class will be used by default.
     *
     * @param string $eventName Name of the "alter event" to trigger.
     *  e.g. `FormHelper.input` will trigger `Alter.FormHelper.input` event
     * @param mixed &$p0 Optional argument by reference
     * @param mixed &$p1 Optional argument by reference
     * @param mixed &$p2 Optional argument by reference
     * @param mixed &$p3 Optional argument by reference
     * @param mixed &$p4 Optional argument by reference
     * @param mixed &$p5 Optional argument by reference
     * @param mixed &$p6 Optional argument by reference
     * @param mixed &$p7 Optional argument by reference
     * @param mixed &$p8 Optional argument by reference
     * @param mixed &$p9 Optional argument by reference
     * @param mixed &$p10 Optional argument by reference
     * @param mixed &$p11 Optional argument by reference
     * @param mixed &$p12 Optional argument by reference
     * @param mixed &$p13 Optional argument by reference
     * @param mixed &$p14 Optional argument by reference
     * @return \Cake\Event\Event
     */
    public static function alter($eventName, &$p0 = null, &$p1 = null, &$p2 = null, &$p3 = null, &$p4 = null, &$p5 = null, &$p6 = null, &$p7 = null, &$p8 = null, &$p9 = null, &$p10 = null, &$p11 = null, &$p12 = null, &$p13 = null, &$p14 = null)
    {
        if (is_array($eventName)) {
            list($eventName, $context) = $eventName;
        } else {
            $context = new HookManager();
        }

        $eventName = "Alter.{$eventName}";
        static::_log($eventName);
        $event = new Event($eventName, $context);
        $listeners = EventManager::instance()->listeners($eventName);

        foreach ($listeners as $listener) {
            if ($event->isStopped()) {
                break;
            }

            $result = $listener['callable']($event, $p0, $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10, $p11, $p12, $p13, $p14);

            if ($result === false) {
                $event->stopPropagation();
            }

            if ($result !== null) {
                $event->result = $result;
            }
        }

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
