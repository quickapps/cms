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

use Cake\Network\Session;

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
 *    flash.success
 *        - message 1
 *        - message 2
 *    flash.info
 *        - message 3
 *    flash.warning
 *        - more messages
 *    flash.danger
 *        - even more
 *        - and more
 *    ...
 *    <group>.<class>
 *        - message ...
 *
 * As we mention, `flash` is the default group, but you are able to define your own.
 * For example, in your controller:
 *
 *     $this->alert('My success alert for group "dummy"', 'success', 'dummy');
 *     $this->alert('My danger alert for group "dummy"', 'danger', 'dummy');
 *
 * Added to previous alerts will result on:
 *
 *    flash.success
 *        - message 1
 *        - message 2
 *    flash.info
 *        - message 3
 *    flash.warning
 *        - more messages
 *    flash.danger
 *        - even more
 *        - and more
 *    dummy.success
 *        - My success alert for group "dummy"
 *    dummy.danger
 *        - My danger alert for group "dummy"
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
		$messages = (array)Session::read($key);
		$messages[] = $message;

		Session::write($key, $messages);
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
			Session::delete('Alert');
		} elseif ($class === null && $group !== null) {
			Session::delete("Alert.{$group}");
		} elseif ($class !== null && $group === null) {
			$groups = (array)Session::read('Alert');

			foreach ($groups as $groupName => $span) {
				$messages = (array)Session::read("Alert.{$groupName}");

				foreach ((array)$span as $c => $m) {
					if ($class == $c) {
						Session::read("Alert.{$groupName}.{$c}");
					}
				}
			}
		} elseif ($class !== null && $group !== null) {
			Session::delete("Alert.{$group}.{$class}");
		}
	}

/**
 * Renders/Gets alert messages.
 *
 * When using this method in view class, alert messages are automatically rendered as HTML.
 * But when using this method in non-View Classes, a list array with all requested messages
 * will be returned.
 *
 * ## Using this method in View Class
 *
 * When using this method as part of the view-rendering cycle (view, layouts, etc),
 * each messages is rendered using the `render_alert.ctp` view element.
 *
 * Example:
 *
 *     // this will render all defined alerts (danger, success, etc), for group 'flash' *
 *     echo $this->alerts();
 *
 *     // this will render success alerts only, for group 'flash'
 *     echo $this->alerts('success');
 *     // or:
 *     echo $this->alerts(['success']);
 *
 *     // this will render success and info alerts (in order), for group 'flash'
 *     echo $this->alerts(['success', 'info']);
 *
 * * The second argument (group name) value is `flash` by default.
 *
 * ## Using this method in non-View Classes
 *
 * Similar to the "in-view class usage" described above but for class that are not View classes such
 * as Controllers, Components, Models, etc.
 * In this case, instead of returning an HTML of each rendered message, you will get an array list of messages.
 * Note that **after this array list is returned messages are automatically destroyed**.
 *
 * Example:
 *
 *     $this->alerts(['success', 'info']);
 *     // returns:
 *
 *     [success] => [
 *         'Success message 1',
 *         'Success message 2',
 *         'Success message 3',
 *     ],
 *     [info] => [
 *         'Info message 1',
 *         'Info message 2',
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
		$isView = true;

		if (!($this instanceof \QuickApps\View\View)) {
			$isView = false;
		}

		$out = $isView ? '' : [];

		if (empty($group)) {
			return $out;
		}

		if (is_array($class)) {
			foreach ($class as $c) {
				if ($isView) {
					$out .= $this->alerts($c, $group);
				} else {
					$out = array_merge($out, $this->alerts($c, $group));
				}
			}
		} elseif (is_null($class)) {
			$_messages = Session::read("Alert.{$group}");

			if (!empty($_messages)) {
				foreach ($_messages as $_class => $messages) {
					if (!empty($messages)) {
						if ($isView) {
							$out .= $this->alerts($_class, $group);
						} else {
							$out = array_merge($out, $this->alerts($_class, $group));
						}
					}
				}
			}
		} elseif (is_string($class) && !empty($class)) {
			$messages = (array)Session::read("Alert.{$group}.{$class}");

			foreach ($messages as $k => $message) {
				if (!empty($message) && in_array($class, ['success', 'info', 'warning', 'danger'])) {
					if ($isView) {
						$alert = $this->element('render_alert', compact('class', 'message'));
						$out .= "{$alert}\n";
					} else {
						$out[$class][] = $message;
					}
				}
			}

			Session::delete("Alert.{$group}.{$class}");
		}

		return $out;
	}

}
