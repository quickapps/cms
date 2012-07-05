<?php
App::uses('Model', 'Model');

/**
 * Application Model
 *
 * PHP version 5
 *
 * @package	 QuickApps.Model
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
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
 * Wrapper method to HookCollectionBehavior::detachModuleHooks()
 *
 * @see HookCollectionBehavior::detachModuleHooks()
 */
	public function detachModuleHooks($module) {
		return $this->Behaviors->HookCollection->detachModuleHooks($module);
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
 *	be returned. If no validation key is provided, defaults to true.
 * @return void
 */
	public function invalidate($field, $value = true) {
		$value = is_string($value) ? __t($value) : $value;

		if (!is_array($this->validationErrors)) {
			$this->validationErrors = array();
		}

		// QuickApps Mod.
		$this->validationErrors = Hash::insert($this->validationErrors, $field, $value);
	}

	private function __loadHookObjects() {
		$b = Configure::read('Hook.behaviors');

		if (!$b) {
			return false; // fix for HookCollection::preloadHooks()
		}

		foreach ($b as $hook) {
			$this->actsAs[$hook] = array();
		}

		$this->actsAs['HookCollection'] = array();
	}
}