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

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Utility\Hash;
use QuickApps\Event\HookAwareTrait;
use QuickApps\View\ViewModeAwareTrait;

/**
 * Field rendering dispatcher.
 *
 * Dispatches `Render.Field\Model\Entity\Field` rendering-request from View to
 * their corresponding FieldHandlers.
 *
 * Field Handlers should implement the `<FieldHandler>.Entity.display` hook. e.g.:
 *
 *     Field.TextField.Entity.display
 *
 * Its callable method should expect two parameters, `$field` and `$options`, and it
 * should return a HTML string representation of your field. i.e.:
 *
 *     public function display(Event $event, $field, $options) {
 *         return
 *             "<h2>{$field->label}</h2>" .
 *             "<p>{$field->value}</p>";
 *     }
 *
 * Usually you will rely on view elements for HTML rendering, to invoke
 * View::element(...), you should use event's subject which is the view instance
 * in use:
 *
 *     public function display(Event $event, $field, $options) {
 *         return $event
 *             ->subject
 *             ->element('MyPlugin.text_field_display', compact('field', 'options'));
 *     }
 *
 * Remember that view elements can alway be overwritten by themes. So it's a good
 * practice always use view elements as rendering method instead returning
 * hard-coded HTML code in your methods as in the first example above.
 */
class FieldHook implements EventListenerInterface
{

    use HookAwareTrait;
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
            'Field.info' => 'listFields',
        ];
    }

    /**
     * We catch all field rendering request (from QuickApps\View\View) here, then we
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
        $viewMode = $this->inUseViewMode();

        if (
            isset($field->metadata->view_modes[$viewMode]) &&
            !$field->metadata->view_modes[$viewMode]['hidden']
        ) {
            $options = array_merge(['edit' => false], $options);
            $renderFieldHook = $this->trigger(["Field.{$field->metadata['handler']}.Entity.display", $event->subject], $field, $options);
            $event->stopPropagation(); // We don't want other plugins to catch this
            return (string)$renderFieldHook->result;
        }

        return '';
    }

    /**
     * Gets a collection of information of every registered field in the system.
     *
     * ### Example:
     *
     * Using `$this->trigger('Field.info', true)` may produce:
     *
     *     array(
     *         [0] => array(
     *             'name' => 'Textarea',
     *             'description' => 'Allows to store long texts',
     *             'hidden' => false
     *         ),
     *         [1] => array(
     *             'name' => 'Secret Field',
     *             'description' => 'This field should only be used internally by plugins',
     *             'hidden' => true
     *         )
     *     )
     *
     * Some fields may register themselves as hidden when they are intended to be used
     * exclusively by plugins. So users can not `attach` them to entities using Field UI.
     *
     * @param \Cake\Event\Event $event The hook event
     * @param bool $includeHidden Set to true to include fields marked as hidden
     * @return \Cake\Collection\Collection A collection of fields information
     */
    public function listFields(Event $event, $includeHidden = false)
    {
        $fields = [];

        foreach (listeners() as $listener) {
            if (str_starts_with($listener, 'Field.') && str_ends_with($listener, '.Instance.info')) {
                $fieldHandler = explode('.', $listener)[1];
                $response = array_merge(
                    [
                        'name' => null,
                        'description' => null,
                        'hidden' => false,
                        'handler' => $fieldHandler,
                    ],
                    (array)$this->trigger($listener)->result
                );
                if (!$response['hidden'] || $includeHidden) {
                    $fields[] = $response;
                }
            }
        }

        return collection($fields);
    }
}
