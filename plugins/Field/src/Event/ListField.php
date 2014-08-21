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
 * List Field Handler.
 *
 * Defines list field types, used to create selection lists.
 */
class ListField extends FieldHandler {

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
		return $View->element('Field.ListField/display', compact('field', 'options'));
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
		return $View->element('Field.ListField/edit', compact('field', 'options'));
	}

/**
 * {@inheritdoc}
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return void
 */
	public function entityBeforeSave(Event $event, $entity, $field, $options) {
		$value = $options['_post'];
		if (is_array($value)) {
			$value = implode(' ', array_values($value));
		}
		$field->set('value', $value);
		$field->set('extra', $options['_post']); // force it to be a string if it is
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
				->validatePresence(":{$field->name}")
				->notEmpty(":{$field->name}", __d('field', 'Field required.'));
		} else {
			$validator->allowEmpty(":{$field->name}");
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
			'name' => __d('field', 'List'),
			'description' => __d('field', 'Defines list field types, used to create selection lists.'),
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
		return $View->element('Field.ListField/settings_form', compact('instance', 'options'));
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
		return $View->element('Field.ListField/view_mode_form', compact('instance', 'options'));
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
					'formatter' => 'default',
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
