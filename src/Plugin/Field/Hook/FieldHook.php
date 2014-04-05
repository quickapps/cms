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
namespace Hook;

use Cake\Event\Event;
use Cake\Event\EventListener;
use Cake\Event\EventManager;
use QuickApps\Utility\HookTrait;

/**
 * Field rendering dispatcher.
 *
 * Dispatches `ObjectRender.Field\Model\Entity\Field`
 * rendering-request from View to their corresponding
 * FieldHandlers.
 *
 * Field Handlers should implement the `<FieldHandler>.Entity.display` hook. i.e.:
 *
 *     Field\TextField.Entity.display
 *
 * Its callable method should expect two parameters, `$field` and `$options`,
 * and it should return a HTML string representation of your field. i.e.:
 *
 *     public function display(Event $event, $field, $options) {
 *         return
 *             "<h2>{$field->label}</h2>" .
 *             "<p>{$field->value}</p>";
 *     }
 *
 * Usually you will rely on view elements for HTML rendering,
 * to invoke View::element(...), you should use event's subject
 * which is the view instance in use:
 *
 *     public function display(Event $event, $field, $options) {
 *         return $event->subject->element('MyPlugin.text_field_display', compact('field', 'options'));
 *     }
 *
 * Remember that view elements can alway be overwritten by themes.
 * So it's a good practice always use view elements as rendering method instead
 * returning hard-coded HTML code in your methods as in the first example above.
 */
class FieldHook implements EventListener {

	use HookTrait;

/**
 * Returns a list of hooks this Hook Listener is implementing. When the class is registered
 * in an event manager, each individual method will be associated with the respective event.
 *
 * @return void
 */
	public function implementedEvents() {
		return [
			'ObjectRender.Field\Model\Entity\Field' => [
				'callable' => 'renderField',
				'priority' => -1
			]
		];
	}

/**
 * We catch all field rendering request (from QuickApps\View\View) here,
 * then we dispatch to their corresponding FieldHandler.
 *
 * @param Cake\Event\Event $event
 * @param Field\Model\Entity\Field $field Mock entity
 * @param array $options Additional array of options
 * @return string The rendered field
 */
	public function renderField(Event $event, &$field, &$options = []) {
		$options = array_merge(['edit' => false], $options);
		$renderFieldHook = $this->event("Field.{$field->metadata['handler']}.Entity.display", $event->subject, $field, $options);
		$event->stopPropagation(); // We don't want other plugins to catch this

		return (string)$renderFieldHook->result;
	}

}
