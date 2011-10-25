<?php
/**
 * Application Controller
 *
 * PHP version 5
 *
 * @package  QuickApps.Controller
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class AppController extends Controller {
    public $view = 'Theme';
    public $theme = 'default';

    public $Layout = array(
        'feed' => null, # url to rss feed
        'blocks' => array(),
        'node' => array(),
        'viewMode' => '', # full, list
        'header' => array(), # extra code for header
        'footer' => array(), # extra code for </body>
        'stylesheets' => array(
            'all' => array(),
            'braille' => array(),
            'embossed' => array(),
            'handheld' => array(),
            'print' => array(),
            'projection' => array(),
            'screen' => array(),
            'speech' => array(),
            'tty' => array(),
            'tv' => array(),
            'embed' => array()
        ),
        'javascripts' => array(
            'embed' => array(),
            'file' => array('jquery.js', 'quickapps.js')
        ),
        'meta' => array() # meta tags for layout
    );

    public $helpers = array(
        'Layout',
        'Form' => array('className' => 'QaForm'),
        'Html' => array('className' => 'QaHtml'),
        'Session',
        'Cache',
        'Js',
        'Time'
    );

    public $uses = array(
        'System.Variable',
        'System.Module',
        'Menu.MenuLink',
        'Locale.Language'
    );

    public $components = array(
        'Session',
        'Cookie',
        'RequestHandler',
        'Hook',
        'Acl',
        'Auth',
        'Quickapps'
    );

    public function __construct($request = null, $response = null) {
        $this->__preloadHooks();
        parent::__construct($request, $response);
    }

    public function beforeRender() {
        if ($this->Layout['feed']) {
            $this->Layout['meta']['link'] = $this->Layout['feed'];
        }

        $this->set('Layout', $this->Layout);

        if ($this->name == 'CakeError') {
            $this->beforeFilter();
            $this->layout = 'error';
        }

        return true;
    }

    public function isAuthorized($user) {
        $this->Quickapps->accessCheck();

		$isAllowed = (
			$this->Auth->allowedActions == array('*') || 
			in_array($this->request->params['action'], $this->Auth->allowedActions)
		);

        return $isAllowed;
    }

/**
 * shortcut for $this->set(`title_for_layout`...)
 *
 * @param string $str layout title
 * @return void
 */
    public function title($str) {
        return $this->Quickapps->title($str);
    }

/**
 * shortcut for Session setFlash
 *
 * @param string $msg mesagge to display
 * @param string $class type of message: error, success, alert, bubble
 * @return void
 */
    public function flashMsg($msg, $class = 'success') {
        return $this->Quickapps->flashMsg($msg, $class);
    }

/**
 * Insert custom block in stack
 *
 * @param array $data formatted block array
 * @param string $region theme region where to push
 * @return boolean
 */
    public function blockPush($block = array(), $region = null, $show_on = true) {
        return $this->Quickapps->blockPush($block, $region, $show_on);
    }

/**
 * Wrapper method to Hook::hook_defined
 *
 * @param string $hook Name of the event
 * @return bool
 */
    public function hook_defined($hook) {
        return $this->Hook->hook_defined($hook);
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
 *  $response2 = $this->hook('no_collect_and_breakOn_hook_with_no_parameters');
 * }}}
 *
 * @param array $options Array of options to overwrite
 * @return void
 */
    public function setHookOptions($options) {
        $this->Hook->setHookOptions($options);
    }

/**
 * Wrapper method to Hook::__dispatchEvent
 *
 * @param string $hook Name of the event
 * @param mix $data Any data to attach
 * @param bool $raw_return false means return asociative data, true will return a listed array
 * @return mixed FALSE -or- result array
 */
    public function hook($hook, &$data = array(), $options = array()) {
        $hook = Inflector::underscore($hook);
        return $this->Hook->hook($hook, $data, $options);
    }

