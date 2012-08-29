<?php
/**
 * Field Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.Field.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class Field extends FieldAppModel {
	public $name = 'Field';
	public $useTable = 'fields';
	public $order = array('Field.ordering' => 'ASC');
	public $actsAs = array('Serialized' => array('settings'));
	public $validate = array(
		'label' => array('required' => true, 'allowEmpty' => false, 'rule' => array('between', 1, 128), 'message' => 'Invalid field label.'),
		'name' => array(
			'alphaNumeric' => array('required' => true, 'allowEmpty' => false, 'rule' => array('custom', '/^[a-z0-9_]{3,32}$/s'), 'message' => "Field name must only contain letters and numbers. Between 3-32 characters are required (character '_' is allowed)."),
			'isUnique' => array('required' => true, 'allowEmpty' => false, 'rule' => 'checkUnique', 'message' => 'Field name already in use.')
		),
		'field_module' => array('required' => true, 'allowEmpty' => false, 'rule' => 'notEmpty', 'message' => 'Select a field type.')
	);

/**
 * Before instance is attached to entity, Nor before instance
 * settings is updated. And before field POST validation.
 *
 * Invokes field's 'before_validate_instance'.
 * Return a non-true value to halt attachment or saving operation
 *
 * @return boolean
 */
	public function beforeValidate($options = array()) {
		// merge settings (array treatment): formatter form post
		if (isset($this->data['Field']['id']) && isset($this->data['Field']['settings'])) {
			$this->validate = false;
			$settings = $this->field('settings', array('Field.id' => $this->data['Field']['id']));
			$this->data['Field']['settings'] = Hash::merge($settings, $this->data['Field']['settings']);

			if (!isset($this->data['Field']['field_module']) || empty($this->data['Field']['field_module'])) {
				$this->data['Field']['field_module'] = $this->field('field_module', array('Field.id' => $this->data['Field']['id']));
			}
		} elseif (!isset($this->data['Field']['id'])) {
			// new field
			$default_settings = array(
				'display' => array(
					'default' => array(
						'label' => 'hidden',
						'type' => '', // formatter name
						'settings' => array(),
						'ordering' => 0
					)
				)
			);

			$this->data['Field']['settings'] = isset($this->data['Field']['settings']) ? Hash::merge($this->data['Field']['settings'], $default_settings) : $default_settings;
		}

		if ($this->hookDefined("{$this->data['Field']['field_module']}_before_validate_instance")) {
			$before = $this->hook("{$this->data['Field']['field_module']}_before_validate_instance", $this);

			if ($before !== true) {
				return false;
			}
		}

		return true;
	}

/**
 * Before instance is attached to entity, Nor before instance
 * settings is updated.
 *
 * Invokes field's 'before_save_instance'.
 * Return a non-true value to halt attachment or saving operation
 *
 * @return boolean
 */
	public function beforeSave($options = array()) {
		if (isset($this->data['Field']['field_module'])) {
			if (isset($this->data['Field']['name'])) {
				// attaching new instance to entity
				$this->data['Field']['name'] = 'field_' . str_replace('field_', '', $this->data['Field']['name']);
			} elseif (isset($this->data['Field']['id']) && !empty($this->data['Field']['id'])) {
				// updating field settings
				if ($this->isFieldLocked($this->data['Field']['id'])) {
					$this->invalidate('label', __t('This field is locked and cannot be modified'));

					return false;
				}
			}

			$this->data['Field']['settings'] = @unserialize($this->data['Field']['settings']);

			if ($this->hookDefined("{$this->data['Field']['field_module']}_before_save_instance")) {
				$before = $this->hook("{$this->data['Field']['field_module']}_before_save_instance", $this);

				if ($before !== true) {
					return false;
				}
			}

			$this->data['Field']['settings'] = !is_array($this->data['Field']['settings']) ? array() : $this->data['Field']['settings'];
			$this->data['Field']['settings'] = @serialize($this->data['Field']['settings']);
		}

		// field formatter
		if (isset($this->data['Field']['display'])) {
			$display = $this->data['Field']['display'];
			$this->data['Field']['settings'] = @unserialize($this->data['Field']['settings']);

			if ($this->data['Field']['display_hidden']) {
				$this->data['Field']['settings']['display'][$display]['type'] = 'hidden';
			} else {
				if (!isset($this->data['Field']['settings']['display'][$display]['type'])) {
					$this->data['Field']['settings']['display'][$display]['type'] = false;
				}
			}

			$this->data['Field']['settings'] = @serialize($this->data['Field']['settings']);
		}

		return true;
	}

/**
 * After instance has been attached to entity, Nor after instance
 * settings has been updated.
 *
 * Invokes field's 'after_save_instance'.
 *
 * @return void
 */
	public function afterSave($created) {
		$field = $this->read();

		$this->hook("{$field['Field']['field_module']}_after_save_instance", $this);
	}

/**
 * Before instance is detached from entity.
 *
 * Invokes field's 'before_delete_instance'.
 * Return a non-true value to halt field detachment operation.
 *
 * @return boolean
 */
	public function beforeDelete($cascade = true) {
		$this->data = $this->read(); // tmp holder (before/afterDelete)

		if ($this->hookDefined("{$this->field['Field']['field_module']}_before_delete_instance")) {
			$before = $this->hook("{$this->field['Field']['field_module']}_before_delete_instance", $this, array('collectReturn' => false));

			if ($before !== true) {
				return false;
			}
		}

		return true;
	}

