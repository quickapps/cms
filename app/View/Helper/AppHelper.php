<?php
App::uses('Helper', 'View');
class AppHelper extends Helper {
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
        'Menu',        # menu helper
        'Form' => array('className' => 'QaForm'),
        'Html' => array('className' => 'QaHtml'),
        'Session',
        'Js'
    );

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

    public function __construct(View $View, $settings = array()) {
        $this->__loadHookObjects();
        parent::__construct($View, $settings = array());
    }

    public function beforeRender() {
        $this->__loadHooks();
        return true;
    }

/**
 * Load all hooks (and optionally hooktags) of specified Module.
 *
 * @param string $module Name of the module
 * @param boolean $hooktags TRUE load hooktags. FALSE do not load.
 * @return void
 */
    public function attachModuleHooks($module, $hooktags = true) {
        $Plugin = Inflector::camelize($module);

        if (isset($this->_hookObjects[$Plugin . 'Hook'])) {
            return;
        }

        $folder = new Folder;
        $folder->path = CakePlugin::path($Plugin) . 'View' . DS . 'Helper' . DS;
        $file_pattern = $hooktags ? '(.*)Hook(tagsHelper|Helper)\.php' : '(.*)Hook(Helper)\.php';
        $files = $folder->find($file_pattern);

        foreach ($files as $helper) {
            $helper = str_replace('Helper.php', '', $helper);
            $this->$helper = $this->_View->loadHelper("{$Plugin}.{$helper}" , array('plugin' => $Plugin));

            if (!is_object($this->{$helper})) {
                continue;
            }

            $methods = array();
            $_methods = get_this_class_methods($this->{$helper});

            foreach ($_methods as $method) {
                $methods[] = $method;

                if (isset($this->__map[$method])) {
                    $this->__map[$method][] = (string)$helper;
                } else {
                    $this->__map[$method] = array((string)$helper);
                }
            }

            $this->_hookObjects["{$Plugin}.{$helper}"] = $methods;
        }

        $this->_methods['Hooks'] = array_keys($this->__map['Hooks']);
        $this->_methods['Hooktags'] = array_keys($this->__map['Hooktags']);
        $this->_View->Layout->_tmp['__hooktags_reg'] = implode('|', $this->hooktagsList());
    }

/**
 * Unload all hooks & hooktags of specified Module.
 *
 * @param string $module Name of the module
 * @return void
 */  
    public function deattachModuleHooks($module) {
        $Plugin = Inflector::camelize($module);

        foreach ($this->_hookObjects as $helper => $hooks) {
            if (strpos($helper, "{$Plugin}.") === false) {
                continue;
            }

            foreach ($this->_hookObjects[$helper] as $hook) {
                unset($this->__map[$hook]);
            }

            unset($this->_hookObjects[$helper]);
        }

        $this->_methods['Hooks'] = array_keys($this->__map['Hooks']);
        $this->_methods['Hooktags'] = array_keys($this->__map['Hooktags']);
        $this->_View->Layout->_tmp['__hooktags_reg'] = implode('|', $this->hooktagsList());
    }

/**
 * Chech if hook method exists.
 *
 * @param string $hook Name of the hook to check
 * @return boolean
 */
    public function hook_defined($hook) {
        return (isset($this->__map['Hooks'][$hook]));
    }

