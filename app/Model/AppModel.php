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

    private function __loadHookObjects() {
        $b = Configure::read('Hook.behaviors');

        if (!$b){
            return false; // fix for AppController __preloadHooks()
        }

        foreach ($b as $hook) {
            $this->actsAs[$hook] = array();
        }

        $this->actsAs['HookCollection'] = array();
    }

/**
 * Chech if hook exists
 *
 * @param string $hook Name of the hook to check
 * @return bool
 */
    public function hookDefined($hook) {
        return $this->Behaviors->HookCollection->hookDefined($hook);
    }

/**
 * Trigger a callback method on every HookBehavior.
 *
 * ### Options
 *
 * - `breakOn` Set to the value or values you want the callback propagation to stop on.
 *    Can either be a scalar value, or an array of values to break on.
 *    Defaults to `false`.
 *
 * - `break` Set to true to enabled breaking. When a trigger is broken, the last returned value
 *    will be returned.  If used in combination with `collectReturn` the collected results will be returned.
 *    Defaults to `false`.
 *
 * - `collectReturn` Set to true to collect the return of each object into an array.
 *    This array of return values will be returned from the hook() call. Defaults to `false`.
 *
 * - `alter` Allows each callback gets called on to modify the parameters to the next object.
 *    Defaults to true.
 *
 * @param string $event name of the hook to call
 * @param mixed $data data for the triggered callback
 * @param array $option Array of options
 * @return mixed Either the last result or all results if collectReturn is on. Or null in case of no response
 */
    public function hook($hook, &$data = array(), $options = array()) {
        return $this->Behaviors->HookCollection->hook($hook, $data, $options);
    }

/**
 * Turns on the hook method if it's turned off.
 *
 * @param string $hook Hook name to turn on.
 * @return boolean TRUE on success. FALSE hook does not exists or is already on.
 */
    public function hookEnable($hook) {
        return $this->Behaviors->HookCollection->hookEnable($hook);
    }

/**
 * Turns off hook method.
 *
 * @param string $hook Hook name to turn off.
 * @return boolean TRUE on success. FALSE hook does not exists.
 */ 
    public function hookDisable($hook) {
        return $this->Behaviors->HookCollection->hookDisable($hook);
    }

/**
 * Overwrite default options for Hook dispatcher.
 * Useful when calling a hook with non-parameter and custom options.
 *
 * Watch out!: Hook dispatcher automatic reset its default options to
 * the original ones after `hook()` is invoked.
 * Means that if you need to call more than one hook (consecutive) with no parameters and
 * same options ** you must call `setHookOptions()` after each hook() **
 *
 * ### Usage
 * For example in any controller action:
 * {{{
 *  $this->setHookOptions(array('collectReturn' => false));
 *  $response = $this->hook('collect_hook_with_no_parameters');
 *
 *  $this->setHookOptions(array('collectReturn' => false, 'break' => true, 'breakOn' => false));
 *  $response2 = $this->hook('other_collect_hook_with_no_parameters');
 * }}}
 *
 * @param array $options Array of options to overwrite
 * @return void
 */
    public function setHookOptions($options) {
        return $this->Behaviors->HookCollection->setHookOptions($options); 
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
}