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
use Cake\Validation\Validator;
use Field\BaseHandler;
use Field\Model\Entity\Field;

/**
 * List Field Handler.
 *
 * Defines list field types, used to create selection lists.
 */
class ListField extends BaseHandler
{

    /**
     * {@inheritDoc}
     */
    public function entityDisplay(Event $event, Field $field, $options = [])
    {
        $View = $event->subject();
        return $View->element('Field.ListField/display', compact('field', 'options'));
    }

    /**
     * {@inheritDoc}
     */
    public function entityEdit(Event $event, Field $field, $options = [])
    {
        $View = $event->subject();
        return $View->element('Field.ListField/edit', compact('field', 'options'));
    }

    /**
     * {@inheritDoc}
     */
    public function entityBeforeSave(Event $event, Field $field, $options)
    {
        $value = $options['_post'];
        if (is_array($value)) {
            $value = implode(' ', array_values($value));
        }
        $field->set('value', $value);
        $field->set('extra', $options['_post']);
    }

    /**
     * {@inheritDoc}
     */
    public function entityValidate(Event $event, Field $field, Validator $validator)
    {
        if ($field->metadata->required) {
            $validator
                ->requirePresence($field->name)
                ->notEmpty($field->name, __d('field', 'Field required.'));
        } else {
            $validator->allowEmpty($field->name);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function instanceInfo(Event $event)
    {
        return [
            'type' => 'text',
            'name' => __d('field', 'List'),
            'description' => __d('field', 'Defines list field types, used to create selection lists.'),
            'hidden' => false,
            'maxInstances' => 0,
            'searchable' => true,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function instanceSettingsForm(Event $event, $instance, $options = [])
    {
        $View = $event->subject();
        return $View->element('Field.ListField/settings_form', compact('instance', 'options'));
    }

    /**
     * {@inheritDoc}
     */
    public function instanceViewModeForm(Event $event, $instance, $options = [])
    {
        $View = $event->subject();
        return $View->element('Field.ListField/view_mode_form', compact('instance', 'options'));
    }

    /**
     * {@inheritDoc}
     */
    public function instanceViewModeDefaults(Event $event, $instance, $options = [])
    {
        switch ($options['viewMode']) {
            default:
                return [
                    'label_visibility' => 'above',
                    'shortcodes' => true,
                    'hidden' => false,
                    'formatter' => 'default',
                ];
        }
    }
}
