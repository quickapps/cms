<?php
/**
 * Hooks collection is used as a registry for loaded hooks and handles dispatching
 * and loading hook methods.
 *
 * PHP version 5
 *
 * @package	 QuickApps.Plugin.System.Lib
 * @version	 1.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 */
class HookCollection {
/**
 * Type of hook classes to handle:
 *  - Behavior
 *  - Helper
 *  - Component
 *
 * @var string
 */
	private $__type;

/**
 * Instance of `Model`, `View` or `Controller`
 *
 * @var object
 */
	private $__object;

/**
 * Associative list of available hook methods and its hook classes.
 *
 *    ['hook_name'] => array('HookClass1', 'HookClass2', ...);
 *
 * @var array
 */
	private $__map = array();

/**
 * List of available hook methods.
 *
 * @var array
 */
	public $_methods = array();

/**
 * List of loaded hook classes.
 *
 * @var array
 */
	protected $_hookObjects = array();

/**
 * Initializes hook objects and methods.
 *
 * @param object $object Instance of `Model`, `View` or `Controller`
 * @param return void
 */
	public function __construct(&$object) {
		if ($object instanceof Model) {
			$this->__type = 'Behavior';
		} elseif ($object instanceof View) {
			$this->__type = 'Helper';
		} elseif ($object instanceof Controller) {
			$this->__type = 'Component';
		} else {
			throw new InvalidArgumentException(__t('$object must be an instance of Model, View or Controller'));
		}

		$this->__object = $object;

		$this->__loadHooks();
	}

/**
 * Load all hooks of specified module.
 *
 * @param string $module Name of the module.
 * @return boolean TRUE on success. FALSE otherwise.
 */
	public function attachModuleHooks($module) {
		$Plugin = Inflector::camelize($module);

		if (!CakePlugin::loaded($Plugin) || isset($this->_hookObjects[$Plugin . 'Hook'])) {
			return false;
		}

		switch ($this->__type) {
			case 'Behavior':
				$__folder = 'Model';
			break;

			case 'Helper':
				$__folder = 'View';
			break;

			case 'Component':
				$__folder = 'Controller';
			break;
		}

		$folder = new Folder;
		$folder->path = CakePlugin::path($Plugin) . $__folder . DS . $this->__type . DS;
		$files = $folder->find('(.*)Hook' . $this->__type . '\.php');

		foreach ($files as $object) {
			$object = str_replace("{$this->__type}.php", '', $object);
			$class = "{$object}{$this->__type}";

			$this->__loadHookClass("{$Plugin}.{$object}");

			if (!is_object($this->__getHookClass($object))) {
				continue;
			}

			$methods = array();
			$_methods = QuickApps::get_this_class_methods($this->__getHookClass($object));

			foreach ($_methods as $method) {
				$methods[] = $method;
				$this->__map[$method][] = (string)$object;
			}

			$this->_hookObjects["{$Plugin}.{$object}"] = $methods;
		}

		$this->_methods = array_keys($this->__map);

		return true;
	}

/**
 * Unload all hooks of specified module.
 *
 * @param string $module Name of the module
 * @return boolean TRUE on success. FALSE otherwise.
 */
	public function detachModuleHooks($module) {
		$Plugin = Inflector::camelize($module);
		$found = 0;

		foreach ($this->_hookObjects as $object => $hooks) {
			if (strpos($object, "{$Plugin}.") === 0) {
				foreach ($hooks as $hook) {
					unset($this->__map[$hook]);
				}

				unset($this->_hookObjects[$object]);
				$this->__unloadHookClass("{$Plugin}.{$object}");

				$found++;
			}
		}

		$this->_methods = array_keys($this->__map);

		return $found > 0;
	}

/**
 * Trigger a callback method on every Hook object.
 * Plugin-Dot-Syntax is allowed.
 *
 * ### Example
 *
 *    $this->hook('Block.blocks_list');
 *
 * The above will trigger the `blocks_list` callback for the `Block` module only.
 *
 *    $this->hook('block_list');
 *
 * The above will trigger the `block_list` callback on every Hook class.
 *
 * ### Options
 *
 *	-	`breakOn` Set to the value or values you want the callback propagation to stop on.
 *		Can either be a scalar value, or an array of values to break on.
 *		Defaults to `false`.
 *
 *	-	`break` Set to true to enabled breaking. When a trigger is broken, the last returned value
 *		will be returned.  If used in combination with `collectReturn` the collected results will be returned.
 *		Defaults to `false`.
 *
 *	-	`collectReturn` Set to true to collect the return of each object into an array.
 *		This array of return values will be returned from the hook() call. Defaults to `false`.
 *
 * @param string $hook Name of the hook to call.
 * @param mixed $data Data for the triggered callback.
 * @param array $option Array of options.
 * @return mixed Either the last result or all results if collectReturn is on. Or null in case of no response.
 */
	public function hook($hook, &$data = array(), $options = array()) {
		return $this->__dispatchHook($hook, $data, $options);
	}

/**
 * Chech if hook exists and is enabled.
 * Plugin-dot-syntax is allowed if you need to check if an specific module has defined 
 * certain hook method.
 *
 * Note that disabled hooks using `hookDisable()` won't be considered
 * and they will be detected as undefined/nonexistent by `hookDefined()`.
 *
 * ### Example
 *
 *     hookDefined('ModuleName.hook_to_check')
 *
 * The above will check if `ModuleName` module has defined the `hook_to_check` hook method.
 *
 * @param string $hook Name of the hook to check
 * @return boolean
 */
	public function hookDefined($hook) {
		list($plugin, $hook) = pluginSplit((string)$hook);
		$plugin = Inflector::camelize($plugin);
		$hook = Inflector::underscore($hook);

		if ($plugin) {
			return isset($this->__map[$hook]) && in_array($plugin, $this->__map[$hook]);
		} else {
			return isset($this->__map[$hook]);
		}
	}

/**
 * Turns on the hook method.
 *
 * @param string $hook Hook name to turn on.
 * @return boolean TRUE on success. FALSE hook does not exists or is already on.
 */
	public function hookEnable($hook) {
		$hook = Inflector::underscore($hook);

		if (isset($this->__map["{$hook}::Disabled"])) {
			$this->__map[$hook] = $this->__map["{$hook}::Disabled"];

			unset($this->__map["{$hook}::Disabled"]);

			if (in_array("{$hook}::Disabled", $this->_methods)) {
				$this->_methods[] = $hook;

				unset($this->_methods[array_search("{$hook}::Disabled", $this->_methods)]);
			}

			return true;
		}

		return false;
	}

/**
 * Turns off hook method.
 * Can be used to stop hook propagation.
 *
 * @param string $hook Hook name to turn off
 * @return boolean TRUE on success. FALSE hook does not exists
 */
	public function hookDisable($hook) {
		$hook = Inflector::underscore($hook);

		if (isset($this->__map[$hook])) {
			$this->__map["{$hook}::Disabled"] = $this->__map[$hook];

			unset($this->__map[$hook]);

			if (in_array($hook, $this->_methods)) {
				$this->_methods[] = "{$hook}::Disabled";

				unset($this->_methods[array_search("{$hook}", $this->_methods)]);
			}

			return true;
		}

		return false;
	}

/**
 * Load and attach hooks to AppController.
 *
 *  - Preload helpers hooks.
 *  - Preload behaviors hooks.
 *  - Preload components hooks.
 *
 * @param object $Controller Instance of AppController
 * @return void
 */
	public static function preloadHooks(Controller $Controller) {
		$_variable = Cache::read('Variable');
		$_modules = Cache::read('Modules');
		$_themeType = Router::getParam('admin') ? 'admin_theme' : 'site_theme';
		$hook_objects = Cache::read("hook_objects_{$_themeType}");

		if (!$hook_objects) {
			if (!$_variable) {
				$Controller->loadModel('System.Variable');

				$_variable = $Controller->Variable->find('first', array('conditions' => array('Variable.name' => $_themeType)));
				$_variable[$_themeType] = $_variable['Variable']['value'];

				ClassRegistry::flush();
				unset($Controller->Variable);
			}

			if (!$_modules) {
				$Controller->loadModel('System.Module');

				foreach ($Controller->Module->find('all', array('fields' => array('name'), 'conditions' => array('Module.status' => 1))) as $m) {
					$_modules[$m['Module']['name']] = array();
				}

				ClassRegistry::flush();
				unset($Controller->Module);
			}

			$paths = $c = $h = $ht = $b = array();
			$_modules = array_keys($_modules);
			$themeToUse = $_variable[$_themeType];
			$plugins = App::objects('plugin', null, false);
			$modulesCache = Cache::read('Modules');
			$theme_path = App::themePath($themeToUse);
			$load_order = Cache::read('modules_load_order');

			if ($load_order) {
				$load_order = array_intersect($load_order, $plugins);
				$tail = array_diff($plugins, $load_order);
				$plugins = array_merge($load_order, $tail);
			}

			foreach ($plugins as $plugin) {
				$ppath = CakePlugin::path($plugin);
				$isTheme = false;

				if (strpos($ppath, APP . 'View' . DS . 'Themed' . DS) !== false || strpos($ppath, THEMES) !== false) {
					$isTheme = true;
				}

				// inactive module. (except fields because they are not registered as plugin in DB)
				if (!in_array($plugin, $_modules) && !QuickApps::is('module.field', $plugin)) {
					continue;
				}

				// ignore disabled modules
				if (isset($modulesCache[$plugin]['status']) && $modulesCache[$plugin]['status'] == 0) {
					continue;
				}

				// disabled themes
				if ($isTheme && basename(dirname(dirname($ppath))) != $themeToUse) {
					continue;
				}

				$paths["{$plugin}_components"] = $ppath . 'Controller' . DS . 'Component' . DS;
				$paths["{$plugin}_behaviors"] = $ppath . 'Model' . DS . 'Behavior' . DS;
				$paths["{$plugin}_helpers"] = $ppath . 'View' . DS . 'Helper' . DS;
			}

			$paths = array_merge(
				array(
					APP . 'Controller' . DS . 'Component' . DS,	// core components
					APP . 'View' . DS . 'Helper' . DS,			// core helpers
					APP . 'Model' . DS . 'Behavior' . DS,		// core behaviors
					ROOT . DS . 'Hooks' . DS . 'Behavior' . DS,	// custom MH
					ROOT . DS . 'Hooks' . DS . 'Helper' . DS,	// custom VH
					ROOT . DS . 'Hooks' . DS . 'Component' . DS	// custom CH
				),
				(array)$paths
			);

			$folder = new Folder;

			foreach ($paths as $key => $path) {
				if (!file_exists($path)) {
					continue;
				}

				$folder->path = $path;
				$files = $folder->find('([a-zA-Z0-9]*)Hook(Component|Behavior|Helper|tagsHelper)\.php');
				$plugin = is_string($key) ? explode('_', $key) : false;
				$plugin = is_array($plugin) ? $plugin[0] : $plugin;

				foreach ($files as $file) {
					$prefix = ($plugin) ? Inflector::camelize($plugin) . '.' : '';
					$hook = $prefix . Inflector::camelize(str_replace(array('.php'), '', basename($file)));
					$hook = str_replace(array('Component', 'Behavior', 'Helper'), '', $hook);

					if (strpos($path, 'Helper')) {
						if (strpos($hook, 'Hooktags') !== false) {
							$ht[] = $hook;
						} else {
							$h[] = $hook;
						}

						$Controller->helpers[] = $hook;
					} elseif (strpos($path, 'Behavior')) {
						$b[] = $hook;
					} else {
						$c[] = $hook;
						$Controller->components[] = $hook;
					}
				}
			}

			Configure::write('Hook.components', $c);
			Configure::write('Hook.behaviors', $b);
			Configure::write('Hook.helpers', $h);
			Configure::write('Hook.hooktags', $ht);
			Cache::write("hook_objects_{$_themeType}", Configure::read('Hook'));
		} else {
			$Controller->helpers = array_merge($Controller->helpers, $hook_objects['helpers'], $hook_objects['hooktags']);
			$Controller->components = array_merge($Controller->components, $hook_objects['components']);

			Configure::write('Hook', $hook_objects);
		}
	}

/**
 * Dispatch hook.
 *
 * @return mixed Either the last result or all results if collectReturn is on. Or NULL in case of no response
 * @see HookCollection::hook()
 */
	private function __dispatchHook($hook, &$data = array(), $options = array()) {
		list($plugin, $hook) = pluginSplit((string)$hook);

		$plugin = Inflector::camelize($plugin);
		$hook = Inflector::underscore($hook);
		$collected = array();
		$result = null;
		$__options = array(
			'break' => false,
			'breakOn' => false,
			'collectReturn' => false
		);

		$options = array_merge($__options, $options);

		if (!$this->hookDefined($hook)) {
			return null;
		}

		if ($plugin &&
			!in_array("{$hook}::Disabled", $this->_methods) &&
			isset($this->_hookObjects["{$plugin}.{$plugin}Hook"]) &&
			in_array($hook, $this->_hookObjects["{$plugin}.{$plugin}Hook"])
		) {
			$object = "{$plugin}Hook";
			$hookClass = $this->__getHookClass($object);

			if (is_callable(array($HookClass, $hook))) {
				$result = $hookClass->$hook($data);

				return $result;
			} else {
				return null;
			}
		} else {
			if (isset($this->__map[$hook])) {
				foreach ($this->__map[$hook] as $object) {
					if (in_array("{$hook}::Disabled", $this->_methods)) {
						break;
					}

					$hookClass = $this->__getHookClass($object);

					if (is_callable(array($hookClass, $hook))) {
						$result = $hookClass->$hook($data);

						if ($options['collectReturn'] === true) {
							$collected[] = $result;
						}

						if ($options['break'] && ($result === $options['breakOn'] ||
							(is_array($options['breakOn']) && in_array($result, $options['breakOn'], true)))
						) {
							return $result;
						}
					}
				}
			}
		}

		if (empty($collected) && in_array($result, array('', null), true)) {
			return null;
		}

		return $options['collectReturn'] ? $collected : $result;
	}

/**
 * Loads the given hook class.
 *
 * @param string $class Hook class to load
 * @return void
 */
	private function __loadHookClass($class) {
		list($plugin, $class) = pluginSplit($class);

		switch ($this->__type) {
			case 'Behavior':
				$this->__object->Behaviors->load("{$plugin}.{$class}");
			break;

			case 'Helper':
				$this->__object->Helpers->load("{$plugin}.{$class}");
			break;

			case 'Component':
				$this->__object->Components->attach("{$plugin}.{$class}");
			break;
		}
	}

/**
 * Unloads the given hook class.
 *
 * @param string $class Hook class to unload
 * @return void
 */
	private function __unloadHookClass($class) {
		list($plugin, $object) = pluginSplit($class);

		switch ($this->__type) {
			case 'Behavior':
				$this->__object->Behaviors->unload("{$plugin}.{$object}");
			break;

			case 'Helper':
				if (isset($this->__object->{$object})) {
					unset($this->__object->{$object});
				}
			break;

			case 'Component':
				if (isset($this->__object->{$object})) {
					unset($this->__object->{$object});
				}
			break;
		}
	}

/**
 * Gets an instance of the given hook class.
 *
 * @param string $class Hook class to get
 * @return object Instance of hook object
 */
	private function __getHookClass($class) {
		list($plugin, $object) = pluginSplit($class);

		switch ($this->__type) {
			case 'Behavior':
				return $this->__object->Behaviors->{$object};
			break;

			case 'Helper':
				return $this->__object->{$object};
			break;

			case 'Component':
				return $this->__object->{$object};
			break;
		}
	}

/**
 * Loads and analyzes all hook objects preloaded using HookCollection::preloadHook().
 *
 * @return void
 */
	private function __loadHooks() {
		if (!empty($this->__map)) {
			return;
		}

		foreach ((array)Configure::read('Hook.' . strtolower("{$this->__type}s")) as $hookClass) {
			list($plugin, $hookClass) = pluginSplit($hookClass);

			if ($hookClass == 'HookCollection' || !is_object($this->__getHookClass($hookClass))) {
				continue;
			}

			if (preg_match('/Hook$/', $hookClass)) {
				$methods = array();
				$_methods = QuickApps::get_this_class_methods($this->__getHookClass($hookClass));

				foreach ($_methods as $method) {
					$methods[] = $method;

					if (isset($this->__map[$method])) {
						if (!in_array($hookClass, $this->__map[$method])) {
							$this->__map[$method][] = (string)$hookClass;
						}
					} else {
						$this->__map[$method] = array((string)$hookClass);
					}
				}

				if ($plugin) {
					$this->_hookObjects["{$plugin}.{$hookClass}"] = $methods;
				} else {
					$this->_hookObjects[$hookClass] = $methods;
				}
			}
		}

		$this->_methods = array_keys($this->__map);

		return true;
	}
}