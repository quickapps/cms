<?php
App::uses('Helper', 'View');

class AppHelper extends Helper {
    public $helpers = array(
        'Layout',
        'Menu',
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

/**
 * Preload hook helpers classes
 *
 * @return void
 */
    public function __construct(View $View, $settings = array()) {
        $this->__loadHookObjects();
        parent::__construct($View, $settings = array());
    }

/**
 * Wrapper method to HookCollectionHelper::attachModuleHooks()
 *
 * @see HookCollectionHelper::attachModuleHooks()
 */
    public function attachModuleHooks($module) {
        return $this->_View->HookCollection->attachModuleHooks($module);
    }

/**
 * Wrapper method to HooktagsCollectionHelper::attachModuleHooktags()
 *
 * @see HooktagsCollectionHelper::attachModuleHooktags()
 */
    public function attachModuleHooktags($module) {
        return $this->_View->HooktagsCollection->attachModuleHooktags($module);
    }

/**
 * Wrapper method to HookCollectionHelper::detachModuleHooks()
 *
 * @see HookCollectionHelper::detachModuleHooks()
 */
    public function detachModuleHooks($module) {
        return $this->_View->HookCollection->detachModuleHooks($module);
    }

/**
 * Wrapper method to HooktagsCollectionHelper::detachModuleHooktags()
 *
 * @see HooktagsCollectionHelper::detachModuleHooktags()
 */
    public function detachModuleHooktags($module) {
        return $this->_View->HooktagsCollection->detachModuleHooktags($module);
    }

/**
 * Wrapper method to QuickApps::is()
 *
 * @see QuickApps::is()
 */
    public function is($detect) {
        return QuickApps::is($detect, $this->_View);
    }

/**
 * Wrapper method to HookCollectionHelper::hook()
 *
 * @see HookCollectionHelper::hook()
 */
    public function hook($hook, &$data = array(), $options = array()) {
        return $this->_View->HookCollection->hook($hook, $data, $options);
    }

/**
 * Wrapper method to HooktagsCollectionHelper::hooktags()
 *
 * @see HooktagsCollectionHelper::hooktags()
 */
    public function hooktags($text) {
        return $this->_View->HooktagsCollection->hooktags($text);
    }

/**
 * Wrapper method to HookCollectionHelper::hookDefined()
 *
 * @see HookCollectionHelper::hookDefined()
 */
    public function hookDefined($hook) {
        return $this->_View->HookCollection->hookDefined($hook);
    }

/**
 * Wrapper method to HookCollectionHelper::hooktagDefined()
 *
 * @see HookCollectionHelper::hooktagDefined()
 */
    public function hooktagDefined($hooktag) {
        return $this->_View->HooktagsCollection->hooktagDefined($hooktag);
    }

/**
 * Wrapper method to HookCollectionHelper::hookEnable()
 *
 * @see HookCollectionHelper::hookEnable()
 */
    public function hookEnable($hook) {
        return $this->_View->HookCollection->hookEnable($hook);
    }

/**
 * Wrapper method to HooktagsCollectionHelper::hooktagEnable()
 *
 * @see HooktagsCollectionHelper::hooktagEnable()
 */
    public function hooktagEnable($hooktag) {
        return $this->_View->HooktagsCollection->hooktagEnable($hooktag);
    }

/**
 * Wrapper method to HookCollectionHelper::hookDisable()
 *
 * @see HookCollectionHelper::hookDisable()
 */
    public function hookDisable($hook) {
        return $this->_View->HookCollection->hookDisable($hook);
    }

/**
 * Wrapper method to HooktagsCollectionHelper::hooktagDisable()
 *
 * @see HooktagsCollectionHelper::hooktagDisable()
 */
    public function hooktagDisable($hooktag) {
        return $this->_View->HooktagsCollection->hooktagDisable($hooktag);
    }

/**
 * Wrapper method to HooktagsCollectionHelper::stripHooktags()
 *
 * @see HooktagsCollectionHelper::stripHooktags()
 */
    public function stripHooktags($text) {
        return $this->_View->HooktagsCollection->stripHooktags($text);
    }

/**
 * Wrapper method to HooktagsCollectionHelper::specialTags()
 *
 * @see HooktagsCollectionHelper::specialTags()
 */
    public function specialTags($text) {
        return $this->_View->HooktagsCollection->specialTags($text);
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
        $hooks = array_merge((array)Configure::read('Hook.helpers'), (array)Configure::read('Hook.hooktags'));

        if ($hooks) {
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