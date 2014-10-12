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
namespace Field\Model\Table;

use Cake\Database\Schema\Table as Schema;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Field\Model\Entity\FieldInstance;
use QuickApps\Event\HookAwareTrait;
use QuickApps\View\ViewModeAwareTrait;

/**
 * Represents "field_instances" database table.
 *
 * This table holds information about all fields attached to tables.
 * It also triggers Field Instances's events:
 *
 * - `Field.<FieldHandler>.Instance.info`: When QuickAppsCMS asks for information about each registered Field
 * - `Field.<FieldHandler>.Instance.settingsForm`: Additional settings for this field. Should define the way the values will be stored in the database.
 * - `Field.<FieldHandler>.Instance.settingsDefaults`: Default values for field settings form's inputs
 * - `Field.<FieldHandler>.Instance.viewModeForm`: Additional formatter options. Show define the way the values will be rendered for a particular view mode.
 * - `Field.<FieldHandler>.Instance.viewModeDefaults`: Default values for view mode settings form's inputs
 * - `Field.<FieldHandler>.Instance.beforeValidate`: Before field is validated when it's being attached to Tables
 * - `Field.<FieldHandler>.Instance.afterValidate`: After field is validated when it's being attached to Tables
 * - `Field.<FieldHandler>.Instance.beforeAttach`: Before field is attached to Tables
 * - `Field.<FieldHandler>.Instance.afterAttach`: After field is attached to Tables
 * - `Field.<FieldHandler>.Instance.beforeDetach`: Before field is detached from Tables
 * - `Field.<FieldHandler>.Instance.afterDetach`: After field is detached from Tables
 */
class FieldInstancesTable extends Table {

