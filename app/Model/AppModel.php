<?php
/**
 * Application Model
 *
 * PHP version 5
 *
 * @package  QuickApps.Model
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class AppModel extends Model {
    public $cacheQueries = false;
    public $actsAs = array(
        'WhoDidIt' => array(
            'auth_session' => 'Auth.User.id',
            'user_model' => 'User.User'
        )
    );

    public function __construct($id = false, $table = null, $ds = null) {
        $this->__loadHookObjects();
        parent::__construct($id, $table, $ds);
    }

/**
 * Wrapper method to HookCollectionBehavior::attachModuleHooks()
 *
 * @see HookCollectionBehavior::attachModuleHooks()
 */
    public function attachModuleHooks($module) {
        return $this->Behaviors->HookCollection->attachModuleHooks($module);
    }

/**
 * Wrapper method to HookCollectionBehavior::deattachModuleHooks()
 *
 * @see HookCollectionBehavior::deattachModuleHooks()
 */
    public function deattachModuleHooks($module) {
        return $this->Behaviors->HookCollection->deattachModuleHooks($module);
    }

/**
 * Wrapper method to HookCollectionBehavior::hook()
 *
 * @see HookCollectionBehavior::hook()
 */
    public function hook($hook, &$data = array(), $options = array()) {
        return $this->Behaviors->HookCollection->hook($hook, $data, $options);
    }

/**
 * Wrapper method to HookCollectionBehavior::hookDefined()
 *
 * @see HookCollectionBehavior::hookDefined()
 */
    public function hookDefined($hook) {
        return $this->Behaviors->HookCollection->hookDefined($hook);
    }

/**
 * Wrapper method to HookCollectionBehavior::hookEnable()
 *
 * @see HookCollectionBehavior::hookEnable()
 */
    public function hookEnable($hook) {
        return $this->Behaviors->HookCollection->hookEnable($hook);
    }

/**
 * Wrapper method to HookCollectionBehavior::hookDisable()
 *
 * @see HookCollectionBehavior::hookDisable()
 */
    public function hookDisable($hook) {
        return $this->Behaviors->HookCollection->hookDisable($hook);
    }

/**
 * Marks a field as invalid, optionally setting the name of validation
 * rule (in case of multiple validation for field) that was broken.
 *
 * @param string $field The name of the field to invalidate
 * @param mixed $value Name of validation rule that was not failed, or validation message to
 *    be returned. If no validation key is provided, defaults to true.
 * @return void
 */
	public function invalidate($field, $value = true) {
        $value = is_string($value) ? __t($value) : $value;

		if (!is_array($this->validationErrors)) {
			$this->validationErrors = array();
		}
		$this->validationErrors = Set::insert($this->validationErrors, $field, $value); # QUICKAPPS MOD
	}

/**
 * Saves model hasAndBelongsToMany data to the database.
 *
 * @param array $joined Data to save
 * @param mixed $id ID of record in this model
 * @param DataSource $db
 * @return void
 */
	protected function _saveMulti($joined, $id, $db) {
		foreach ($joined as $assoc => $data) {

			if (isset($this->hasAndBelongsToMany[$assoc])) {
				list($join) = $this->joinModel($this->hasAndBelongsToMany[$assoc]['with']);

				$keyInfo = $this->{$join}->schema($this->{$join}->primaryKey);
				$isUUID = !empty($this->{$join}->primaryKey) && (
						$keyInfo['length'] == 36 && (
						$keyInfo['type'] === 'string' ||
						$keyInfo['type'] === 'binary'
					)
				);

				$newData = $newValues = array();
				$primaryAdded = false;

				$fields =  array(
					$db->name($this->hasAndBelongsToMany[$assoc]['foreignKey']),
					$db->name($this->hasAndBelongsToMany[$assoc]['associationForeignKey'])
				);

				$idField = $db->name($this->{$join}->primaryKey);
				if ($isUUID && !in_array($idField, $fields)) {
					$fields[] = $idField;
					$primaryAdded = true;
				}

				foreach ((array)$data as $row) {
					if (!empty($row) && (is_string($row) /*&& (strlen($row) == 36 || strlen($row) == 16)*/) || is_numeric($row)) { # QUICKAPPS MOD
						$values = array($id, $row);
						if ($isUUID && $primaryAdded) {
							$values[] = String::uuid();
						}
						$newValues[] = $values;
						unset($values);
					} elseif (isset($row[$this->hasAndBelongsToMany[$assoc]['associationForeignKey']])) {
						$newData[] = $row;
					} elseif (isset($row[$join]) && isset($row[$join][$this->hasAndBelongsToMany[$assoc]['associationForeignKey']])) {
						$newData[] = $row[$join];
					}
				}

				if ($this->hasAndBelongsToMany[$assoc]['unique']) {
					$conditions = array(
						$join . '.' . $this->hasAndBelongsToMany[$assoc]['foreignKey'] => $id
					);
					if (!empty($this->hasAndBelongsToMany[$assoc]['conditions'])) {
						$conditions = array_merge($conditions, (array)$this->hasAndBelongsToMany[$assoc]['conditions']);
					}
					$links = $this->{$join}->find('all', array(
						'conditions' => $conditions,
						'recursive' => empty($this->hasAndBelongsToMany[$assoc]['conditions']) ? -1 : 0,
						'fields' => $this->hasAndBelongsToMany[$assoc]['associationForeignKey']
					));

					$associationForeignKey = "{$join}." . $this->hasAndBelongsToMany[$assoc]['associationForeignKey'];
					$oldLinks = Set::extract($links, "{n}.{$associationForeignKey}");
					if (!empty($oldLinks)) {
 						$conditions[$associationForeignKey] = $oldLinks;
						$db->delete($this->{$join}, $conditions);
					}
				}

				if (!empty($newData)) {
					foreach ($newData as $data) {
						$data[$this->hasAndBelongsToMany[$assoc]['foreignKey']] = $id;
						$this->{$join}->create($data);
						$this->{$join}->save();
					}
				}

				if (!empty($newValues)) {
					$db->insertMulti($this->{$join}, $fields, $newValues);
				}
			}
		}
	}

    private function __loadHookObjects() {
        $b = Configure::read('Hook.behaviors');

        if (!$b) {
            return false; // fix for AppController __preloadHooks()
        }

        foreach ($b as $hook) {
            $this->actsAs[$hook] = array();
        }

        $this->actsAs['HookCollection'] = array();
    }
}