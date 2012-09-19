<?php
/**
 * Hooks collection is used as a registry for loaded hook components and handles dispatching
 * and loading hook methods.
 *
 * PHP version 5
 *
 * @package	 QuickApps.Controller.Component
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class HookCollectionComponent extends Component {
/**
 * Instance of HookCollection class.
 *
 * @var HookCollection
 */
	public $HookCollection;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param object $controller Controller with components to initialize
 * @return void
 */
	public function initialize(Controller $Controller) {
		$this->HookCollection = new HookCollection($Controller);
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
 * Trigger a callback method on every HookComponent.
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