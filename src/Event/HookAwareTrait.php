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
use QuickApps\Event\HookManager;

/**
 * Provides trigger() & alter() methods.
 *
 * @see QuickApps\Event\HookManager
 */
trait HookAwareTrait
{

    /**
     * Retrieves the number of times an event was triggered, or the complete list
     * of events that were triggered.
     *
     * @param string $eventName The name of the event, if null returns the entire
     *  list of event that were fired
     * @return int|array
     * @see QuickApps\Event\HookManager::triggered()
     */
    public function triggered($eventName = null)
    {
        return HookManager::triggered($eventName);
    }

    /**
     * Triggers the given event name.
     *
     * You can pass an unlimited number of arguments to your event handler method.
     *
     * ### Usage:
     *
     *     $this->trigger('GetTime', $arg_0, $arg_1, ..., $arg_n);
     *
     * Your `Event Listener` must implement:
     *
     *     public function implementedEvents() {
     *         return ['GetTime' => 'handlerForGetTime'];
     *     }
     *
     * You can provide a context to use by passing an array as first arguments where
     * the first element is the event name and the second one is the context:
     *
     *     $this->trigger(['GetTime', new ContextObject()], $arg_0, $arg_1, ..., $arg_n);
     *
     * If no context is given `$this` will be used by default.
     *
     * @param string|array $eventName The event name to trigger
     * @return \Cake\Event\Event The event object that was fired
     * @see QuickApps\Event\HookManager::trigger()
     */
    public function trigger($eventName)
    {
        if (is_string($eventName)) {
            $eventName = [$eventName, $this];
        }
        $args = func_get_args();
        array_shift($args);
        return HookManager::trigger($eventName, $args);
    }

    /**
     * Similar to "trigger()" but aimed to alter the given arguments.
     *
     *  * You can pass up to 15 arguments by reference.
     *
     * ### Usage:
     *
     *     $this->alter('Time', $arg_0, $arg_0, ..., $arg_1);
     *
     * Your `Event Listener` must implement:
     *
     *     public function implementedEvents() {
     *         return ['Alter.Time' => 'handlerForAlterTime'];
     *     }
     *
     * You can provide a context to use by passing an array as first arguments where
     * the first element is the event name and the second one is the context:
     *
     *     $this->alter(['Time', new ContextObject()], $arg0, $arg1, ...);
     *
     * If no context is given "$this" will be used by default.
     *
     * @param string $eventName The name of the "alter hook" to trigger. e.g. `FormHelper.input`
     * @param mixed &$p0 Optional Argument by reference
     * @param mixed &$p1 Optional Argument by reference
     * @param mixed &$p2 Optional Argument by reference
     * @param mixed &$p3 Optional Argument by reference
     * @param mixed &$p4 Optional Argument by reference
     * @param mixed &$p5 Optional Argument by reference
     * @param mixed &$p6 Optional Argument by reference
     * @param mixed &$p7 Optional Argument by reference
     * @param mixed &$p8 Optional Argument by reference
     * @param mixed &$p9 Optional Argument by reference
     * @param mixed &$p10 Optional Argument by reference
     * @param mixed &$p11 Optional Argument by reference
     * @param mixed &$p12 Optional Argument by reference
     * @param mixed &$p13 Optional Argument by reference
     * @param mixed &$p14 Optional Argument by reference
     * @return \Cake\Event\Event The event object that was fired
     * @see QuickApps\Event\HookManager::alter()
     */
    public function alter($eventName, &$p0 = null, &$p1 = null, &$p2 = null, &$p3 = null, &$p4 = null, &$p5 = null, &$p6 = null, &$p7 = null, &$p8 = null, &$p9 = null, &$p10 = null, &$p11 = null, &$p12 = null, &$p13 = null, &$p14 = null)
    {
        if (is_string($eventName)) {
            $eventName = [$eventName, $this];
        }
        return HookManager::alter($eventName, $p0, $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10, $p11, $p12, $p13, $p14);
    }
}
