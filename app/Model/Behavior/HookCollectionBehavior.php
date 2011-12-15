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
    public $Options = array(
        'break' => false,
        'breakOn' => false,
        'collectReturn' => false
    );
    private $__Options = array(
        'break' => false,
        'breakOn' => false,
        'collectReturn' => false
    );

    public function setup(&$Model, $settings = array()) {
        $this->__model = $Model;
        return $this->__loadHooks();
    }

/**
 * Chech if hook exists
 *
 * @param string $hook Name of the hook to check
 * @return bool
 */
    public function hookDefined($hook) {
        return (isset($this->__map[$hook]));
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
        $hook = Inflector::underscore($hook);

        return $this->__dispatchHook($hook, $data, $options);
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

            if (isset($this->_methods["{$hook}::Disabled"])) {
                $this->_methods[] = $hook;

                unset($this->_methods["{$hook}::Disabled"]);
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

            if (isset($this->_methods[$hook])) {
                $this->_methods[] = "{$hook}::Disabled";

                unset($this->_methods["{$hook}"]);
            }

            return true;
        }

        return false;
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
        $this->Options = Set::merge($this->Options, $options);
    }

/**
 * Dispatch Component-hooks from all the plugins and core
 *
 * @see AppModel::hook()
 * @return mixed Either the last result or all results if collectReturn is on. Or NULL in case of no response
 */
    private function __dispatchHook($hook, &$data = array(), $options = array()) {
        $options = array_merge($this->Options, (array)$options);
        $collected = array();
        $result = null;

        if (!$this->hookDefined($hook)) {
            $this->__resetOptions();

            return null;
        } else {
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
        }

        if (empty($collected) && in_array($result, array('', null), true)) {
            $this->__resetOptions();

            return null;
        }

        $this->__resetOptions();

        return $options['collectReturn'] ? $collected : $result;
    }

    private function __resetOptions() {
        if ($this->Options !== $this->__Options) {
            $this->Options = $this->__Options;
        }
    }

    private function __loadHooks() {
        foreach (Configure::read('Hook.behaviors') as $behavior) {
            $pluginSplit = pluginSplit($behavior);
            $behavior = strpos($behavior, '.') !== false ? substr($behavior, strpos($behavior, '.')+1) : $behavior;

            if ($behavior == 'HookCollection' || !is_object($this->__model->Behaviors->{$behavior})) {
                continue;
            }

            if (strpos($behavior, 'Hook')) {
                $methods = array();
                $_methods = get_this_class_methods($this->__model->Behaviors->{$behavior});

                foreach ($_methods as $method) {
                    $methods[] = $method;

                    if (isset($this->__map[$method])) {
                        $this->__map[$method][] = (string)$behavior;
                    } else {
                        $this->__map[$method] = array((string)$behavior);
                    }
                }

                if ($pluginSplit[0]) {
                    $this->_hookObjects["{$pluginSplit[0]}.{$behavior}"] = $methods;
                } else {
                    $this->_hookObjects[$helper] = $methods;
                }
            }
        }

        $this->_methods = array_keys($this->__map);

        return true;    
    }
}