	use HookAwareTrait;
	use ViewModeAwareTrait;

/**
 * Alter the schema used by this table.
 *
 * @param \Cake\Database\Schema\Table $table The table definition fetched from database
 * @return \Cake\Database\Schema\Table the altered schema
 */
	protected function _initializeSchema(Schema $table) {
		$table->columnType('settings', 'serialized');
		$table->columnType('view_modes', 'serialized');
		return $table;
	}

/**
 * Default validation rules set.
 *
 * @param \Cake\Validation\Validator $validator The validator object
 * @return \Cake\Validation\Validator
 */
	public function validationDefault(Validator $validator) {
		$validator
			->add('slug', [
				'notEmpty' => [
					'rule' => 'notEmpty',
					'message' => __d('field', 'You need to provide a machine name.'),
				],
				'length' => [
					'rule' => ['minLength', 3],
					'message' => __d('field', 'Machine name need to be at least 3 characters long.'),
				],
				'regExp' => [
					'rule' => function ($value, $context) {
						return preg_match('/^[a-z\d\-]+$/', $value) > 0;
					},
					'message' => __d('field', 'Only lowercase letters, numbers and "-" symbol are allowed.'),
					'provider' => 'table',
				],
				'unique' => [
					'rule' => 'validateUnique',
					'provider' => 'table',
					'message' => __d('field', 'The machine name is already in use.'),
				]
			])
			->notEmpty('table_alias', __d('field', 'Invalid table alias.'))
			->notEmpty('handler', __d('field', 'Invalid field type.'))
			->add('label', [
				'notEmpty' => [
					'rule' => 'notEmpty',
					'message' => __d('field', 'You need to provide a label.'),
				],
				'length' => [
					'rule' => ['minLength', 3],
					'message' => __d('field', 'Label need to be at least 3 characters long'),
				],
			]);

		return $validator;
	}

/**
 * Instance's settings validator.
 *
 * @param \Cake\Validation\Validator $validator The validator object
 * @return \Cake\Validation\Validator
 */
	public function validationSettings(Validator $validator) {
		return $validator;
	}

/**
 * Instance's view mode validator.
 *
 * This rules are apply to a single view mode.
 *
 * @param \Cake\Validation\Validator $validator The validator object
 * @return \Cake\Validation\Validator
 */
	public function validationViewMode(Validator $validator) {
		return $validator;
	}

/**
 * Here we set default values for each view mode if they were not defined before.
 *
 * @param \Cake\Event\Event $event The event that was triggered
 * @param \Cake\ORM\Query $query The query object
 * @param array $options Additional options given as an array
 * @param bool $primary Whether this find is a primary query or not
 * @return void
 */
	public function beforeFind(Event $event, Query $query, array $options, $primary) {
		$viewModes = $this->viewModes();
		$query->formatResults(function ($results) use ($viewModes) {
			return $results->map(function ($instance) use ($viewModes) {
				foreach ($viewModes as $viewMode) {
					$instanceViewModes = $instance->view_modes;
					$viewModeDefaults = array_merge([
						'label_visibility' => 'above',
						'hooktags' => false,
						'hidden' => false,
						'ordering' => 0,
					], (array)$this->trigger("Field.{$instance->handler}.Instance.viewModeDefaults", $instance, ['viewMode' => $viewMode])->result);

					if (!isset($instanceViewModes[$viewMode])) {
						$instanceViewModes[$viewMode] = [];
					}

					$instanceViewModes[$viewMode] = array_merge($viewModeDefaults, $instanceViewModes[$viewMode]);
					$instance->set('view_modes', $instanceViewModes);
				}

				$settingsDefaults = (array)$this->trigger("Field.{$instance->handler}.Instance.settingsDefaults", $instance, [])->result;
				if (!empty($settingsDefaults)) {
					$instanceSettings = $instance->get('settings');
					foreach ($settingsDefaults as $k => $v) {
						if (!isset($instanceSettings[$k])) {
							$instanceSettings[$k] = $v;
						}
					}
					$instance->set('settings', $instanceSettings);
				}

				return $instance;
			});
		});
	}

/**
 * Triggers the "Field.<FieldHandler>.Instance.settingsValidate" event.
 *
 * @param \Cake\Event\Event $event The event that was triggered
 * @param \Cake\ORM\Entity $settings Settings being validated
 * @param array $options Additional options given as an array
 * @param \Cake\Validation\Validator $validator The validator object
 * @return bool False if save operation should not continue, true otherwise
 */
	public function beforeValidate(Event $event, Entity $settings, $options, Validator $validator) {
		if (isset($options['validate']) && in_array($options['validate'], ['settings', 'viewMode'])) {
			$eventName = $options['validate'] == 'settings' ? 'settingsValidate' : 'viewModeValidate';
			$instanceEvent = $this->trigger(["Field.{$settings->get('_field_handler')}.Instance.{$eventName}", $event->subject], $settings, $validator);
			if ($instanceEvent->isStopped() || $instanceEvent->result === false) {
				return false;
			}
			return true;
		}
	}

/**
 * Triggers the "Field.<FieldHandler>.Instance.afterValidate" event.
 *
 * @param \Cake\Event\Event $event The event that was triggered
 * @param \Cake\ORM\Entity $instance The Field Instance that is going to be validated
 * @param array $options Additional options given as an array
 * @param \Cake\Validation\Validator $validator The validator object
 * @return void
 */
	public function afterValidate(Event $event, Entity $instance, $options, Validator $validator) {
	}

/**
 * Triggers the "Field.<FieldHandler>.Instance.beforeAttach" event.
 *
 * @param \Cake\Event\Event $event The event that was triggered
 * @param \Field\Model\Entity\FieldInstance $instance The Field Instance that is going to be saved
 * @param array $options The options passed to the save method
 * @return bool False if save operation should not continue, true otherwise
 */
	public function beforeSave(Event $event, FieldInstance $instance, $options = []) {
		$instanceEvent = $this->trigger(["Field.{$instance->handler}.Instance.beforeAttach", $event->subject], $instance, $options);
		if ($instanceEvent->isStopped() || $instanceEvent->result === false) {
			return false;
		}
		return true;
	}

/**
 * Triggers the "Field.<FieldHandler>.Instance.afterAttach" event.
 * 
 * @param \Cake\Event\Event $event The event that was triggered
 * @param \Field\Model\Entity\FieldInstance $instance The Field Instance that was saved
 * @param array $options the options passed to the save method
 * @return void
 */
	public function afterSave(Event $event, FieldInstance $instance, $options = []) {
		$this->trigger(["Field.{$instance->handler}.Instance.afterAttach", $event->subject], $instance, $options);
	}

/**
 * Triggers the "Field.<FieldHandler>.Instance.beforeDetach" event.
 *
 * @param \Cake\Event\Event $event The event that was triggered
 * @param \Field\Model\Entity\FieldInstance $instance The Field Instance that is going to be deleted
 * @param array $options the options passed to the delete method
 * @return bool False if delete operation should not continue, true otherwise
 */
	public function beforeDelete(Event $event, FieldInstance $instance, $options = []) {
		$instanceEvent = $this->trigger(["Field.{$instance->handler}.Instance.beforeDetach", $event->subject], $instance, $options);
		if ($instanceEvent->isStopped() || $instanceEvent->result === false) {
			return false;
		}
		return true;
	}

/**
 * Triggers the "Field.<FieldHandler>.Instance.afterDetach" event.
 * it also deletes all associated records in the `field_values` table.
 *
 * @param \Cake\Event\Event $event The event that was triggered
 * @param \Field\Model\Entity\FieldInstance $instance The Field Instance that was deleted
 * @param array $options the options passed to the delete method
 * @return void
 */
	public function afterDelete(Event $event, FieldInstance $instance, $options = []) {
		$FieldValues = TableRegistry::get('Field.FieldValues');
		$FieldValues->deleteAll(['field_instance_id' => $instance->id]);
		$this->trigger(["Field.{$instance->handler}.Instance.afterDetach", $event->subject], $instance, $options);
	}

}
