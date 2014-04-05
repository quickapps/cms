<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace QuickApps\Utility;

use Cake\Event\Event;
use Cake\Event\EventManager;

/**
 * Provides hook(), alter() & invoke() methods.
 *
 * QuickAppsCMS's hook system is built over [cake's event system](http://book.cakephp.org/3.0/en/core-libraries/events.html).
 * And allows plugins to communicate with the entire system or other plugins.
 *
 * QuickAppsCMS's Hook system is composed of three primary elements:
 *
 * - `Hook Listener`: An event listeners class, which belongs to the `Hook` name-space.
 * - `Hook Handler`: A method in your your listener class which take care of a single hook.
 * - `Hook`: Name of the hook event. e.g.: `FormHelper.input`.
 *
 * A Hook Listener, may listen to many Hook events. But a Hook Handler can only responds
 * to a single Hook event.
 *
 * Your `Hook Listener` class must implement `\Cake\Event\EventListener` interface and provide the `implementedEvents()` method.
 * This method must return an associative array with all hook names that the class will handle.
 * For example: `User.beforeLogin` will respond to:
 *
 *     $this->hook('User.beforeLogin', ...);
 *
 * QuickAppsCMS has divided hook into two groups or "event spaces":
 *
 * - `Alter`: Hooks aimed to alter the given arguments. Triggered trough `alter()` method.
 * - `Hook`: Just a normal hook event which may may return some values. Triggered trough `hook()` method.
 *
 * Alter hooks must prefix their names with the `Alter.` word. For example, `Alter.FormHelper.textarea`
 * will respond to:
 *
 *     $this->alter('FormHelper.textarea', $arg_0, $arg_1, ..., $arg_14);
 *
 * When using alter hook you can provide **up to 15 arguments by reference**
 *
 * ---
 *
 * In the other hand, hooks which belongs to the `Hook` event space must prefix their names with the
 * `Hook.` word, so for example, `Hook.HelloWorld` will respond to:
 *
 *     $this->hook('HelloWorld', $arg_0, $arg_1, ..., $arg_n);
 *
 * You can provide an unlimited number of arguments which are treated by value, and NOT by reference as `alter()` does.
 *
 * ***
 *
 * ## "Hello World!" Example:
 *
 *     // Hook Listener Class
 *
 *     namespace Hook;
 *
 *     class HookHandler extends EventListener {
 *         public function implementedEvents() {
 *		       return [
 *		           'Alter.Hello' => 'alterWorld',
 *		           'Hook.Hello' => 'world',
 *		       ];
 *         }
 *
 *         public function alterWorld(Event $event, &$byReference) {
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
 *     // Wherever you are able to use hook() & alter()
 *
 *     $hello = 'Hello';
 *     $this->alter('Hello', $hello);
 *     echo $hello; // out: "Hello World!"
 *     echo $this->hook('Hello', $hello); // out: "Hello World! world!"
 *     echo $this->hook('Hello', 'hello'); // out: "hello world!"
 *
 * ## Recommended Reading
 *
 * As QuickAppsCMS's hook system is built on top of CakePHP's events system we highly recommend you
 * to take a look at this part of CakePHP's book:
 *
 * [CakePHP's Events System](http://book.cakephp.org/3.0/en/core-libraries/events.html)
 */
trait HookTrait {

/**
 * Trigger the given hook name under the "Hook" event space.
 *
 * You can pass an unlimited number of arguments to your hook handler method.
 *
 * Usage:
 *
 *     $this->hook('GetTime', $arg_0, $arg_0, ..., $arg_1);
 *
 * Your `Hook Listener` must implement:
 *
 *     // note the `Hook.` prefix
 *     ['Hook.GetTime' => 'handlerForGetTime']
 *
 * @param string $hookName The hook name to trigger
 * @return mixed Whatever the hook handler returns
 */
	public function hook($hookName) {
		$args = func_get_args();
		array_shift($args);
		$event = new Event($hookName, $this, $args);
		EventManager::instance()->dispatch($event);

		return $event->result;
	}

/**
 * Similar to "hook()" but aimed to alter the given arguments.
 *
 * You can provide **up to 15 arguments**, which are automatically
 * passed to you hook handler method by reference. For example:
 *
 *     $this->alter('MyHook', $arg_0, $arg_1, ..., $arg_14);
 *
 * Note that passing arguments as values will produce `Fatal Error`:
 *
 *     $this->alter('MyHook', 'data 0', 'data 1', ..., 'data 14');
 *     // Fatal Error
 *
 * In your `Hook Listener` you must implement the Hook name as below:
 *
 *     // note the `Alter.` prefix
 *     ['Alter.MyHook' => 'alterHandler']
 *
 *     // now you are able to get arguments by reference
 *     public function alterHandler(Event $event, &$arg_0, &$arg_1, ..., &$arg_14) {
 *         // stuff here
 *     }
 *
 * @param string $hookName The name of the "alter hook" to trigger. e.g.: `FormHelper.input`
 * @param mixed $p0 Optional argument by reference
 * @param mixed $p1 Optional argument by reference
 * @param mixed $p2 Optional argument by reference
 * @param mixed $p3 Optional argument by reference
 * @param mixed $p4 Optional argument by reference
 * @param mixed $p5 Optional argument by reference
 * @param mixed $p6 Optional argument by reference
 * @param mixed $p7 Optional argument by reference
 * @param mixed $p8 Optional argument by reference
 * @param mixed $p9 Optional argument by reference
 * @param mixed $p10 Optional argument by reference
 * @param mixed $p11 Optional argument by reference
 * @param mixed $p12 Optional argument by reference
 * @param mixed $p13 Optional argument by reference
 * @param mixed $p14 Optional argument by reference
 * @return \Cake\Event\Event
 */
	public function alter($hookName, &$p0 = null, &$p1 = null, &$p2 = null, &$p3 = null, &$p4 = null, &$p5 = null, &$p6 = null, &$p7 = null, &$p8 = null, &$p9 = null, &$p10 = null, &$p11 = null, &$p12 = null, &$p13 = null, &$p14 = null) {
		$eventName = "Alter.{$hookName}";
		$event = new Event($eventName, $this);
		$listeners = EventManager::instance()->listeners($event->name());

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
 * Triggers the given hook regardless of the "event space".
 *
 * Similar to `hook()` but no prefix will be added to the hook name.
 * Also, optionally you can provide a custom `context` for \Cake\Event\Event::$subject.
 *
 * ---
 *
 * **Comparison, `hook()` vs `invoke()`:**
 *
 *     // triggers "Hook.Hello", with "$this" as context
 *     $this->hook('Hello');
 *
 *     // triggers "Hello", with an instance of "SomeClass" as context
 *     $this->invoke('Hello', new SomeClass());
 *
 * @param string $hookName The name of the hook to trigger
 * @param null|object $context Optional context for \Cake\Event\Event::$subject
 * @return \Cake\Event\Event The event used to trigger the hook
 */
	public function invoke($hookName, $context = null) {
		$context = $context === null ? $this : $context;
		$args = func_get_args();
		array_shift($args);
		array_shift($args);
		$event = new Event($hookName, $context, $args);
		EventManager::instance()->dispatch($event);

		return $event;
	}

}
