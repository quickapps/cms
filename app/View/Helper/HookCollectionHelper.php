<?php
/**
 * Hooks collection is used as a registry for loaded hook helpers and handles dispatching
 * and loading hook methods.
 *
 * PHP version 5
 *
 * @package  QuickApps.View.Helper
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class HookCollectionHelper extends AppHelper {
    private $__view;
    private $__map = array();
    protected $_methods = array();
    protected $_hookObjects = array();

    public function beforeRender() {
        $this->__view = $this->_View;
        $this->__loadHooks();

        return true;
    }

/**
 * Load all hooks (and optionally hooktags) of specified Module.
 *
 * @param string $module Name of the module.
 * @return boolean TRUE on success. FALSE otherwise.
 */
    public function attachModuleHooks($module) {
        $Plugin = Inflector::camelize($module);

        if (!CakePlugin::loaded($Plugin) || isset($this->_hookObjects['Hooks'][$Plugin . 'Hook'])) {
            return false;
        }

        $folder = new Folder;
        $folder->path = CakePlugin::path($Plugin) . 'View' . DS . 'Helper' . DS;
        $file_pattern = '(.*)HookHelper\.php';
        $files = $folder->find($file_pattern);

        foreach ($files as $object) {
            $object = str_replace('Helper.php', '', $object);
            $this->{$object} = $this->_View->loadHelper("{$Plugin}.{$object}");

            if (!is_object($this->{$object})) {
                continue;
            }

            $methods = array();
            $_methods = QuickApps::get_this_class_methods($this->{$object});

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

                $className = str_replace("{$Plugin}.", '', $object);

                unset($this->_hookObjects[$object]);
                unset($this->__view->{$className});

                $found++;
            }
        }

        $this->_methods = array_keys($this->__map);

        return $found > 0;
    }

/**
 * Trigger a callback method on every HookHelper.
 * Plugin-Dot-Syntax is allowed.
 *
 * ### Example
 * {{{
 *  $this->hook('Block.blocks_list');
 * }}}
 *
 * The above will trigger the `blocks_list` callback for the `Block` module only.
 *
 * {{{
 *  $this->hook('block_list');
 * }}}
 *
 * The above will trigger the `block_list` callback on every Hook class.
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
        return $this->__dispatchHook($hook, $data, $options);
    }

/**
 * Chech if hook method exists.
 *
 * @param string $hook Name of the hook to check
 * @return boolean
 */
    public function hookDefined($hook) {
        return isset($this->__map[$hook]);
    }

/**
 * Turn on hook method if is turned off.
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
 * Dispatch Helper-hooks from all the plugins and core
 *
 * @see HookCollectionHelper::hook()
 * @return mixed Either the last result or all results if collectReturn is on. Or NULL in case of no response
 */
    private function __dispatchHook($hook, &$data = array(), $options = array()) {
        list($plugin, $hook) = pluginSplit($hook);

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

            if (is_callable(array($this->__controller->{$object}, $hook))) {
                $result = $this->__view->{$object}->$hook($data);

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

                    if (is_callable(array($this->__view->{$object}, $hook))) {
                        $result = $this->__view->{$object}->$hook($data);

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

    private function __loadHooks() {
        foreach ((array)Configure::read('Hook.helpers') as $helper) {
            $pluginSplit = pluginSplit($helper);
            $helper = $pluginSplit[1];

            if ($helper == 'HookCollection' || !is_object($this->__view->{$helper})) {
                continue;
            }

            if (strpos($helper, 'Hook') !== false) {
                if (!is_object($this->{$helper})) {
                    continue;
                }

                $methods = array();
                $_methods = QuickApps::get_this_class_methods($this->__view->{$helper});

                foreach ($_methods as $method) {
                    // ignore private and protected methods
                    if (strpos($method, '__') === 0 || strpos($method, '_') === 0) {
                        continue;
                    }

                    $methods[] = $method;

                    if (isset($this->__map[$method])) {
                        if (!in_array($helper, $this->__map[$method])) {
                            $this->__map[$method][] = (string)$helper;
                        }
                    } else {
                        $this->__map[$method] = array((string)$helper);
                    }
                }

                if ($pluginSplit[0]) {
                    $this->_hookObjects["{$pluginSplit[0]}.{$helper}"] = $methods;
                } else {
                    $this->_hookObjects[$helper] = $methods;
                }
            }
        }

        $this->_methods = array_keys($this->__map);
    }
}