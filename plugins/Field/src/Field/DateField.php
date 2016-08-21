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

use Cake\Validation\Validator;
use CMS\View\View;
use Field\Handler;
use Field\Model\Entity\Field;
use Field\Model\Entity\FieldInstance;
use Field\Utility\DateToolbox;

/**
 * Date Field Handler.
 *
 * This field allows attach date pickers to entities.
 */
class DateField extends Handler
{

    /**
     * {@inheritDoc}
     */
    public function info()
    {
        return [
            'type' => 'datetime',
            'name' => __d('field', 'Date'),
            'description' => __d('field', 'Allows to attach date picker to contents.'),
            'hidden' => false,
            'maxInstances' => 0,
            'searchable' => false,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function render(Field $field, View $view)
    {
        return $view->element('Field.DateField/display', compact('field'));
    }

    /**
     * {@inheritDoc}
     */
    public function edit(Field $field, View $view)
    {
        return $view->element('Field.DateField/edit', compact('field'));
    }

    /**
     * {@inheritDoc}
     */
    public function validate(Field $field, Validator $validator)
    {
        if (!$field->metadata->required) {
            return true;
        }

        $validator
            ->notEmpty($field->name, __d('field', 'You must select a date/time.'))
            ->add($field->name, 'validDate', [
                'rule' => function ($value, $context) {
                    return DateToolbox::createFromFormat($value['format'], $value['date']) !== false;
                },
                'message' => __d('field', 'Invalid date/time given.'),
            ]);

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * - extra: Holds string date incoming from POST
     * - value: Holds datetime information
     */
    public function beforeSave(Field $field, $post)
    {
        if (!empty($post['date']) && !empty($post['format'])) {
            $date = $post['date'];
            $format = $post['format'];
            if ($date = DateToolbox::createFromFormat($format, $date)) {
                $field->set('extra', $post['date']);
            } else {
                $field->metadata->entity->errors($field->name, __d('field', 'Invalid date/time, it must match the the pattern: {0}', $format));

                return false;
            }
            $field->set('value', date_timestamp_get($date));
        } else {
            $field->set('value', null);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function settings(FieldInstance $instance, View $view)
    {
        return $view->element('Field.DateField/settings_form', compact('instance'));
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
        return $view->element('Field.DateField/view_mode_form', compact('instance', 'viewMode'));
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
