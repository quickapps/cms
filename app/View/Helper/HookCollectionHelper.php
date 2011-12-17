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

    private $__map = array(
        'Hooks' => array(),
        'Hooktags' => array(),
    );

    protected $_methods = array(
        'Hooks' => array(),
        'Hooktags' => array()
    );

    protected $_hookObjects = array(
        'Hooks' => array(),
        'Hooktags' => array()
    );

    public $helpers = array(
        'Layout',
        'Menu',
        'Form' => array('className' => 'QaForm'),
        'Html' => array('className' => 'QaHtml'),
        'Session',
        'Js'
    );

    public function beforeRender() {
        $this->__view = $this->_View;
        $this->__loadHooks();
        return true;
    }

/**
 * Load all hooks (and optionally hooktags) of specified Module.
 *
 * @param string $module Name of the module.
 * @param boolean $hooktags TRUE load hooktags. FALSE do not load.
 * @return boolean TRUE on success. FALSE otherwise.
 */
    public function attachModuleHooks($module, $hooktags = true) {
        $Plugin = Inflector::camelize($module);

        if (!CakePlugin::loaded($Plugin) || isset($this->_hookObjects['Hooks'][$Plugin . 'Hook'])) {
            return false;
        }

        $folder = new Folder;
        $folder->path = CakePlugin::path($Plugin) . 'View' . DS . 'Helper' . DS;
        $file_pattern = $hooktags ? '(.*)Hook(tagsHelper|Helper)\.php' : '(.*)HookHelper\.php';
        $files = $folder->find($file_pattern);

        foreach ($files as $object) {
            $object = str_replace('Helper.php', '', $object);
            $this->{$object} = $this->_View->loadHelper("{$Plugin}.{$object}" , array('plugin' => $Plugin));
            $mapGroup = strpos($object, 'Hooktags') !== false ? 'Hooktags' : 'Hooks';

            if (!is_object($this->{$object})) {
                continue;
            }

            $methods = array();
            $_methods = get_this_class_methods($this->{$object});

            foreach ($_methods as $method) {
                $methods[] = $method;
                $this->__map[$mapGroup][$method][] = (string)$object;
            }

            $this->_hookObjects[$mapGroup]["{$Plugin}.{$object}"] = $methods;
        }

        $this->_methods['Hooks'] = array_keys($this->__map['Hooks']);
        $this->_methods['Hooktags'] = array_keys($this->__map['Hooktags']);
        $_tmp = $this->_View->Layout->_tmp;
        $_tmp['__hooktags_reg'] = implode('|', $this->hooktagsList());
        $this->_View->Layout->_tmp = $_tmp;

        return true;
    }

/**
 * Unload all hooks & hooktags of specified Module.
 *
 * @param string $module Name of the module
 * @return boolean TRUE on success. FALSE otherwise.
 */
    public function deattachModuleHooks($module) {
        $Plugin = Inflector::camelize($module);
        $found = 0;

        foreach (array('Hooks', 'Hooktags') as $group) {
            foreach ($this->_hookObjects[$group] as $object => $hooks) {
                if (strpos($object, "{$Plugin}.") === 0) {
                    foreach ($hooks as $hook) {
                        unset($this->__map[$group][$hook]);
                    }

                    $className = str_replace("{$Plugin}.", '', $object);

                    unset($this->_hookObjects[$group][$object]);
                    unset($this->__view->{$className});

                    $found++;
                }
            }
        }

        $this->_methods['Hooks'] = array_keys($this->__map['Hooks']);
        $this->_methods['Hooktags'] = array_keys($this->__map['Hooktags']);
        $_tmp = $this->_View->Layout->_tmp;
        $_tmp['__hooktags_reg'] = implode('|', $this->hooktagsList());
        $this->_View->Layout->_tmp = $_tmp;
        
        return $found > 0;
    }

/**
 * Trigger a callback method on every HookHelper.
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
 * Chech if hook method exists.
 *
 * @param string $hook Name of the hook to check
 * @return boolean
 */
    public function hookDefined($hook) {
        return isset($this->__map['Hooks'][$hook]);
    }

/**
 * Chech if hooktag method exists.
 *
 * @param string $hooktag Name of the hooktag method to check
 * @return boolean
 */
    public function hooktagDefined($hooktag) {
        return isset($this->__map['Hooktags'][$hooktag]);
    }

