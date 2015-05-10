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
namespace Field\Field;

use Cake\Routing\Router;
use Cake\Validation\Validator;
use Field\Handler;
use Field\Model\Entity\Field;
use Field\Model\Entity\FieldInstance;
use Field\Utility\DateToolbox;
use QuickApps\View\View;

/**
 * Publish Date Field Handler.
 *
 * Allows scheduling of contents by making them available only between
 * certain dates.
 */
class PublishDateField extends Handler
{

    /**
     * {@inheritDoc}
     */
    public function info()
    {
        return [
            'type' => 'text',
            'name' => __d('field', 'Publishing Date'),
            'description' => __d('field', 'Allows scheduling of contents by making them available only between certain dates.'),
            'hidden' => false,
            'maxInstances' => 1,
            'searchable' => false,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function render(Field $field, View $view)
    {
        $extra = array_merge([
            'from' => ['string' => null, 'timestamp' => null],
            'to' => ['string' => null, 'timestamp' => null],
        ], (array)$field->extra);
        $field->set('extra', $extra);
        return $view->element('Field.PublishDateField/display', compact('field'));
    }

    /**
     * {@inheritDoc}
     */
    public function edit(Field $field, View $view)
    {
        return $view->element('Field.PublishDateField/edit', compact('field'));
    }

    /**
     * {@inheritDoc}
     */
    public function beforeFind(Field $field, array $options, $primary)
    {
        if ($primary &&
            !Router::getRequest()->isAdmin() &&
            !empty($field->extra['from']['timestamp']) &&
            !empty($field->extra['to']['timestamp'])
        ) {
            $now = time();
            if ($field->extra['from']['timestamp'] > $now ||
                $now > $field->extra['to']['timestamp']
            ) {
                return false;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function validate(Field $field, Validator $validator)
    {
        if ($field->metadata->required) {
            $validator->notEmpty($field->name, __d('field', 'You must select a date/time range.'));
        }

        $validator
            ->add($field->name, [
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
    public function beforeSave(Field $field, $post)
    {
        $values = [];
        $extra = [
            'from' => ['string' => null, 'timestamp' => null],
            'to' => ['string' => null, 'timestamp' => null],
        ];
        foreach (['from', 'to'] as $type) {
            if (!empty($post[$type]['string']) &&
                !empty($post[$type]['format'])
            ) {
                $date = $post[$type]['string'];
                $format = $post[$type]['format'];
                if ($date = DateToolbox::createFromFormat($format, $date)) {
                    $extra[$type]['string'] = $post[$type]['string'];
                    $extra[$type]['timestamp'] = date_timestamp_get($date);
                    $values[] = $extra[$type]['timestamp'] . ' ' . $post[$type]['string'];
                } else {
                    $typeLabel = $type == 'from' ? __d('field', 'Start') : __d('field', 'Finish');
                    $field->metadata->entity->errors($field->name, __d('field', 'Invalid date/time range, "{0}" date must match the the pattern: {1}', $typeLabel, $format));
                    return false;
                }
            }
        }

        $field->set('value', implode(' ', $values));
        $field->set('extra', $extra);
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function settings(FieldInstance $instance, View $view)
    {
        return $view->element('Field.PublishDateField/settings_form', compact('instance'));
    }

    /**
     * {@inheritDoc}
     */
    public function validateSettings(FieldInstance $instance, array $settings, Validator $validator)
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
    public function viewModeSettings(FieldInstance $instance, View $view, $viewMode)
    {
        return $view->element('Field.PublishDateField/view_mode_form', compact('instance', 'viewMode'));
    }

    /**
     * {@inheritDoc}
     */
    public function defaultViewModeSettings(FieldInstance $instance, $viewMode)
    {
        return [
            'label_visibility' => 'above',
            'shortcodes' => false,
            'hidden' => false,
        ];
    }
}
