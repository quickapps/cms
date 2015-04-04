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
use Field\Utility\TextToolbox;

/**
 * Text Field Handler.
 *
 * This field allows to store text information, such as textboxes, textareas, etc.
 */
class TextField extends BaseHandler
{

    /**
     * {@inheritDoc}
     */
    public function entityDisplay(Event $event, Field $field, $options = [])
    {
        $View = $event->subject();
        $value = TextToolbox::process($field->value, $field->metadata->settings['text_processing']);
        $field->set('value', $value);
        return $View->element('Field.TextField/display', compact('field', 'options'));
    }

    /**
     * {@inheritDoc}
     */
    public function entityEdit(Event $event, Field $field, $options = [])
    {
        $View = $event->subject();
        return $View->element('Field.TextField/edit', compact('field', 'options'));
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
     */
    public function entityBeforeSave(Event $event, Field $field, $options)
    {
        $field->set('extra', null);
        $field->set('value', $options['_post']);
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
            $validator
                ->requirePresence(":{$field->name}", __d('field', 'This field required.'))
                ->add(":{$field->name}", 'notEmpty', [
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
            $validator->allowEmpty(":{$field->name}", true);
        }

        if ($field->metadata->settings['type'] === 'text' &&
            !empty($field->metadata->settings['max_len']) &&
            $field->metadata->settings['max_len'] > 0
        ) {
            $validator
                ->add(":{$field->name}", 'validateLen', [
                    'rule' => function ($value, $context) use ($field) {
                        return strlen(trim($value)) <= $field->metadata->settings['max_len'];
                    },
                    'message' => __d('field', 'Max. {0,number} characters length.', $field->metadata->settings['max_len']),
                ]);
        }

        if (!empty($field->metadata->settings['validation_rule'])) {
            if (!empty($field->metadata->settings['validation_message'])) {
                $message = $this->hooktags($field->metadata->settings['validation_message']);
            } else {
                $message = __d('field', 'Invalid field.', $field->label);
            }

            $validator
                ->add(":{$field->name}", 'validateReg', [
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
    public function instanceSettingsForm(Event $event, $instance, $options = [])
    {
        $View = $event->subject();
        return $View->element('Field.TextField/settings_form', compact('instance', 'options'));
    }

    /**
     * {@inheritDoc}
     */
    public function instanceSettingsDefaults(Event $event, $instance, $options = [])
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
    public function instanceSettingsValidate(Event $event, array $settings, $validator)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function instanceViewModeForm(Event $event, $instance, $options = [])
    {
        $View = $event->subject();
        return $View->element('Field.TextField/view_mode_form', compact('instance', 'options'));
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
                    'hooktags' => true,
                    'hidden' => false,
                    'formatter' => 'full',
                    'trim_length' => '',
                ];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function instanceViewModeValidate(Event $event, array $settings, $validator)
    {
        if (!empty($settings['formatter']) && $settings['formatter'] == 'trimmed') {
            $validator
                ->requirePresence('trim_length', __d('field', 'Invalid trimmer string.'))
                ->notEmpty('trim_length', __d('field', 'Invalid trimmer string.'));
        }
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
