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
 * Provides hook() method.
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
 * Your `Hook Listener` class must extends `\Cake\Event\EventListener` and provide the `implementedEvents()` method.
 * This method must return an associative array with all hook names that the class will handle.
 * For example: `User.beforeLogin` will respond to:
 *
 *     $this->hook('User.beforeLogin', ...);
 *
 * When invoking a Hook Handler, arguments are always treated by reference, this allows you to create
 * `alter hooks` which may alter the provided arguments. Anyway, `hook()` method always returns
 * the Event object used when invoking the Hook Handler.
 *
 * ***
 *
 * **Note:** Remember that `call-time pass-by-reference` is [no longer available in PHP](php.net/manual/en/language.references.pass.php),
 * so the end you're the responsible of getting arguments by reference at method definition.
 *
 *     public function myHookHandler(Event $event, $arg_1, $arg_2);
 *     public function myAlterHookHandler(Event $event, &$arg_1, &$arg_2);
 *
 * ***
 *
 * When defining new Hooks, is always a good practice to prefix (or suffix) your Hook names
 * with the `alter` word indicating if they are intended to alter the given arguments or not.
 * For example, `User.alterName`, `User.alterAge`:
 *
 *     public function userAlterName(Event $event, &$name) {
 *         $name = 'New Name';
 *     }
 *
 *     public function userAlterAge(Event $event, &$age) {
 *         $age = 18;
 *     }
 *
 * ***
 *
 * ## "Hello World!" Example:
 *
 *     // Hook Listener Class
 *     namespace Hook;
 *
 *     class HookHandler extends EventListener {
 *         public function implementedEvents() {
 *		       return ['alterThis' => 'alterThisHandlerMethod'];
 *         }
 *
 *         public function alterThisHandlerMethod(Event $event, &$alterThis) {
 *             $alterThis .= ' World!';
 *         }
 *     }
 *
 * ***
 *
 *     // Wherever you are able to use hook()
 *     $alterThis = 'Hello';
 *     $event = $this->hook('alterThis', $alterThis);
 *     echo $alterThis;
 *     // out: "Hello World!"
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
 * Triggers the given hook across the entire system.
 *
 * You can provide up to 15 arguments, which are automatically
 * passed to you hook handler method by reference. For example:
 *
 *     $this->hook('MyHook', arg_0, arg_1, ..., arg_14);
 *
 * Note that passing arguments as values will produce `Fatal Error`:
 *
 *     $this->hook('MyHook', 'data 0', 'data 1', ..., 'data 14');
 *     // Fatal Error
 *
 * In your `Hook Listener` you must implement the Hook name as below:
 *
 *     // implementedEvents() should return:
 *     ['MyHook' => 'handlerMethod']
 *
 *     // You are able to get arguments by reference
 *     public function handlerMethod(Event $event, &$arg_0, &$arg_1, ..., &$arg_14) {
 *         // stuff here
 *     }
 *
 * @param string $hookName The name of the hook to launch. e.g.: `FormHelper.input`
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
	public function hook($hookName, &$p0 = null, &$p1 = null, &$p2 = null, &$p3 = null, &$p4 = null, &$p5 = null, &$p6 = null, &$p7 = null, &$p8 = null, &$p9 = null, &$p10 = null, &$p11 = null, &$p12 = null, &$p13 = null, &$p14 = null) {
		$result = $this->event($hookName, $this, $p0, $p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8, $p9, $p10, $p11, $p12, $p13, $p14);
		return $result;
	}

/**
 * Generic event dispatcher.
 *
 * Similar to hook(), but allows to specify a subject for \Cake\Event\Event.
 *
 * You can provide up to 15 arguments, which are automatically
 * passed to you hook handler method by reference.
 *
 * @param string $eventName Name of the hook to be triggered under the given $space
 * @param object $subject Context for Cake\Event\Event constructor
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
 * @return \Cake\Event\Event The event object used
 */
	public function event($eventName, $subject, &$p0 = null, &$p1 = null, &$p2 = null, &$p3 = null, &$p4 = null, &$p5 = null, &$p6 = null, &$p7 = null, &$p8 = null, &$p9 = null, &$p10 = null, &$p11 = null, &$p12 = null, &$p13 = null, &$p14 = null) {
		$event = new Event($eventName, $subject);
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

}
