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

use Cake\Routing\Router;

/**
 * Adds methods for handling alert messages.
 *
 * Similar to Cake's `flashMessage` mechanism. But specifically
 * designed to work with QuickAppsCMS.
 *
 * QuickAppsCMS's alert messages system is designed to work in combination with
 * `Twitter Bootstrap`, so alerts messages classes are limited to:
 *
 * - success
 * - info
 * - warning
 * - danger
 *
 * Alerts are organized in a `group, class` basis. Default group is `flash`.
 * Each groups has one `subgroup` per class. Each subgroups may contain an unlimited
 * number of messages. For example, in certain moment you might have:
 *
 * - flash.success
 *   - message 1
 *   - message 2
 * - flash.info
 *   - message 3
 * - flash.warning
 *   - more messages
 * - flash.danger
 *   - even more
 *   - and more messages
 * - ...
 * - `<group>`.`<class>`
 *   - message 1
 *   - ...
 *   - message n
 *
 * As we mention, `flash` is the default group, but you are able to define your own.
 * For example, in your controller:
 *
 *     $this->alert('My success alert for group "dummy"', 'success', 'dummy');
 *     $this->alert('My danger alert for group "dummy"', 'danger', 'dummy');
 *
 * Added to previous alerts will result on:
 *
 * - flash.success
 *   - message 1
 *   - message 2
 * - flash.info
 *   - message 3
 * - flash.warning
 *   - more messages
 * - flash.danger
 *   - even more
 *   - and more
 * - dummy.success
 *   - My success alert for group "dummy"
 * - dummy.danger
 *   - My danger alert for group "dummy"
 *
 * This is extremely useful when having to render multiple messages in different parts
 * of your page (simultaneously or not).
 */
