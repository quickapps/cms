<?php
/**
 * Hooks collection is used as a registry for loaded hook helpers and handles dispatching
 * and loading hook methods.
 *
 * PHP version 5
 *
 * @package	 QuickApps.View.Helper
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class HookCollectionHelper extends AppHelper {
/**
 * Instance of HookCollection class.
 *
 * @var HookCollection
 */
	public $HookCollection;

	public function beforeRender($viewFile) {
		$this->HookCollection = new HookCollection($this->_View);

		return true;
	}

/**
 * Load all hooks of specified Module.
 *
 * @param string $module Name of the module.
 * @return boolean TRUE on success. FALSE otherwise.
 */
	public function attachModuleHooks($module) {
		return $this->HookCollection->attachModuleHooks($module);
	}

/**
 * Unload all hooks of specified Module.
 *
 * @param string $module Name of the module
 * @return boolean TRUE on success. FALSE otherwise.
 */
	public function detachModuleHooks($module) {
		return $this->HookCollection->detachModuleHooks($module);
	}

/**
 * Trigger a callback method on every HookHelper.
 * Plugin-Dot-Syntax is allowed.
 *
 * ### Example
 *
 *     $this->hook('Block.blocks_list');
 *
 * The above will trigger the `blocks_list` callback for the `Block` module only.
 *
 *     $this->hook('block_list');
 *
 * The above will trigger the `block_list` callback on every Hook class.
 *
 * ### Options
 *
 * -	`breakOn` Set to the value or values you want the callback propagation to stop on.
 *		Can either be a scalar value, or an array of values to break on.
 *		Defaults to `false`.
 *
 * -	`break` Set to true to enabled breaking. When a trigger is broken, the last returned value
 *		will be returned.  If used in combination with `collectReturn` the collected results will be returned.
 *		Defaults to `false`.
 *
 * -	`collectReturn` Set to true to collect the return of each object into an array.
 *		This array of return values will be returned from the hook() call. Defaults to `false`.
 *
 * @param string $hook Name of the hook to call.
 * @param mixed $data Data for the triggered callback.
 * @param array $option Array of options.
 * @return mixed Either the last result or all results if collectReturn is on. Or null in case of no response.
 */
	public function hook($hook, &$data = array(), $options = array()) {
		return $this->HookCollection->hook($hook, $data, $options);
	}

/**
 * Chech if hook method exists.
 *
 * @param string $hook Name of the hook to check
 * @return boolean
 */
	public function hookDefined($hook) {
		return $this->HookCollection->hookDefined($hook);
	}

/**
 * Turn on hook method if is turned off.
 *
 * @param string $hook Hook name to turn on.
 * @return boolean TRUE on success. FALSE hook does not exists or is already on.
 */
	public function hookEnable($hook) {
		return $this->HookCollection->hookEnable($hook);
	}

/**
 * Turns off hook method.
 *
 * @param string $hook Hook name to turn off.
 * @return boolean TRUE on success. FALSE hook does not exists.
 */
	public function hookDisable($hook) {
		return $this->HookCollection->hookDisable($hook);
	}
}