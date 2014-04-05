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

use \Cake\Event\Event;
use \Cake\Event\EventListener;

/**
 * Text Field Handler.
 *
 * This field allows to store text information, such as textboxes, textareas, etc.
 */
class Text implements EventListener {

/**
 * Return a list of implemented events.
 *
 * Events names must be named as follow:
 *
 *     PluginName\FieldHandlerName.<Entity|Instance>.actionName
 *
 * Example:
 *
 *     Taxonomy\CategoriesField.Entity.edit
 *
 * @return array
 */
	public function implementedEvents() {
		return [
			// Related to Entity:
			'Field.Text.Entity.display' => 'display',
			'Field.Text.Entity.edit' => 'edit',
			'Field.Text.Entity.formatter' => 'formatter',
			'Field.Text.Entity.beforeFind' => 'beforeFind',
			'Field.Text.Entity.beforeSave' => 'beforeSave',
			'Field.Text.Entity.beforeValidate' => 'beforeValidate',
			'Field.Text.Entity.afterValidate' => 'afterValidate',
			'Field.Text.Entity.beforeDelete' => 'beforeDelete',
			'Field.Text.Entity.afterDelete' => 'afterDelete',

			// Related to Instance:
			'Field.Text.Instance.info' => 'info',
			'Field.Text.Instance.settings' => 'settings',
		];
	}

/**
 * Returns an array of information of this field.
 *
 * - `name`: string, Human readable name of this field. ex. `Selectbox`
 * - `description`: string, Something about what this field does or allows to do.
 * - `hidden`: true|false, If set to false users can not use this field via `Field UI`
 *
 * @param \Cake\Event\Event $event
 * @return array
 */
	public function info(Event $event) {
		return [
			'name' => __d('field', 'Text'),
			'description' => __d('field', 'Allow to store text data in database.'),
			'hidden' => false
		];
	}

/**
 * Defines how the field will actually display its contents
 * when rendering entities.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options Additional array of options
 * @return string HTML representation of this field
 */
	public function display(Event $event, &$field, &$options = []) {
		$View = $event->subject;
		return $View->element('Field.text_field_display', compact('field', 'options'));
	}

/**
 * Renders field in edit mode.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return string HTML containing from elements
 */
	public function edit(Event $event, &$field, &$options = []) {
		$View = $event->subject;
		return $View->element('Field.text_field_edit', compact('field', 'options'));
	}

/**
 * All fields should have a 'default' formatter.
 * Any number of other formatters can be defined as well.
 * It's nice for there always to be a 'plain' option
 * for the `value` value, but that is not required.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return string HTML
 */
    public function formatter(Event $event, &$field, &$options = []) {
		$View = $event->subject;
        return $View->element('Field.text_field_formatter', compact('field', 'options'));
    }

/**
 * Renders all the form elements to be used on the field settings form.
 * Field settings will be the same for all shared instances of the same field and should
 * define the way the value will be stored in the database.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return string HTML form elements for the settings page
 */
	public function settings(Event $event, &$field, &$options = []) {
		$View = $event->subject;
		return $View->element('Field.text_field_settings', compact('field', 'options'));
	}

/**
 * Before each entity is saved.
 *
 * The options array contains the `post` key, which holds
 * all the information you need to update you field.
 *
 *     $options['post']
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return void
 */
	public function beforeSave(Event $event, &$field, &$options) {
		$field->set('value', $options['post']);
	}

/**
 * Before an entity is validated as part of save process.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @param Validator $validator [description]
 * @return boolean False will halt the save process
 */
	public function beforeValidate(Event $event, &$field, &$options, &$validator) {
		return true;
	}

/**
 * After an entity is validated as part of save process.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @param Validator $validator [description]
 * @return boolean False will halt the save process
 */
	public function afterValidate(Event $event, &$field, &$options, &$validator) {
		return true;
	}

/**
 * Before an entity is deleted from database.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return boolean False will halt the delete process
 */
	public function beforeDelete(Event $event, &$field, &$options) {
		return true;
	}

/**
 * After an entity is deleted from database.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return void
 */
	public function afterDelete(Event $event, &$field, &$options) {
		return;
	}

/**
 * Before an new instance of this field is attached to a database table.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param \Cake\ORM\Table $table The table which $field is being attached to
 * @return boolean False will halt the attach process
 */
	public function beforeAttach(Event $event, &$field, &$table) {
		return true;
	}

/**
 * After an new instance of this field is attached to a database table.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param \Cake\ORM\Table $table The table which $field was attached to
 * @return void
 */
	public function afterAttach(Event $event, $field, $table) {
		return;
	}

}
