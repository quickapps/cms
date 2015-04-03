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
use Field\BaseHandler;
use Field\Model\Entity\Field;
use Field\Utility\DateToolbox;

/**
 * Date Field Handler.
 *
 * This field allows attach date pickers to entities.
 */
class DateField extends BaseHandler
{

    /**
     * {@inheritDoc}
     */
    public function entityDisplay(Event $event, Field $field, $options = [])
    {
        $View = $event->subject();
        return $View->element('Field.DateField/display', compact('field', 'options'));
    }

    /**
     * {@inheritDoc}
     */
    public function entityEdit(Event $event, Field $field, $options = [])
    {
        $View = $event->subject();
        return $View->element('Field.DateField/edit', compact('field', 'options'));
    }

    /**
     * {@inheritDoc}
     */
    public function entityFieldAttached(Event $event, Field $field)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function entityBeforeFind(Event $event, Field $field, $options, $primary)
    {
    }

    /**
     * {@inheritDoc}
     *
     * - extra: Holds string date incoming from POST
     * - value: Holds datetime information
     */
    public function entityBeforeSave(Event $event, Field $field, $options)
    {
        if (!empty($options['_post']['date']) && !empty($options['_post']['format'])) {
            $date = $options['_post']['date'];
            $format = $options['_post']['format'];
            if ($date = DateToolbox::createFromFormat($format, $date)) {
                $field->set('extra', $options['_post']['date']);
            } else {
                $field->metadata->entity->errors(":{$field->name}", __d('field', 'Invalid date/time, it must match the the pattern: {0}', $format));
                return false;
            }
        }

        $field->set('value', date_timestamp_get($date));
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function entityAfterSave(Event $event, Field $field, $options)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function entityBeforeValidate(Event $event, Field $field, $options, $validator)
    {
        if (!$field->metadata->required) {
            return true;
        }

        $validator
            ->notEmpty(":{$field->name}", __d('field', 'You must select a date/time.'))
            ->add(":{$field->name}", 'validDate', [
                'rule' => function ($value, $context) {
                    return DateToolbox::createFromFormat($value['format'], $value['date']) !== false;
                },
                'message' => __d('field', 'Invalid date/time given.'),
            ]);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function entityAfterValidate(Event $event, Field $field, $options, $validator)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function entityBeforeDelete(Event $event, Field $field, $options)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function entityAfterDelete(Event $event, Field $field, $options)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function instanceInfo(Event $event)
    {
        return [
            'type' => 'datetime',
            'name' => __d('field', 'Date'),
            'description' => __d('field', 'Allows to attach date picker to contents.'),
            'hidden' => false,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function instanceSettingsForm(Event $event, $instance, $options = [])
    {
        $View = $event->subject();
        return $View->element('Field.DateField/settings_form', compact('instance', 'options'));
    }

    /**
     * {@inheritDoc}
     */
    public function instanceSettingsDefaults(Event $event, $instance, $options = [])
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function instanceSettingsValidate(Event $event, array $settings, $validator)
    {
        $validator
            ->allowEmpty('time_format')
            ->add('time_format', 'validTimeFormat', [
                'rule' => function ($value, $context) use ($settings) {
                    if (empty($settings['timepicker'])) {
                        return true;
                    }
                    return DateToolbox::validateTimeFormat($value);
                },
                'message' => __d('field', 'Invalid time format.')
            ])
            ->allowEmpty('format')
            ->add('format', 'validDateFormat', [
                'rule' => function ($value, $context) {
                    return DateToolbox::validateDateFormat($value);
                },
                'message' => __d('field', 'Invalid date format.')
            ]);
    }

    /**
     * {@inheritDoc}
     */
    public function instanceViewModeForm(Event $event, $instance, $options = [])
    {
        $View = $event->subject();
        return $View->element('Field.DateField/view_mode_form', compact('instance', 'options'));
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
                    'hooktags' => false,
                    'hidden' => false,
                ];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function instanceViewModeValidate(Event $event, array $settings, $validator)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function instanceBeforeAttach(Event $event, $instance, $options = [])
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function instanceAfterAttach(Event $event, $instance, $options = [])
    {
    }

    /**
     * {@inheritDoc}
     */
    public function instanceBeforeDetach(Event $event, $instance, $options = [])
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function instanceAfterDetach(Event $event, $instance, $options = [])
    {
    }
}
