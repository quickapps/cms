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
namespace Field;

use Cake\Event\Event;
use Field\Utility\TextToolbox;
use Field\Utility\FieldHandler;

/**
 * Text Field Handler.
 *
 * This field allows to store text information, such as textboxes, textareas, etc.
 */
class TextField extends FieldHandler {

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options Additional array of options
 * @return string HTML representation of this field
 */
	public function entityDisplay(Event $event, $field, $options = []) {
		$View = $event->subject;
		$value = TextToolbox::process($field->value, $field->metadata->settings['text_processing']);
		$field->set('value', $value);
		return $View->element('Field.TextField/display', compact('field', 'options'));
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return string HTML containing from elements
 */
	public function entityEdit(Event $event, $field, $options = []) {
		$View = $event->subject;
		return $View->element('Field.TextField/edit', compact('field', 'options'));
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $entity The entity to which field is attached to
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return bool
 */
	public function entityBeforeSave(Event $event, $entity, $field, $options) {
		return true;
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $entity The entity to which field is attached to
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return void
 */
	public function entityAfterSave(Event $event, $entity, $field, $options) {
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $entity The entity to which field is attached to
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @param \Cake\Validation\Validator $validator
 * @return bool False will halt the save process
 */
	public function entityBeforeValidate(Event $event, $entity, $field, $options, $validator) {
		if ($field->metadata->required) {
			$validator
				->notEmpty(":{$field->name}", __d('field', 'Field required.'))
				->add(":{$field->name}", 'validateRequired', [
					'rule' => function ($value, $context) use ($field) {
						if ($field->metadata->settings['type'] === 'textarea') {
							return !empty(html_entity_decode(trim(strip_tags($value))));
						} else {
							return !empty(trim(strip_tags($value)));
						}
					},
					'message' => __d('field', 'Field required.'),
				]);
		} else {
			$validator->allowEmpty(":{$field->name}", true);
		}

		if (
			$field->metadata->settings['type'] === 'text' &&
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
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $entity The entity to which field is attached to
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @param \Cake\Validation\Validator $validator
 * @return bool False will halt the save process
 */
	public function entityAfterValidate(Event $event, $entity, $field, $options, $validator) {
		return true;
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $entity The entity to which field is attached to
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return bool False will halt the delete process
 */
	public function entityBeforeDelete(Event $event, $entity, $field, $options) {
		return true;
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $entity The entity to which field is attached to
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return void
 */
	public function entityAfterDelete(Event $event, $entity, $field, $options) {
		return;
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event
 * @return array
 */
	public function instanceInfo(Event $event) {
		return [
			'name' => __d('field', 'Text'),
			'description' => __d('field', 'Allow to store text data in database.'),
			'hidden' => false
		];
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return string HTML form elements for the settings page
 */
	public function instanceSettingsForm(Event $event, $instance, $options = []) {
		$View = $event->subject;
		return $View->element('Field.TextField/settings_form', compact('instance', 'options'));
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return array
 */
	public function instanceSettingsDefaults(Event $event, $instance, $options = []) {
		return [
			'type' => 'textarea',
			'text_processing' => 'full',
			'max_len' => '',
			'validation_rule' => '',
			'validation_message' => '',
		];
	}	

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return string HTML form elements for the settings page
 */
	public function instanceViewModeForm(Event $event, $instance, $options = []) {
		$View = $event->subject;
		return $View->element('Field.TextField/view_mode_form', compact('instance', 'options'));
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return array
 */
	public function instanceViewModeDefaults(Event $event, $instance, $options = []) {
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
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return bool False will halt the attach process
 */
	public function instanceBeforeAttach(Event $event, $instance, $options = []) {
		return true;
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @return void
 */
	public function instanceAfterAttach(Event $event, $instance, $options = []) {
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return bool False will halt the detach process
 */
	public function instanceBeforeDetach(Event $event, $instance, $options = []) {
		return true;
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return void
 */
	public function instanceAfterDetach(Event $event, $instance, $options = []) {
	}

}
