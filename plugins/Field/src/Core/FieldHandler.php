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
namespace Field\Core;

use Cake\Event\Event;
use Cake\Event\EventListener;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use QuickApps\Core\HooktagTrait;
use QuickApps\Core\HookTrait;

/**
 * Field Handler base class.
 *
 * All Field Handlers classes should extend this class. It adds some utility
 * methods, and define some default events handlers.
 */
class FieldHandler implements EventListener {

	use HooktagTrait;
	use HookTrait;

/**
 * Return a list of implemented events.
 *
 * Events names must be named as follow:
 *
 *     Field.<FieldHandlerName>.<Entity|Instance>.<eventName>
 *
 * Example:
 *
 *     Field.TextField.Entity.edit
 *
 * Where:
 *
 * - `Field`: Prefix, is the event subspace.
 * - `TextField`: Name of the class for Text Handler in this example.
 * - `Entity` or `Instance`: "Entity" for events related to entities
 *    (an User, a Node, etc), or "Instance" for field instances events.
 * - `edit`: The name of the event.
 *
 * You can override this method and provide a customized set of event handlers.
 *
 * @return array
 */
	public function implementedEvents() {
		$handlerName = explode('\\', get_class($this));
		$handlerName = array_pop($handlerName);

		return [
			// Related to Entity:
			"Field.{$handlerName}.Entity.display" => 'entityDisplay',
			"Field.{$handlerName}.Entity.edit" => 'entityEdit',
			"Field.{$handlerName}.Entity.beforeSave" => 'entityBeforeSave',
			"Field.{$handlerName}.Entity.afterSave" => 'entityAfterSave',
			"Field.{$handlerName}.Entity.beforeValidate" => 'entityBeforeValidate',
			"Field.{$handlerName}.Entity.afterValidate" => 'entityAfterValidate',
			"Field.{$handlerName}.Entity.beforeDelete" => 'entityBeforeDelete',
			"Field.{$handlerName}.Entity.afterDelete" => 'entityAfterDelete',

			// Related to Instance:
			"Field.{$handlerName}.Instance.info" => 'instanceInfo',
			"Field.{$handlerName}.Instance.settingsForm" => 'instanceSettingsForm',
			"Field.{$handlerName}.Instance.settingsDefaults" => 'instanceSettingsDefaults',
			"Field.{$handlerName}.Instance.settingsValidate" => 'instanceSettingsValidate',
			"Field.{$handlerName}.Instance.viewModeForm" => 'instanceViewModeForm',
			"Field.{$handlerName}.Instance.viewModeDefaults" => 'instanceViewModeDefaults',
			"Field.{$handlerName}.Instance.viewModeValidate" => 'instanceViewModeValidate',
			"Field.{$handlerName}.Instance.beforeAttach" => 'instanceBeforeAttach',
			"Field.{$handlerName}.Instance.afterAttach" => 'instanceAfterAttach',
			"Field.{$handlerName}.Instance.beforeDetach" => 'instanceBeforeDetach',
			"Field.{$handlerName}.Instance.afterDetach" => 'instanceAfterDetach',
		];
	}

/**
 * Defines how the field will actually display its contents when rendering entities.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options Additional array of options
 * @return string HTML representation of this field
 */
	public function entityDisplay(Event $event, $field, $options = []) {
		return '';
	}

/**
 * Renders field in edit mode.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return string HTML containing from elements
 */
	public function entityEdit(Event $event, $field, $options = []) {
		return '';
	}

/**
 * Before each entity is saved.
 *
 * Returning a non-true value will halt the save operation, as stopping
 * the event as well.
 *
 * The options array contains the `post` key, which holds all the information
 * you need to update you field:
 *
 *     $options['post']
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
 * After each entity is saved.
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
 * Before an entity is validated as part of save process.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $entity The entity to which field is attached to
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @param \Cake\Validation\Validator $validator
 * @return bool False will halt the save process
 */
	public function entityBeforeValidate(Event $event, $entity, $field, $options, $validator) {
		return false;
	}

/**
 * After an entity is validated as part of save process.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $entity The entity to which field is attached to
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @param \Cake\Validation\Validator $validator
 * @return bool False will halt the save process
 */
	public function entityAfterValidate(Event $event, $entity, $field, $options, $validator) {
		return false;
	}

/**
 * Before an entity is deleted from database.
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
 * After an entity is deleted from database.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $entity The entity to which field is attached to
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return void
 */
	public function entityAfterDelete(Event $event, $entity, $field, $options) {
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
	public function instanceInfo(Event $event) {
		return [];
	}

/**
 * Renders all the form elements to be used on the field settings form.
 *
 * Field settings will be the same for all shared instances of the same field
 * and should define the way the value will be stored in the database.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return string HTML form elements for the settings page
 */
	public function instanceSettingsForm(Event $event, $instance, $options = []) {
		return '';
	}

/**
 * Returns an array of default values for field settings form's inputs.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return array
 */
	public function instanceSettingsDefaults(Event $event, $instance, $options = []) {
		return [];
	}

/**
 * Triggered before instance's settings are changed.
 *
 * Here Field Handlers can apply custom validation rules to their settings.
 * Stopping this event or returning false will halt the save operation.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $settings Settings values as an entity
 * @param \Cake\Validation\Validator $validator
 * @return mixed
 */
	public function instanceSettingsValidate(Event $event, Entity $settings, $validator) {
		return true;
	}

/**
 * Renders all the form elements to be used on the field view mode form.
 *
 * Here is where you should render form elements to hold settings about how
 * Entities should be rendered for a particular View Mode.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \\Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return string HTML form elements for the settings page
 */
	public function instanceViewModeForm(Event $event, $instance, $options = []) {
		return '';
	}

/**
 * Returns an array of defaults values for each input in the view modes form.
 *
 * You can provide different default values depending on the view mode, you can
 * use `$option['viewMode']` to distinct between view modes.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return array
 */
	public function instanceViewModeDefaults(Event $event, $instance, $options = []) {
		return [];
	}

/**
 * Triggered before instance's view mode settings are changed.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Cake\ORM\Entity $viewMode View mode's setting values as an entity
 * @param \Cake\Validation\Validator $validator
 * @return void
 */
	public function instanceViewModeValidate(Event $event, Entity $viewMode, $validator) {
	}

/**
 * Before an new instance of this field is attached to a database table.
 *
 * Stopping this event will abort the attach operation.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return bool False will halt the attach process
 */
	public function instanceBeforeAttach(Event $event, $instance, $options = []) {
		return false;
	}

/**
 * After an new instance of this field is attached to a database table.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @return void
 */
	public function instanceAfterAttach(Event $event, $instance, $options = []) {
	}

/**
 * Before an instance of this field is detached from a database table.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return bool False will halt the detach process
 */
	public function instanceBeforeDetach(Event $event, $instance, $options = []) {
		return false;
	}

/**
 * After an instance of this field was detached from a database table.
 *
 * Here is when you should remove all the stored data for this instance from
 * the DB. For example, if your field stores physical files for every entity,
 * then you should delete those files.
 *
 * NOTE: By default QuickApps CMS, automatically removes all related records
 * from the `field_values` table. 
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance entity being
 *  detached (deleted from "field_instances" table)
 * @param array $options
 * @return void
 */
	public function instanceAfterDetach(Event $event, $instance, $options = []) {
	}

}