/**
 * After instance has been detached from entity.
 *
 * Invokes field's 'after_delete_instance'.
 *
 * @return void
 */
	public function afterDelete() {
		$this->hook("{$this->data['Field']['field_module']}_after_delete_instance", $this, array('collectReturn' => false));
	}

/**
 * Very that the given field name is unique in the group of
 * fields already attached to entity.
 * May exists same named fields but attached to different entities.
 *
 * @param array $check Value to check
 * @return boolean
 */
	public function checkUnique($check) {
		$value = array_shift($check);

		return $this->find('count',
			array(
				'conditions' => array(
					'Field.belongsTo' => $this->data['Field']['belongsTo'],
					'Field.name' => $value
				)
			)
		) === 0;
	}

	public function move($id, $dir = 'up', $display_mode = false) {
		if (!($record = $this->findById($id))) {
			return false;
		}

		$_data = array('id' => $id, 'dir' => $dir, 'display_mode' => $display_mode);

		$this->hook("{$record['Field']['field_module']}_before_move_instance", $_data);
		extract($_data);

		// get brothers
		$nodes = $this->find('all',
			array(
				'conditions' => array(
					'Field.belongsTo ' => $record['Field']['belongsTo']
				),
				'order' => array("Field.ordering" => 'ASC'),
				'fields' => array('id', 'ordering', 'settings', 'label'),
				'recursive' => -1
			)
		);

		if (is_string($display_mode)) {
			foreach ($nodes as &$node) {
				if (!isset($node['Field']['settings']['display'][$display_mode])) {
					$node['Field']['settings']['display'][$display_mode]['ordering'] = 0;
				}
			}

			$nodes = Hash::sort($nodes, "{n}.Field.settings.display.{$display_mode}.ordering", 'asc');
		}

		$ids = Hash::extract($nodes, '{n}.Field.id');

		if (($dir == 'down' && $ids[count($ids)-1] == $record['Field']['id']) ||
			($dir == 'up' && $ids[0] == $record['Field']['id'])
		) {
			// edge => cant go down/up
			return false;
		}

		$position = array_search($record['Field']['id'], $ids);
		$key = ($dir == 'up') ? $position-1 : $position+1;
		$tmp = $ids[$key];
		$ids[$key] = $ids[$position];
		$ids[$position] = $tmp;
		$i = 1;
		$prev_id = $this->id;

		foreach ($ids as $id) {
			$this->id = $id;

			if (is_string($display_mode)) {
				$node = Hash::extract($nodes, "{n}.Field[id={$id}]");

				if (isset($node[0]['settings']['display'][$display_mode])) {
					$node[0]['settings']['display'][$display_mode]['ordering'] = $i;

					$this->saveField('settings', $node[0]['settings'], false);
				}
			} else {
				$this->saveField('ordering', $i, false);
			}

			$i++;
		}

		$this->id = $prev_id;

		$this->hook("{$record['Field']['field_module']}_after_move_instance", $_data);

		return true;
	}

	public function setViewModes($modes, $conditions = false) {
		if (!is_array($modes) || empty($modes)) {
			$modes = array();
		}

		$conditions = (!$conditions || !is_array($conditions)) ? '1 = 1' : $conditions;
		$fields = $this->find('all', array('conditions' => $conditions));

		foreach ($fields as &$field) {
			$this->hook("{$field['Field']['field_module']}_before_set_display_modes", $field);

			$actual = array_keys($field['Field']['settings']['display']);

			foreach ($actual as $actual_mode) {
				// remove old modes
				if (!in_array($actual_mode, $modes) && $actual_mode !== 'default') {
					unset($field['Field']['settings']['display'][$actual_mode]);
				}
			}

			if (!empty($modes)) {
				foreach ($modes as $new_mode) {
					// add if not set yet
					if (!isset($field['Field']['settings']['display'][$new_mode])) {
						$field['Field']['settings']['display'][$new_mode] = array(
							'label' => 'hidden',
							'type' => '', // formatter name
							'settings' => array(),
							'ordering' => 0
						);
					}
				}
			}

			$this->save($field, false);
			$this->hook("{$field['Field']['field_module']}_after_set_display_modes", $field);
		}
	}

/**
 * Whether or not the field is available for editing.
 * If TRUE, users can't change field settings.
 *
 * @param integer $id Field instance ID
 * @return boolean TRUE if field is locked. FALSE otherwise
 */
	public function isFieldLocked($instance_id) {
		$c = $this->find('count',
			array(
				'conditions' => array(
					'Field.id' => $instance_id,
					'Field.locked' => 1
				),
				'recursive' => -1
			)
		);

		return $c > 0;
	}

/**
 * Lock the specified field, so users can't modify its settings.
 *
 * @param integer $instance_id Instance ID of the field to lock
 * @return boolean TRUE on success, FALSE on failure
 */
	public function lockField($instance_id) {
		return $this->updateAll(
			array('Field.locked' => 1),
			array('Field.id' => $instance_id)
		);
	}

/**
 * Lock the specified field, so users can't modify its settings.
 *
 * @param integer $instance_id Instance ID of the field to unlock
 * @return boolean TRUE on success, FALSE on failure
 */
	public function unlockField($instance_id) {
		return $this->updateAll(
			array('Field.locked' => 0),
			array('Field.id' => $instance_id)
		);
	}
}