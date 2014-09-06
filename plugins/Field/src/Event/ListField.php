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
namespace Field\Event;

use Cake\Event\Event;
use Field\Core\FieldHandler;
use Field\Model\Entity\Field;
use Field\Utility\TextToolbox;

/**
 * List Field Handler.
 *
 * Defines list field types, used to create selection lists.
 */
class ListField extends FieldHandler {

/**
 * {@inheritDoc}
 */
	public function entityDisplay(Event $event, Field $field, $options = []) {
		$View = $event->subject;
		return $View->element('Field.ListField/display', compact('field', 'options'));
	}

/**
 * {@inheritDoc}
 */
	public function entityEdit(Event $event, Field $field, $options = []) {
		$View = $event->subject;
		return $View->element('Field.ListField/edit', compact('field', 'options'));
	}

/**
 * {@inheritDoc}
 */
	public function entityFieldAttached(Event $event, Field $field) {
	}

/**
 * {@inheritDoc}
 */
	public function entityBeforeFind(Event $event, Field $field, $options, $primary) {
	}

/**
 * {@inheritDoc}
 */
	public function entityBeforeSave(Event $event, Field $field, $options) {
		$value = $options['_post'];
		if (is_array($value)) {
			$value = implode(' ', array_values($value));
		}
		$field->set('value', $value);
		$field->set('extra', $options['_post']); // force it to be a string if it is
	}

/**
 * {@inheritDoc}
 */
	public function entityAfterSave(Event $event, Field $field, $options) {
	}

/**
 * {@inheritDoc}
 */
	public function entityBeforeValidate(Event $event, Field $field, $options, $validator) {
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
 * {@inheritDoc}
 */
	public function entityAfterValidate(Event $event, Field $field, $options, $validator) {
		return true;
	}

/**
 * {@inheritDoc}
 */
	public function entityBeforeDelete(Event $event, Field $field, $options) {
		return true;
	}

/**
 * {@inheritDoc}
 */
	public function entityAfterDelete(Event $event, Field $field, $options) {
	}

/**
 * {@inheritDoc}
 */
	public function instanceInfo(Event $event) {
		return [
			'name' => __d('field', 'List'),
			'description' => __d('field', 'Defines list field types, used to create selection lists.'),
			'hidden' => false
		];
	}

/**
 * {@inheritDoc}
 */
	public function instanceSettingsForm(Event $event, $instance, $options = []) {
		$View = $event->subject;
		return $View->element('Field.ListField/settings_form', compact('instance', 'options'));
	}

/**
 * {@inheritDoc}
 */
	public function instanceSettingsDefaults(Event $event, $instance, $options = []) {
		return [];
	}

/**
 * {@inheritDoc}
 */
	public function instanceViewModeForm(Event $event, $instance, $options = []) {
		$View = $event->subject;
		return $View->element('Field.ListField/view_mode_form', compact('instance', 'options'));
	}

/**
 * {@inheritDoc}
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
 * {@inheritDoc}
 */
	public function instanceBeforeAttach(Event $event, $instance, $options = []) {
		return true;
	}

/**
 * {@inheritDoc}
 */
	public function instanceAfterAttach(Event $event, $instance, $options = []) {
	}

/**
 * {@inheritDoc}
 */
	public function instanceBeforeDetach(Event $event, $instance, $options = []) {
		return true;
	}

/**
 * {@inheritDoc}
 */
	public function instanceAfterDetach(Event $event, $instance, $options = []) {
	}

}
