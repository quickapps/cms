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
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Field\Model\Entity\FieldInstance;
use QuickApps\Utility\HookTrait;
use QuickApps\View\ViewModeTrait;

/**
 * Represents "field_instances" database table.
 *
 * This table holds information about all fields attached to tables.
 */
class FieldInstancesTable extends Table {

	use HookTrait;
	use ViewModeTrait;

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
 * @param \Cake\Validation\Validator $validator
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
					'rule' => function($value, $context) {
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
 * Here we set default values for each view mode if they were not defined before.
 * 
 * @param \Cake\Event\Event $event
 * @param \Cake\ORM\Query $query
 * @param array $options
 * @param boolean $primary
 * @return void
 */
	public function beforeFind(Event $event, Query $query, array $options, $primary) {
		$viewModes = $this->viewModes();
		$query->formatResults(function ($results) use ($viewModes) {
			return $results->map(function($instance) use ($viewModes) {
				foreach ($viewModes as $viewMode) {
					$view_modes = $instance->view_modes;
					$viewModeDefaults = am([
						'label_visibility' => 'above',
						'hooktags' => false,
						'hidden' => false,
						'ordering' => 0,
					], (array)$this->invoke("Field.{$instance->handler}.Instance.viewModeDefaults", $this, $instance, ['viewMode' => $viewMode])->result);

					if (!isset($view_modes[$viewMode])) {
						$view_modes[$viewMode] = [];
					}

					$view_modes[$viewMode] = am($viewModeDefaults, $view_modes[$viewMode]);
					$instance->set('view_modes', $view_modes);
				}

				$settingsDefaults = (array)$this->invoke("Field.{$instance->handler}.Instance.settingsDefaults", $this, $instance, [])->result;

				if (!empty($settingsDefaults)) {
					foreach ($settingsDefaults as $k => $v) {
						if (!isset($instance->settings[$k])) {
							$instance->settings[$k] = $v;
						}
					}
				}

				return $instance;
			});
		});
	}

/**
 * Starts the "Field.<FieldHandler>.Instance.beforeAttach" hook so Field Handlers can
 * do any logic their require.
 *
 * @param \Cake\Event\Event $event The beforeSave event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance The Field Instance that is going to be saved
 * @param array $options the options passed to the save method
 * @return boolean False if save operation should not continue, true otherwise
 */
	public function beforeSave(Event $event, FieldInstance $instance, $options = []) {
		$instanceEvent = $this->invoke("Field.{$instance->handler}.Instance.beforeAttach", $event->subject, $instance, $options);
		if ($instanceEvent->isStopped() || $instanceEvent->result === false) {
			return false;
		}
		return true;
	}

/**
 * Starts the "Field.<FieldHandler>.Instance.afterAttach" hook so Field Handlers can
 * do any logic their require.
 * 
 * @param \Cake\Event\Event $event The beforeSave event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance The Field Instance that is going to be saved
 * @return void
 */
	public function afterSave(Event $event, FieldInstance $instance, $options = []) {
		$this->invoke("Field.{$instance->handler}.Instance.afterAttach", $event->subject, $instance, $options);
	}

/**
 * Starts the "Field.<FieldHandler>.Instance.beforeDetach" hook so Field Handlers can
 * do any logic their require.
 *
 * @param \Cake\Event\Event $event The beforeSave event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance The Field Instance that is going to be deleted
 * @param array $options the options passed to the save method
 * @return boolean False if delete operation should not continue, true otherwise
 */
	public function beforeDelete(Event $event, FieldInstance $instance, $options = []) {
		$instanceEvent = $this->invoke("Field.{$instance->handler}.Instance.beforeDetach", $event->subject, $instance, $options);
		if ($instanceEvent->isStopped() || $instanceEvent->result === false) {
			return false;
		}
		return true;
	}

/**
 * Starts the "Field.<FieldHandler>.Instance.afterDetach" hook so Field Handlers can
 * do any logic their require. Also, automatically deletes all associated records in the `field_values` tables.
 *
 * @param \Cake\Event\Event $event The beforeSave event that was fired
 * @param \Field\Model\Entity\FieldInstance $instance The Field Instance that is going to be deleted
 * @param array $options the options passed to the save method
 * @return void
 */
	public function afterDelete(Event $event, FieldInstance $instance, $options = []) {
		$FieldValues = TableRegistry::get('Field.FieldValues');
		$FieldValues->deleteAll(['field_instance_id' => $instance->id]);
		$this->invoke("Field.{$instance->handler}.Instance.afterDetach", $event->subject, $instance, $options);
	}

}
