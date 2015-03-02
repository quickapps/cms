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
use Cake\Routing\Router;
use Field\BaseHandler;
use Field\Event\DateField;
use Field\Model\Entity\Field;
use Field\Utility\DateToolbox;

/**
 * Publish Date Field Handler.
 *
 * Allows scheduling of contents by making them available only between
 * certain dates.
 */
class PublishDateField extends BaseHandler
{

    /**
     * {@inheritDoc}
     */
    public function entityDisplay(Event $event, Field $field, $options = [])
    {
        $View = $event->subject();
        $field->raw = array_merge([
            'from' => ['string' => null, 'timestamp' => null],
            'to' => ['string' => null, 'timestamp' => null],
        ], (array)$field->raw);
        return $View->element('Field.PublishDateField/display', compact('field', 'options'));
    }

    /**
     * {@inheritDoc}
     */
    public function entityEdit(Event $event, Field $field, $options = [])
    {
        $View = $event->subject();
        return $View->element('Field.PublishDateField/edit', compact('field', 'options'));
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
        if ($primary &&
            !Router::getRequest()->isAdmin() &&
            !empty($field->raw['from']['timestamp']) &&
            !empty($field->raw['to']['timestamp'])
        ) {
            $now = time();
            if ($field->raw['from']['timestamp'] > $now ||
                $now > $field->raw['to']['timestamp']
            ) {
                return false;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function entityBeforeSave(Event $event, Field $field, $options)
    {
        $values = [];
        $raw = [
            'from' => ['string' => null, 'timestamp' => null],
            'to' => ['string' => null, 'timestamp' => null],
        ];
        foreach (['from', 'to'] as $type) {
            if (!empty($options['_post'][$type]['string']) &&
                !empty($options['_post'][$type]['format'])
            ) {
                $date = $options['_post'][$type]['string'];
                $format = $options['_post'][$type]['format'];
                if ($date = DateToolbox::createFromFormat($format, $date)) {
                    $raw[$type]['string'] = $options['_post'][$type]['string'];
                    $raw[$type]['timestamp'] = date_timestamp_get($date);
                    $values[] = $raw[$type]['timestamp'] . ' ' . $options['_post'][$type]['string'];
                } else {
                    $typeLabel = $type == 'from' ? __d('field', 'Start') : __d('field', 'Finish');
                    $field->metadata->entity->errors(":{$field->name}", __d('field', 'Invalid date/time range, "{0}" date must match the the pattern: {1}', $typeLabel, $format));
                    return false;
                }
            }
        }

        $field->set('value', implode(' ', $values));
        $field->set('raw', $raw);
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
        if ($field->metadata->required) {
            $validator->notEmpty(":{$field->name}", __d('field', 'You must select a date/time range.'));
        }

        $validator
            ->add(":{$field->name}", [
                'validRange' => [
                    'rule' => function ($value, $context) {
                        if (!empty($value['from']['string']) &&
                            !empty($value['from']['format']) &&
                            !empty($value['to']['string']) &&
                            !empty($value['to']['format'])
                        ) {
                            $from = DateToolbox::createFromFormat($value['from']['format'], $value['from']['string']);
                            $to = DateToolbox::createFromFormat($value['to']['format'], $value['to']['string']);
                            return date_timestamp_get($from) < date_timestamp_get($to);
                            ;
                        }
                        return false;
                    },
                    'message' => __d('field', 'Invalid date/time range, "Start" date must be before "Finish" date.')
                ]
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
            'name' => __d('field', 'Publishing Date'),
            'description' => __d('field', 'Allows scheduling of contents by making them available only between certain dates.'),
            'hidden' => false,
            'maxInstances' => 1,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function instanceSettingsForm(Event $event, $instance, $options = [])
    {
        $View = $event->subject();
        return $View->element('Field.PublishDateField/settings_form', compact('instance', 'options'));
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
        return $View->element('Field.PublishDateField/view_mode_form', compact('instance', 'options'));
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
