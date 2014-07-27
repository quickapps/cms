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
namespace Field\Utility;

use Cake\Event\Event;
use Cake\Event\EventListener;
use Cake\ORM\TableRegistry;
use QuickApps\Utility\AlertTrait;
use QuickApps\Utility\HooktagTrait;
use QuickApps\Utility\HookTrait;

/**
 * Field Handler base class.
 *
 * All Field Handlers classes should extend this class.
 * It adds some utility methods, and define some default events handlers.
 */
class FieldHandler implements EventListener {

	use AlertTrait;
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
 * - `Entity` or `Instance`: "Entity" for events related to enties (an User, a Node, etc),
 * or "Instance" for field instances events.
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
			"Field.{$handlerName}.Entity.formatter" => 'entityFormatter',
			"Field.{$handlerName}.Entity.beforeFind" => 'entityBeforeFind',
			"Field.{$handlerName}.Entity.afterSave" => 'entityAfterSave',
			"Field.{$handlerName}.Entity.beforeValidate" => 'entityBeforeValidate',
			"Field.{$handlerName}.Entity.afterValidate" => 'entityAfterValidate',
			"Field.{$handlerName}.Entity.beforeDelete" => 'entityBeforeDelete',
			"Field.{$handlerName}.Entity.afterDelete" => 'entityAfterDelete',

			// Related to Instance:
			"Field.{$handlerName}.Instance.info" => 'instanceInfo',
			"Field.{$handlerName}.Instance.settings" => 'instanceSettings',
			"Field.{$handlerName}.Instance.beforeAttach" => 'instanceBeforeAttach',
			"Field.{$handlerName}.Instance.afterAttach" => 'instanceAfterAttach',
			"Field.{$handlerName}.Instance.beforeDetach" => 'instanceBeforeDetach',
			"Field.{$handlerName}.Instance.afterDetach" => 'instanceAfterDetach',
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
 * All fields should have a 'default' formatter.
 *
 * Any number of other formatters can be defined as well.
 * It's nice for there always to be a 'plain' option
 * for the `value` value, but that is not required.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return string HTML
 */
	public function entityFormatter(Event $event, $field, $options = []) {
		return '';
	}

/**
 * After each entity is saved.
 *
 * The options array contains the `post` key, which holds
 * all the information you need to update you field:
 *
 *     $options['post']
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param \Cake\ORM\Entity $entity The entity which started the event
 * @param array $options
 * @return void
 */
	public function entityAfterSave(Event $event, $field, $entity, $options) {
	}

/**
 * Before an entity is validated as part of save process.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param \Cake\ORM\Entity $entity The entity which started the event
 * @param array $options
 * @param \Cake\Validation\Validator $validator
 * @return boolean False will halt the save process
 */
	public function entityBeforeValidate(Event $event, $field, $entity, $options, $validator) {
		return false;
	}

/**
 * After an entity is validated as part of save process.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param \Cake\ORM\Entity $entity The entity which started the event
 * @param array $options
 * @param \Cake\Validation\Validator $validator [description]
 * @return boolean False will halt the save process
 */
	public function entityAfterValidate(Event $event, $field, $entity, $options, $validator) {
		return false;
	}

/**
 * Before an entity is deleted from database.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param \Cake\ORM\Entity $entity The entity which started the event
 * @param array $options
 * @return boolean False will halt the delete process
 */
	public function entityBeforeDelete(Event $event, $field, $entity, $options) {
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
	public function entityAfterDelete(Event $event, $field, $options) {
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
 * Field settings will be the same for all shared instances of the same field and should
 * define the way the value will be stored in the database.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\Field $field Field information
 * @param array $options
 * @return string HTML form elements for the settings page
 */
	public function instanceSettings(Event $event, $field, $options = []) {
		return '';
	}

/**
 * Before an new instance of this field is attached to a database table.
 *
 * Stopping this event will abort the attach operation.
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance information
 * @param array $options
 * @return boolean False will halt the attach process
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
 * @return boolean False will halt the detach process
 */
	public function instanceBeforeDetach(Event $event, $instance, $options = []) {
		return false;
	}

/**
 * After an instance of this field was detached from a database table.
 *
 * Here is when you should remove all the stored data for this instance from the DB.
 * For example, if your field stores physical files for every entity, then you should dele those files.
 *
 * NOTE: By default QuickApps CMS, automatically removes all related records from the `field_values` table. 
 *
 * @param \Cake\Event\Event $event The event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance Instance entity being detached (deleted from "field_instances" table)
 * @param array $options
 * @return void
 */
	public function instanceAfterDetach(Event $event, $instance, $options = []) {
	}

}