trait AlertTrait {

/**
 * Sets a notification message.
 *
 * @param string $message Your notification message
 * @param string $class Type of message (success, info, warning or danger), `success` by default
 * @param string $group Message group, default is 'flash'
 * @return void
 */
	public function alert($message, $class = 'success', $group = 'flash') {
		if (empty($group) || empty($class)) {
			return;
		}

		$class = !in_array($class, ['success', 'info', 'warning', 'danger']) ? 'success' : $class;
		$key = "Alert.{$group}.{$class}";
		$messages = (array)Router::getRequest()->session()->read($key);
		$messages[] = $message;

		Router::getRequest()->session()->write($key, $messages);
	}

/**
 * Removes all defined alerts messages matching the given criteria.
 *
 * You can remove all messages that belongs to a specific group,
 * to a specific class or a combination of both.
 *
 * Example:
 *
 *     // clears all success alerts from `flash` group (default)
 *     $this->clearAlerts('success');
 *
 *     // clears all alerts of any class from `flash` group
 *     $this->clearAlerts();
 *
 *     // clears all alerts from `dummy` group, whatever the class they are
 *     $this->clearAlerts(, 'dummy');
 *
 *     // clears absolutely all messages, any class and any group
 *     $this->clearAlerts(null, null);
 *
 * @param string|null $class Which class of messages to remove ('success', 'info', 'warning', 'danger'). Null
 * by default (clear all classes)
 * @param string|null $group Which group to clear. `flash` by default. Set to null
 * to remove all the messages whatever the group they belongs to
 * @return void
 */
	public function clearAlerts($class = null, $group = 'flash') {
		if ($class === null && $group === null) {
			Router::getRequest()->session()->delete('Alert');
		} elseif ($class === null && $group !== null) {
			Router::getRequest()->session()->delete("Alert.{$group}");
		} elseif ($class !== null && $group === null) {
			$groups = (array)Router::getRequest()->session()->read('Alert');

			foreach ($groups as $groupName => $span) {
				$messages = (array)Router::getRequest()->session()->read("Alert.{$groupName}");

				foreach ((array)$span as $c => $m) {
					if ($class == $c) {
						Router::getRequest()->session()->read("Alert.{$groupName}.{$c}");
					}
				}
			}
		} elseif ($class !== null && $group !== null) {
			Router::getRequest()->session()->delete("Alert.{$group}.{$class}");
		}
	}

/**
 * Renders/Gets alert messages.
 *
 * When using this method in View context (e.g. Helper classes) alert messages are automatically rendered as HTML.
 * But when using this method in a non-View context (e.g. Controllers, Tables, etc), an array list with all requested messages
 * will be returned.
 *
 * ## Using this trait in View context
 *
 * When using this trait over classes having a `_View` property (such as Helpers) or classes extending `\Cake\View\View`,
 * each messages will be rendered using the `render_alert.ctp` view element.
 *
 * **Example:**
 *
 * Trait attached to `MyHelper` class:
 * 
 *     // this will render all defined alerts (danger, success, etc), for group 'flash' *
 *     echo $this->MyHelper->alerts();
 *
 *     // this will render success alerts only, for group 'flash'
 *     echo $this->MyHelper->alerts('success');
 *     // or:
 *     echo $this->MyHelper->alerts(['success']);
 *
 *     // this will render success and info alerts (in order), for group 'flash'
 *     echo $this->MyHelper->alerts(['success', 'info']);
 *
 *     // this will render success and info alerts (in order), for group 'my-group'
 *     echo $this->MyHelper->alerts(['success', 'info'], 'my-group'); 
 *
 *     // this will render all defined alerts (danger, success, etc), for group 'my-group'
 *     echo $this->MyHelper->alerts(null, 'my-group');
 *
 * By default this trait is attached to the View class used by Controllers (QuickApps\View\View), that means you can do as follow:
 *
 *     // my_view.ctp
 *     $this->alerts('success');
 *
 * ## Using this method in non-View context
 *
 * Similar to the View context usage described above but for classes that have no relation with View class such as Controllers,
 * Components, Models, etc. In these cases, instead of returning a HTML for each rendered message, you will get an array list of
 * messages. Note that **after this array list is returned messages are automatically destroyed**.
 *
 * **Example:**
 *
 * Trait attached to Controller class:
 *
 *     $this->alerts(['success', 'info']);
 *     // returns:
 *     [
 *         [success] => [
 *             'Success message 1',
 *             'Success message 2',
 *             'Success message 3',
 *         ],
 *         [info] => [
 *             'Info message 1',
 *             'Info message 2',
 *         ]
 *     ]
 *
 * Following this example. Using this method right after the first call,
 * will return an empty array as messages are destroyed at the first call:
 *
 *     $this->alerts(['success', 'info']);
 *     // returns: []
 *
 * @param string|array|null $class Type of messages to render. Or an array of classes to render.
 * If not given (null) all the alerts will be rendered.
 * @param string $group Which group to render, default is `flash`.
 * @return string|array HTML of rendered message elements. Or an array list of messages.
 */
	public function alerts($class = null, $group = 'flash') {
		$viewInstance = false;

		if (
			isset($this->_View) &&
			($this->_View instanceof \Cake\View\View)
		) {
			$viewInstance = $this->_View;
		} elseif ($this instanceof \Cake\View\View) {
			$viewInstance = $this;
		}

		$out = $viewInstance ? '' : [];

		if (empty($group)) {
			return $out;
		}

		if (is_array($class)) {
			foreach ($class as $c) {
				if ($viewInstance) {
					$out .= $this->alerts($c, $group);
				} else {
					$out = array_merge($out, $this->alerts($c, $group));
				}
			}
		} elseif (is_null($class)) {
			$_messages = Router::getRequest()->session()->read("Alert.{$group}");

			if (!empty($_messages)) {
				foreach ($_messages as $_class => $messages) {
					if (!empty($messages)) {
						if ($viewInstance) {
							$out .= $this->alerts($_class, $group);
						} else {
							$out = array_merge($out, $this->alerts($_class, $group));
						}
					}
				}
			}
		} elseif (is_string($class) && !empty($class)) {
			$messages = (array)Router::getRequest()->session()->read("Alert.{$group}.{$class}");

			foreach ($messages as $k => $message) {
				if (!empty($message) && in_array($class, ['success', 'info', 'warning', 'danger'])) {
					if ($viewInstance) {
						$alert = $viewInstance->element('render_alert', compact('class', 'message'));
						$out .= "{$alert}\n";
					} else {
						$out[$class][] = $message;
					}
				}
			}

			Router::getRequest()->session()->delete("Alert.{$group}.{$class}");
		}

		return $out;
	}

}