/**
 * Turn on hook method if is turned off.
 *
 * @param string $hook Hook name to turn on.
 * @return boolean TRUE on success. FALSE hook does not exists or is already on.
 */
    public function hookEnable($hook) {
        $hook = Inflector::underscore($hook);

        if (isset($this->__map['Hooks']["{$hook}::Disabled"])) {
            $this->__map['Hooks'][$hook] = $this->__map['Hooks']["{$hook}::Disabled"];

            unset($this->__map['Hooks']["{$hook}::Disabled"]);

            if (in_array("{$hook}::Disabled", $this->_methods['Hooks'])) {
                $this->_methods['Hooks'][] = $hook;

                unset($this->_methods['Hooks'][array_search("{$hook}::Disabled", $this->_methods['Hooks'])]);
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

        if (isset($this->__map['Hooks'][$hook])) {
            $this->__map['Hooks']["{$hook}::Disabled"] = $this->__map['Hooks'][$hook];

            unset($this->__map['Hooks'][$hook]);

            if (in_array($hook, $this->_methods['Hooks'])) {
                $this->_methods['Hooks'][] = "{$hook}::Disabled";

                unset($this->_methods['Hooks'][array_search("{$hook}", $this->_methods['Hooks'])]);
            }

            return true;
        }

        return false;
    }

/**
 * Return an array list of all registered hooktag methods.
 *
 * @return array Array list of all available hooktag methods.
 */ 
    public function hooktagsList() {
        return $this->_methods['Hooktags'];
    }

/**
 * Callback function
 *
 * @see Layout::hooktags()
 * @return mixed Hook response or false in case of no response.
 */
    public function doHooktag($m) {
        // allow [[foo]] syntax for escaping a tag
        if ($m[1] == '[' && $m[6] == ']') {
            return substr($m[0], 1, -1);
        }

        $tag = $m[2];
        $attr = $this->__hooktagParseAtts($m[3]);
        $hook = isset($this->__map['Hooktags'][$tag]) ? $this->__map['Hooktags'][$tag] : false;

        if ($hook) {
            foreach ($this->__map['Hooktags'][$tag] as $object) {
                $hook =& $this->{$object};

                if (isset($m[5])) {
                    // enclosing tag - extra parameter
                    return $m[1] . call_user_func(array($hook, $tag), $attr, $m[5], $tag) . $m[6];
                } else {
                    // self-closing tag
                    return $m[1] . call_user_func(array($hook, $tag), $attr, null, $tag) . $m[6];
                }
            }
        }

        return false;
    }

/**
 * Dispatch Helper-hooks from all the plugins and core
 *
 * @see AppHelper::hook()
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

        if (isset($this->__map['Hooks'][$hook])) {
            foreach ($this->__map['Hooks'][$hook] as $object) {
                if (in_array("{$hook}::Disabled", $this->_methods['Hooks'])) {
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
                        $this->__resetOptions();

                        return $result;
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
 * Parse hooktags attributes
 *
 * @param string $text Tag string to parse
 * @return array Array of attributes
 */
    private function __hooktagParseAtts($text) {
        $atts = array();
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);

        if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER)) {
            foreach ($match as $m) {
                if (!empty($m[1])) {
                    $atts[strtolower($m[1])] = stripcslashes($m[2]);
                } elseif (!empty($m[3])) {
                    $atts[strtolower($m[3])] = stripcslashes($m[4]);
                } elseif (!empty($m[5])) {
                    $atts[strtolower($m[5])] = stripcslashes($m[6]);
                } elseif (isset($m[7]) and strlen($m[7])) {
                    $atts[] = stripcslashes($m[7]);
                } elseif (isset($m[8])) {
                    $atts[] = stripcslashes($m[8]);
                }
            }
        } else {
            $atts = ltrim($text);
        }

        return $atts;
    }

    private function __loadHooks() {
        foreach (Configure::read('Hook.helpers') as $helper) {
            $pluginSplit = pluginSplit($helper);
            $helper = strpos($helper, '.') !== false ? substr($helper, strpos($helper, '.') + 1) : $helper;

            if ($helper == 'HookCollection' || !is_object($this->__view->{$helper})) {
                continue;
            }

            if (strpos($helper, 'Hook') !== false) {
                if (!is_object($this->{$helper})) {
                    continue;
                }

                $methods = array();
                $_methods = get_this_class_methods($this->__view->{$helper});
                $group = strpos($helper, 'Hooktags') !== false ? 'Hooktags' : 'Hooks';

                foreach ($_methods as $method) {
                    // ignore private and protected methods
                    if (strpos($method, '__') === 0 || strpos($method, '_') === 0) {
                        continue;
                    }

                    $methods[] = $method;

                    if (isset($this->__map[$group][$method])) {
                        $this->__map[$group][$method][] = (string)$helper;
                    } else {
                        $this->__map[$group][$method] = array((string)$helper);
                    }  
                }

                if ($pluginSplit[0]) {
                    $this->_hookObjects[$group]["{$pluginSplit[0]}.{$helper}"] = $methods;
                } else {
                    $this->_hookObjects[$group][$helper] = $methods;
                }
            }
        }

        $this->_methods['Hooks'] = array_keys($this->__map['Hooks']);
        $this->_methods['Hooktags'] = array_keys($this->__map['Hooktags']);
    }
}