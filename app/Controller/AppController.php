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
            'inline' => array()
        ),
        'javascripts' => array(
            'inline' => array(),
            'file' => array('/system/js/jquery.js', '/system/js/quickapps.js')
        ),
        'meta' => array() # meta tags for layout
    );

    public $helpers = array(
        'HookCollection',
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
        'HookCollection',
        'Session',
        'Cookie',
        'RequestHandler',
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

        $isAllowed = ($this->Auth->allowedActions == array('*') || in_array($this->request->params['action'], $this->Auth->allowedActions));

        return $isAllowed;
    }

/**
 * Wrapper method to QuickappsComponent::title()
 *
 * @see QuickappsComponent::title()
 */
    public function title($str) {
        return $this->Quickapps->title($str);
    }

/**
 * Wrapper method to QuickappsComponent::flashMsg()
 *
 * @see QuickappsComponent::flashMsg()
 */
    public function flashMsg($msg, $class = 'success') {
        return $this->Quickapps->flashMsg($msg, $class);
    }

/**
 * Wrapper method to QuickappsComponent::blockPush()
 *
 * @see QuickappsComponent::blockPush()
 */
    public function blockPush($block = array(), $region = null) {
        return $this->Quickapps->blockPush($block, $region);
    }

/**
 * Wrapper method to HookCollectionComponent::attachModuleHooks()
 *
 * @see HookCollectionComponent::attachModuleHooks()
 */
    public function attachModuleHooks($module) {
        return $this->HookCollection->attachModuleHooks($module);
    }

/**
 * Wrapper method to HookCollectionComponent::detachModuleHooks()
 *
 * @see HookCollectionComponent::detachModuleHooks()
 */
    public function detachModuleHooks($module) {
        return $this->HookCollection->detachModuleHooks($module);
    }

/**
 * Wrapper method to HookCollectionComponent::hook()
 *
 * @see HookCollectionComponent::hook()
 */
    public function hook($hook, &$data = array(), $options = array()) {
        $hook = Inflector::underscore($hook);

        return $this->HookCollection->hook($hook, $data, $options);
    }

/**
 * Wrapper method to HookCollectionComponent::hookDefined()
 *
 * @see HookCollectionComponent::hookDefined()
 */
    public function hookDefined($hook) {
        return $this->HookCollection->hookDefined($hook);
    }

/**
 * Wrapper method to HookCollectionComponent::hookEnable()
 *
 * @see HookCollectionComponent::hookEnable()
 */
    public function hookEnable($hook) {
        return $this->HookCollection->hookEnable($hook);
    }

/**
 * Wrapper method to HookCollectionComponent::hookDisable()
 *
 * @see HookCollectionComponent::hookDisable()
 */
    public function hookDisable($hook) {
        return $this->HookCollection->hookDisable($hook);
    }

/**
 * Wrapper method to QuickappsComponent::setCrumb()
 *
 * @see QuickappsComponent::setCrumb()
 */
    public function setCrumb($url = false) {
        if (func_num_args() > 1) {
            foreach (func_get_args() as $arg) {
                $this->Quickapps->setCrumb($arg);
            }
        } else {
            return $this->Quickapps->setCrumb($url);
        }
    }

/**
 * Load and attach hooks to AppController.
 *  - Preload helpers hooks.
 *  - Preload behaviors hooks.
 *  - Preload components hooks.
 *
 * @return void
 */
    private function __preloadHooks() {
        $_variable = Cache::read('Variable');
        $_modules = Cache::read('Modules');
        $_themeType = Router::getParam('admin') ? 'admin_theme' : 'site_theme';
        $hook_objects = Cache::read("hook_objects_{$_themeType}");

        if (!$hook_objects) {
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

            $paths = $c = $h = $b = array();
            $_modules = array_keys($_modules);
            $themeToUse = $_variable[$_themeType];
            $plugins = App::objects('plugin', null, false);
            $modulesCache = Cache::read('Modules');
            $theme_path = App::themePath($themeToUse);

            foreach ($plugins as $plugin) {
                $ppath = CakePlugin::path($plugin);
                $isTheme = false;

                if (strpos($ppath, APP . 'View' . DS . 'Themed' . DS) !== false || strpos($ppath, THEMES) !== false) {
                    $isTheme = true;
                }

                // inactive module. (except fields because they are not registered as plugin in DB)
                if (!in_array($plugin, $_modules) && strpos($ppath, DS . 'Fields' . DS) === false) {
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
                    APP . 'Controller' . DS . 'Component' . DS,     # core components
                    APP . 'View' . DS . 'Helper' . DS,              # core helpers
                    APP . 'Model' . DS . 'Behavior' . DS,           # core behaviors
                    ROOT . DS . 'Hooks' . DS . 'Behavior' . DS,     # custom MH
                    ROOT . DS . 'Hooks' . DS . 'Helper' . DS,       # custom VH
                    ROOT . DS . 'Hooks' . DS . 'Component' . DS     # custom CH
                ),
                (array)$paths
            );

            $folder = new Folder;

            foreach ($paths as $key => $path) {
                if (!file_exists($path)) {
                    continue;
                }

                $folder->path = $path;
                $files = $folder->find('(.*)Hook(Component|Behavior|Helper|tagsHelper)\.php');
                $plugin = is_string($key) ? explode('_', $key) : false;
                $plugin = is_array($plugin) ? $plugin[0] : $plugin;

                foreach ($files as $file) {
                    $prefix = ($plugin) ? Inflector::camelize($plugin) . '.' : '';
                    $hook = $prefix . Inflector::camelize(str_replace(array('.php'), '', basename($file)));
                    $hook = str_replace(array('Component', 'Behavior', 'Helper'), '', $hook);

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

            Configure::write('Hook.components', $c);
            Configure::write('Hook.behaviors', $b);
            Configure::write('Hook.helpers', $h);
            Cache::write("hook_objects_{$_themeType}", Configure::read('Hook'));
        } else {
            $this->helpers = array_merge($this->helpers, $hook_objects['helpers']);
            $this->components = array_merge($this->components, $hook_objects['components']);

            Configure::write('Hook', $hook_objects);
        }
    }
}