/**
 * Chech if hooktag method exists.
 *
 * @param string $hooktag Name of the hooktag method to check
 * @return boolean
 */
    public function hooktag_defined($hooktag) {
        return (isset($this->__map['Hooktags'][$hooktag]));
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
        $attr = $this->__hooktagParseAtts( $m[3] );
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
 * Evaluate a string of PHP code.
 *
 * This is a wrapper around PHP's eval(). It uses output buffering to capture both
 * returned and printed text. Unlike eval(), we require code to be surrounded by
 * <?php ?> tags; in other words, we evaluate the code as if it were a stand-alone
 * PHP file.
 *
 * Using this wrapper also ensures that the PHP code which is evaluated can not
 * overwrite any variables in the calling code, unlike a regular eval() call.
 *
 * @param string $code The code to evaluate.
 * @return
 *   A string containing the printed output of the code, followed by the returned
 *   output of the code.
 *
 */
    protected function _php_eval($code) {
        ob_start();

        $Layout =& $this->_View->viewVars['Layout'];
        $View =& $this->_View;

        print eval('?>' . $code);

        $output = ob_get_contents();

        ob_end_clean();

        return (bool)$output;
    } 

/**
 * Check if a path matches any pattern in a set of patterns.
 *
 * @param string $patterns String containing a set of patterns separated by \n, \r or \r\n.
 * @param mixed $path String as path to match. Or boolean FALSE to use actual page url.
 * @return boolean TRUE if the path matches a pattern, FALSE otherwise.
 */
    protected function _urlMatch($patterns, $path = false) {
        if (empty($patterns)) {
            return false;
        }

        $path = !$path ? '/' . $this->_View->request->url : $path;
        $patterns = explode("\n", $patterns);

        if (Configure::read('Variable.url_language_prefix')) {
            if (!preg_match('/^\/([a-z]{3})\//', $path, $matches)) {
                $path = "/" . Configure::read('Config.language'). $path;
            }
        }

        foreach ($patterns as &$p) {
            $p = Router::url('/') . $p;
            $p = str_replace('//', '/', $p);
            $p = str_replace($this->_View->base, '', $p);
        }

        $patterns = implode("\n", $patterns);

        // Convert path settings to a regular expression.
        // Therefore replace newlines with a logical or, /* with asterisks and the <front> with the frontpage.
        $to_replace = array(
            '/(\r\n?|\n)/', // newlines
            '/\\\\\*/',     // asterisks
            '/(^|\|)\/($|\|)/' // front '/'
        );

        $replacements = array(
            '|',
            '.*',
            '\1' . preg_quote(Router::url('/'), '/') . '\2'
        );

        $patterns_quoted = preg_quote($patterns, '/');
        $regexps[$patterns] = '/^(' . preg_replace($to_replace, $replacements, $patterns_quoted) . ')$/';

        return (bool) preg_match($regexps[$patterns], $path);
    }

/**
 * Dispatch Helper-hooks from all the plugins and core
 *
 * @see AppHelper::hook()
 * @return mixed Either the last result or all results if collectReturn is on. Or NULL in case of no response
 */
    private function __dispatchHook($hook, &$data = array(), $options = array()) {
        $options = array_merge($this->Options, (array)$options);
        $collected = array();
        $result = null;

        if (!$this->hook_defined($hook)) {
            $this->__resetOptions();

            return null;
        }

        if (isset($this->__map['Hooks'][$hook])) {
            foreach ($this->__map['Hooks'][$hook] as $object) {
                if (is_callable(array($this->{$object}, $hook))) {
                    $result = $this->{$object}->$hook($data);

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

    private function __resetOptions() {
        if ($this->Options !== $this->__Options) {
            $this->Options = $this->__Options;
        }
    }

    private function __loadHooks() {
        foreach ($this->helpers as $helper) {
            if (is_array($helper)) {
                continue;
            }

            $pluginSplit = pluginSplit($helper);
            $helper = strpos($helper, '.') !== false ? substr($helper, strpos($helper, '.') + 1) : $helper;

            if (strpos($helper, 'Hook') !== false) {
                if (!is_object($this->{$helper})) {
                    continue;
                }

                $methods = array();
                $_methods = get_this_class_methods($this->{$helper});
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

    private function __loadHookObjects() {
        if ($hooks = Configure::read('Hook.helpers')) {
            foreach ($hooks as $hook) {
                $filePath = array();

                if (strpos($hook, '.') !== false) {
                    list($plugin, $class) = pluginSplit($hook);
                    $filePath[] = App::pluginPath($plugin) . 'View' . DS . 'Helper' . DS . "{$class}Helper" . '.php';
                } else {
                    $filePath[] = APP . 'View' . DS . 'Helper' . DS . "{$hook}Helper.php";
                    $filePath[] = ROOT . DS . 'Hooks' . DS . 'Helper' . DS . "{$hook}Helper.php";
                }

                if ($this->__files_exists($filePath)) {
                    $this->helpers[] = $hook;
                }
            }
        }

        $this->helpers = array_unique($this->helpers);
    }

    private function __files_exists($files) {
        foreach ($files as $f) {
            if (!file_exists($f)) {
                return false;
            }
        }

        return true;
    }
}