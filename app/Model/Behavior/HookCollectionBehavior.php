<?php
/**
 * Hooks collection is used as a registry for loaded hook behaviors and handles dispatching
 * and loading hook methods.
 *
 * PHP version 5
 *
 * @package  QuickApps.Model.Behavior
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class HookCollectionBehavior extends ModelBehavior {
    private $__model;
    private $__map = array();
    public $_methods = array();
    public $_hookObjects = array();

    public function setup($Model, $settings = array()) {
        $this->__model = $Model;
        $this->__loadHooks();
    }

/**
 * Load all hooks of specified Module.
 *
 * @param string $module Name of the module.
 * @return boolean TRUE on success. FALSE otherwise.
 */
    public function attachModuleHooks($module) {
        $Plugin = Inflector::camelize($module);

        if (!CakePlugin::loaded($Plugin) || isset($this->_hookObjects[$Plugin . 'Hook'])) {
            return false;
        }

        $folder = new Folder;
        $folder->path = CakePlugin::path($Plugin) . 'Model' . DS . 'Behavior' . DS;
        $files = $folder->find('(.*)HookBehavior\.php');

        foreach ($files as $object) {
            $object = str_replace('Behavior.php', '', $object);
            $class = "{$object}Behavior";

            include_once $folder->path . $class . '.php';

            $this->__model->Behaviors->load("{$Plugin}.{$object}");

            if (!is_object($this->__model->Behaviors->{$object})) {
                continue;
            }

            $methods = array();
            $_methods = QuickApps::get_this_class_methods($this->__model->Behaviors->{$object});

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
 * Unload all hooks of specified Module.
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
                $this->__model->Behaviors->unload("{$Plugin}.{$object}");

                $found++;
            }
        }

        $this->_methods = array_keys($this->__map);

        return $found > 0;
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
 * @param string $hook Name of the hook to call.
 * @param mixed $data Data for the triggered callback.
 * @param array $option Array of options.
 * @return mixed Either the last result or all results if collectReturn is on. Or null in case of no response.
 */
    public function hook($hook, &$data = array(), $options = array()) {
        $hook = Inflector::underscore($hook);

        return $this->__dispatchHook($hook, $data, $options);
    }

/**
 * Chech if hook exists
 *
 * @param string $hook Name of the hook to check
 * @return bool
 */
    public function hookDefined($hook) {
        return isset($this->__map[$hook]);
    }

/**
 * Turns on the hook method if it's turned off.
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
 *
 * @param string $hook Hook name to turn off.
 * @return boolean TRUE on success. FALSE hook does not exists.
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
 * Dispatch Behavior-hooks from all the plugins and core
 *
 * @see HookCollectionBehavior::hook()
 * @return mixed Either the last result or all results if collectReturn is on. Or NULL in case of no response
 */
    private function __dispatchHook($hook, &$data = array(), $options = array()) {
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

        foreach ($this->__map[$hook] as $object) {
            if (in_array("{$hook}::Disabled", $this->_methods)) {
                break;
            }

            if (is_callable(array($this->__model->Behaviors->{$object}, $hook))) {
                $result = $this->__model->Behaviors->{$object}->$hook($data);

                if ($options['collectReturn'] === true) {
                    $collected[] = $result;
                }

                if ($options['break'] && ($result === $options['breakOn'] ||
                    (is_array($options['breakOn']) && in_array($result, $options['breakOn'], true)))
                ) {
                    $this->__resetOptions();

                    return $result;
                }
            }
        }

        if (empty($collected) && in_array($result, array('', null), true)) {
            return null;
        }

        return $options['collectReturn'] ? $collected : $result;
    }

    private function __loadHooks() {
        if (!empty($this->__map)) {
            return;
        }

        foreach ((array)Configure::read('Hook.behaviors') as $behavior) {
            $pluginSplit = pluginSplit($behavior);
            $behavior = $pluginSplit[1];

            if ($behavior == 'HookCollection' || !is_object($this->__model->Behaviors->{$behavior})) {
                continue;
            }

            if (strpos($behavior, 'Hook')) {
                $methods = array();
                $_methods = QuickApps::get_this_class_methods($this->__model->Behaviors->{$behavior});

                foreach ($_methods as $method) {
                    $methods[] = $method;

                    if (isset($this->__map[$method])) {
                        if (!in_array($behavior, $this->__map[$method])) {
                            $this->__map[$method][] = (string)$behavior;
                        }
                    } else {
                        $this->__map[$method] = array((string)$behavior);
                    }
                }

                if ($pluginSplit[0]) {
                    $this->_hookObjects["{$pluginSplit[0]}.{$behavior}"] = $methods;
                } else {
                    $this->_hookObjects[$behavior] = $methods;
                }
            }
        }

        $this->_methods = array_keys($this->__map);

        return true;
    }
}