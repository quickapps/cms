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
use CMS\Shortcode\ShortcodeTrait;
use CMS\View\View;
use Field\Handler;
use Field\Model\Entity\Field;
use Field\Model\Entity\FieldInstance;
use Field\Utility\TextToolbox;

/**
 * Text Field Handler.
 *
 * This field allows to store text information, such as textboxes, textareas, etc.
 */
class TextField extends Handler
{

    use ShortcodeTrait;

    /**
     * {@inheritDoc}
     */
    public function info()
    {
        return [
            'type' => 'text',
            'name' => __d('field', 'Text'),
            'description' => __d('field', 'Allow to store text data in database.'),
            'hidden' => false,
            'maxInstances' => 0,
            'searchable' => true,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function render(Field $field, View $view)
    {
        $value = TextToolbox::process($field->value, $field->metadata->settings['text_processing']);
        $field->set('value', $value);
        return $view->element('Field.TextField/display', compact('field'));
    }

    /**
     * {@inheritDoc}
     */
    public function edit(Field $field, View $view)
    {
        return $view->element('Field.TextField/edit', compact('field'));
    }

    /**
     * {@inheritDoc}
     */
    public function validate(Field $field, Validator $validator)
    {
        if ($field->metadata->required) {
            $validator
                ->requirePresence($field->name, __d('field', 'This field required.'))
                ->add($field->name, 'notEmpty', [
                    'rule' => function ($value, $context) use ($field) {
                        if ($field->metadata->settings['type'] === 'textarea') {
                            $clean = html_entity_decode(trim(strip_tags($value)));
                        } else {
                            $clean = trim(strip_tags($value));
                        }
                        return !empty($clean);
                    },
                    'message' => __d('field', 'This field cannot be left empty.'),
                ]);
        } else {
            $validator->allowEmpty($field->name, true);
        }

        if ($field->metadata->settings['type'] === 'text' &&
            !empty($field->metadata->settings['max_len']) &&
            $field->metadata->settings['max_len'] > 0
        ) {
            $validator
                ->add($field->name, 'validateLen', [
                    'rule' => function ($value, $context) use ($field) {
                        return strlen(trim($value)) <= $field->metadata->settings['max_len'];
                    },
                    'message' => __d('field', 'Max. {0,number} characters length.', $field->metadata->settings['max_len']),
                ]);
        }

        if (!empty($field->metadata->settings['validation_rule'])) {
            if (!empty($field->metadata->settings['validation_message'])) {
                $message = $this->shortcodes($field->metadata->settings['validation_message']);
            } else {
                $message = __d('field', 'Invalid field.', $field->label);
            }

            $validator
                ->add($field->name, 'validateReg', [
                    'rule' => function ($value, $context) use ($field) {
                        return preg_match($field->metadata->settings['validation_rule'], $value) === 1;
                    },
                    'message' => $message,
                ]);
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function beforeSave(Field $field, $post)
    {
        $field->set('extra', null);
        $field->set('value', $post);
    }

    /**
     * {@inheritDoc}
     */
    public function settings(FieldInstance $instance, View $view)
    {
        return $view->element('Field.TextField/settings_form', compact('instance'));
    }

    /**
     * {@inheritDoc}
     */
    public function defaultSettings(FieldInstance $instance)
    {
        return [
            'type' => 'textarea',
            'text_processing' => 'full',
            'max_len' => '',
            'validation_rule' => '',
            'validation_message' => '',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function viewModeSettings(FieldInstance $instance, View $view, $viewMode)
    {
        return $view->element('Field.TextField/view_mode_form', compact('instance', 'viewMode'));
    }

    /**
     * {@inheritDoc}
     */
    public function defaultViewModeSettings(FieldInstance $instance, $viewMode)
    {
        return [
            'label_visibility' => 'above',
            'shortcodes' => true,
            'hidden' => false,
            'formatter' => 'full',
            'trim_length' => '',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function validateViewModeSettings(FieldInstance $instance, array $settings, Validator $validator, $viewMode)
    {
        if (!empty($settings['formatter']) && $settings['formatter'] == 'trimmed') {
            $validator
                ->requirePresence('trim_length', __d('field', 'Invalid trimmer string.'))
                ->notEmpty('trim_length', __d('field', 'Invalid trimmer string.'));
        }
    }
}
