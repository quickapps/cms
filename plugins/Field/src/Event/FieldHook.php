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
namespace Field\Event;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use CMS\Event\EventDispatcherTrait;
use CMS\View\ViewModeAwareTrait;

/**
 * Field rendering dispatcher.
 *
 * Dispatches `Render.Field\Model\Entity\Field` rendering-request from View to
 * their corresponding FieldHandlers.
 *
 * Field Handlers should implement the `<FieldHandler>.Entity.display` hook. e.g.:
 *
 * ```
 * Field.TextField.Entity.display
 * ```
 *
 * Its callable method should expect two parameters, `$field` and `$options`, and it
 * should return a HTML string representation of $field. i.e.:
 *
 * ```php
 * public function display(Event $event, $field, $options) {
 *     return
 *         "<h2>{$field->label}</h2>" .
 *         "<p>{$field->value}</p>";
 * }
 * ```
 *
 * Usually you will rely on view elements for HTML rendering, to invoke
 * View::element(...), you should use event's subject which is the view instance
 * being used:
 *
 * ```php
 * public function display(Event $event, $field, $options) {
 *     return $event
 *         ->subject()
 *         ->element('MyPlugin.text_field_display', compact('field', 'options'));
 * }
 * ```
 *
 * Remember that view elements can alway be overwritten by themes. So it's a good
 * practice always use view elements as rendering method instead returning
 * hard-coded HTML code.
 */
class FieldHook implements EventListenerInterface
{

    use EventDispatcherTrait;
    use ViewModeAwareTrait;

    /**
     * Returns a list of hooks this Hook Listener is implementing. When the class is
     * registered in an event manager, each individual method will be associated with
     * the respective event.
     *
     * @return void
     */
    public function implementedEvents()
    {
        return [
            'Render.Field\Model\Entity\Field' => [
                'callable' => 'renderField',
                'priority' => -1
            ],
        ];
    }

    /**
     * We catch all field rendering request (from CMS\View\View) here, then we
     * dispatch to their corresponding FieldHandler.
     *
     * If the field object being rendered has been set to "hidden" for the current
     * view mode it will not be rendered.
     *
     * @param \Cake\Event\Event $event The event that was triggered
     * @param \Field\Model\Entity\Field $field Mock entity
     * @param array $options Additional array of options
     * @return string The rendered field
     */
    public function renderField(Event $event, $field, $options = [])
    {
        $viewMode = $this->viewMode();
        if (isset($field->metadata->view_modes[$viewMode]) &&
            !$field->metadata->view_modes[$viewMode]['hidden']
        ) {
            $event->stopPropagation(); // We don't want other plugins to catch this
            $result = (string)$field->render($event->subject());

            if (!$field->metadata->view_modes[$viewMode]['shortcodes']) {
                $result = $event->subject()->stripShortcodes($result);
            }

            return $result;
        }

        return '';
    }
}
