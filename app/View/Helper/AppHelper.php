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

/**
 * Load all hooks (and optionally hooktags) of specified Module.
 *
 * @param string $module Name of the module
 * @param boolean $hooktags TRUE load hooktags. FALSE do not load.
 * @return void
 */
    public function attachModuleHooks($module, $hooktags = true) {
        return $this->_View->HookCollection->attachModuleHooks($module, $hooktags);
    }

/**
 * Unload all hooks & hooktags of specified Module.
 *
 * @param string $module Name of the module
 * @return void
 */  
    public function deattachModuleHooks($module) {
        return $this->_View->HookCollection->deattachModuleHooks($module);
    }

/**
 * Chech if hook method exists.
 *
 * @param string $hook Name of the hook to check
 * @return boolean
 */
    public function hookDefined($hook) {
        return $this->_View->HookCollection->hookDefined($hook);
    }

/**
 * Chech if hooktag method exists.
 *
 * @param string $hooktag Name of the hooktag method to check
 * @return boolean
 */
    public function hooktagDefined($hooktag) {
        return $this->_View->HookCollection->hooktagDefined($hooktag);
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
        return $this->_View->HookCollection->hook($hook, $data, $options);
    }

/**
 * Turn on hook method if is turned off.
 *
 * @param string $hook Hook name to turn on.
 * @return boolean TRUE on success. FALSE hook does not exists or is already on.
 */
    public function hookEnable($hook) {
        return $this->_View->HookCollection->hookEnable($hook);
    }

/**
 * Turns off hook method.
 *
 * @param string $hook Hook name to turn off.
 * @return boolean TRUE on success. FALSE hook does not exists.
 */ 
    public function hookDisable($hook) {
        return $this->_View->HookCollection->hookDisable($hook);
    }

/**
 * Return an array list of all registered hooktag methods.
 *
 * @return array Array list of all available hooktag methods.
 */ 
    public function hooktagsList() {
        return $this->_View->HookCollection->hooktagsList();
    }

/**
 * Callback function
 *
 * @see Layout::hooktags()
 * @return mixed Hook response or false in case of no response.
 */
    public function doHooktag($m) {
        return $this->_View->HookCollection->doHooktag($m);
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
        return $this->_View->HookCollection->setHookOptions($options);
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
    protected function php_eval($code) {
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
    protected function urlMatch($patterns, $path = false) {
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

                if ($this->__filesExists($filePath)) {
                    $this->helpers[] = $hook;
                }
            }
        }

        $this->helpers = array_unique($this->helpers);
    }

    private function __filesExists($files) {
        foreach ($files as $f) {
            if (!file_exists($f)) {
                return false;
            }
        }

        return true;
    }
}