/**
 * Set crumb from url parse or add url to the links list
 *
 * @param mixed $url if is array then will push de formated array to the crumbs list
 *                   else will set base crum from string parsing
 * @return void
 */
    public function setCrumb($url = false) {
        return $this->Quickapps->setCrumb($url);
    }

/**
 * Load and attach hooks to AppController. (Hooks can be Components, Helpers, Behaviours)
 *  - Preload helpers hooks
 *  - Preload behaviors hooks
 *  - Preload components hooks
 *
 * @return void
 */
    private function __preloadHooks() {
        $paths = $c = $h = $b = array();

        // load current theme hooks only
        $_variable = Cache::read('Variable');
        $_modules = Cache::read('Modules');
        $_themeType = Router::getParam('admin') ? 'admin_theme' : 'site_theme';

        if (!$_variable) {
            $this->loadModel('System.Variable');

            $_variable = $this->Variable->find('first', array('conditions' => array('Variable.name' => $_themeType)));
            $_variable[$_themeType] = $_variable['Variable']['value'];

            ClassRegistry::flush();
            unset($this->Variable);
        }

        if (!$_modules) {
            $this->loadModel('System.Module');

            foreach ($this->Module->find('all', array('fields' => array('name'), 'conditions' => array('Module.status' => 1))) as $m) {
                $_modules[$m['Module']['name']] = array();
            }

            ClassRegistry::flush();
            unset($this->Module);
        }

        $_modules = array_keys($_modules);
        $themeToUse = $_variable[$_themeType];
        $plugins = App::objects('plugin', null, false);

        foreach ($plugins as $plugin) {
            $ppath = CakePlugin::path($plugin);
            $modulesCache = Cache::read('Modules');

            # inactive module, except fields that are nor registered as plugin en DB
            if (!in_array($plugin, $_modules) && strpos($ppath, DS . 'Fields' . DS) === false) {
                continue;
            }

            if ((isset($modulesCache[$plugin]['status']) && $modulesCache[$plugin]['status'] == 0) ||
                (strpos($ppath, DS . 'View' . DS . 'Themed') !== false && strpos($ppath, 'Themed' . DS . $themeToUse . DS . 'app') === false)
            ) {
                continue; # Important: skip no active themes
            }

            $paths["{$plugin}_components"] = $ppath . 'Controller' . DS . 'Component' . DS;
            $paths["{$plugin}_behaviors"] = $ppath . 'Model' . DS . 'Behavior' . DS;
            $paths["{$plugin}_helpers"] = $ppath . 'View' . DS . 'Helper' . DS;
        }

        $paths = array_merge(
            array(
                APP . 'Controller' . DS . 'Components' . DS,    # core components
                APP . 'View' . DS . 'Helper' . DS,              # core helpers
                APP . 'Model' . DS . 'Behavior' . DS            # core behaviors
            ),
            (array)$paths
        );

        $folder = new Folder;

        foreach ($paths as $key => $path) {
            $folder->path = $path;
            $files = $folder->find('(.*)Hook(Component|Behavior|Helper)\.php');
            $plugin = is_string($key) ? explode('_', $key) : false;
            $plugin = is_array($plugin) ? $plugin[0] : $plugin;

            foreach ($files as $file) {
                $prefix = ($plugin) ? Inflector::camelize($plugin) . '.' : '';
                $hook = $prefix . Inflector::camelize(str_replace(array('.php'), '', basename($file)));
                $hook = str_replace(array('Component', 'Behavior', 'Helper'),'', $hook);

                if (strpos($path, 'Helper')) {
                    $h[] = $hook;
                    $this->helpers[] = $hook;
                } elseif (strpos($path, 'Behavior')) {
                    $b[] = $hook;
                } else {
                    $c[] = $hook;
                    $this->components[] = $hook;
                }
            }
        }

        $h[] = 'CustomHooks'; # merge custom hooktags helper

        Configure::write('Hook.components', $c);
        Configure::write('Hook.behaviors', $b);
        Configure::write('Hook.helpers', $h);
    }
}