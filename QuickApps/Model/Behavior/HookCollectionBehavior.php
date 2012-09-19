<?php
/**
 * Hooks collection is used as a registry for loaded hook behaviors and handles dispatching
 * and loading hook methods.
 *
 * PHP version 5
 *
 * @package	 QuickApps.Model.Behavior
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class HookCollectionBehavior extends ModelBehavior {
/**
 * Instance of HookCollection class.
 *
 * @var HookCollection
 */
	public $HookCollection;

	public function setup(Model $Model, $settings = array()) {
		if (!Configure::read('__HookCollectionBehavior')) {
			$this->HookCollection = new HookCollection($Model);

			Configure::write('__HookCollectionBehavior', $this->HookCollection);
		} else {
			$this->HookCollection = Configure::read('__HookCollectionBehavior');
		}
	}

/**
 * Load all hooks of specified Module.
 *
 * @see HookCollection::attachModuleHooks()
 */
	public function attachModuleHooks($module) {
		return $this->HookCollection->attachModuleHooks($module);
	}

/**
 * Unload all hooks of specified Module.
 *
 * @see HookCollection::detachModuleHooks()
 */
	public function detachModuleHooks($module) {
		return $this->HookCollection->detachModuleHooks($module);
	}

/**
 * Trigger a callback method on every HookBehavior.
 * Plugin-Dot-Syntax is allowed.
 *
 * @see HookCollection::hook()
 */
	public function hook($hook, &$data = array(), $options = array()) {
		return $this->HookCollection->hook($hook, $data, $options);
	}

/**
 * Chech if hook method exists.
 *
 * @see HookCollection::hookDefined()
 */
	public function hookDefined($hook) {
		return $this->HookCollection->hookDefined($hook);
	}

/**
 * Turn on hook method if is turned off.
 *
 * @see HookCollection::hookEnable()
 */
	public function hookEnable($hook) {
		return $this->HookCollection->hookEnable($hook);
	}

/**
 * Turns off hook method.
 *
 * @see HookCollection::hookDisable()
 */
	public function hookDisable($hook) {
		return $this->HookCollection->hookDisable($hook);
	}
}