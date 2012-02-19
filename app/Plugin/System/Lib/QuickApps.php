<?php
/**
 * QuickApps class
 *
 * PHP version 5
 *
 * @package  QuickApps.Controller.Plugin.System.Lib
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class QuickApps {
/**
 * The built in detectors used with `is()`. Can be modified with `addDetector()`.
 *
 * @var array
 */
    protected static $_detectors = array(
        'view' => array(
            'frontpage' => array('self', '__viewIsFrontpage'),
            'login' => array('self', '__viewIsLogin'),
            'admin' => array('self', '__viewIsAdmin')
        ),
        'user' => array(
            'admin' => array('self', '__userIsAdmin'),
            'logged' => array('self', '__userIsLogged')
        ),
        'theme' => array(
            'core' => array('self', '__themeIsCore')
        ),
        'module' => array(
            'core' => array('self', '__moduleIsCore')
        )
    );

/**
 * Detector method. Uses the built in detection rules
 * as well as additional rules defined with QuickApps::addDetector()
 * Any detector can be called as `is($detect)`.
 *
 * #Built-in detectors:
 * - is('view.frontpage'): is frontpage ?
 * - is('view.login'): is login screen ?
 * - is('view.admin'): is admin prefix ?
 * - is('user.logged'): is user logged in?
 * - is('user.admin'): has user admin privileges ?
 * - is('theme.core', 'ThemeName'): is `ThemeName` a core theme ?
 * - is('module.core', 'ModuleName'): is `ModuleName` a core module ?
 *
 * ##Example:
 * Is actual request site's frontpage ?
 * {{{
 *  $this->Layout->is('view.frontpage');
 * }}}
 *
 * @param string $detect Dot-Syntax unsersored_detector_name and group name. e.g.: `group.detector_name`
 * @param mixed $p Optional parameter for callback methods
 * @return boolean Whether or not the element is the type you are checking
 */
    public static function is($detect, $p = null) {
        $detect = strtolower($detect);
        list($group, $check) = pluginSplit($detect);

        if (isset(self::$_detectors[$group][$check]) &&
            is_callable(self::$_detectors[$group][$check])
        ) {
            return call_user_func(self::$_detectors[$group][$check], $p);
        } else {
            return false;
        }
    }

/**
 * Add a new detector to the list of detectors.
 * All detector callbacks are grouped by category, this allows to group all
 * detectors by module name and avoid collisions between each other.
 *
 * ###Example:
 * {{{
 *  QuickApps::addDetector('my_module.detector_name', array('MyModuleHookHelper', 'detector_handler'));
 * }}}
 *
 * The above will register `detector_name` on `my_module` category.
 * Also, we are using MyModule's Hook Helper class to register the callback method.
 * This last should looks:
 *
 * # MyModuleHookHelper.php
 * {{{
 *  class MyModuleHookHelper extends AppHelper {
 *      public static function detector_handler() {
 *          return (detector login here);
 *      }
 *  }
 * }}}
 *
 * @param string $detect Dot-Syntax detector name
 * @param array $callback Array with ClassName and Method Name
 * @return void
 */
    public static function addDetector($detect, $callback) {
        $detect = strtolower($detect);
        list($group, $check) = pluginSplit($detect);

        if ($group && $check && is_array($callback)) {
            self::$_detectors[$group][$check] = $callback;
        }
    }

/**
 * Translation function, domain search order:
 * 1- Current plugin
 * 2- Default
 * 3- Translatable entries cache
 *
 * @param string $singular String to translate.
 * @return string The translated string.
 */
    public static function __t($singular = false, $args = null) {
        if (!$singular) {
            return;
        }

        App::uses('I18n', 'I18n');

        $route = class_exists('Router') ? Router::getParams() : null;

        if (isset($route['plugin']) && !empty($route['plugin'])) {
            $translated = I18n::translate($singular, null, Inflector::underscore($route['plugin'])); # 1º look in plugin
        } else {
            $translated = $singular;
        }

        if ($translated === $singular) { # 2º look in default
            $translated = I18n::translate($singular, null, 'default');
        }

        if ($translated === $singular) { # 3º look in transtalion db-cache
            $cache = Cache::read(md5($singular) . '_' . Configure::read('Config.language'), 'i18n');
            $translated = $cache ? $cache: $singular;
        }

        if ($args === null) {
            return $translated;
        }

        return vsprintf($translated, $args);
    }

    public static function userRoles() {
        $roles = array();

        if (!self::__userIsLogged()) {
            $roles[] = 3;
        } else {
            $roles = CakeSession::read('Auth.User.role_id');
        }

        return $roles;
    }

/**
 * Return only the methods for the indicated object.
 * It will strip out the inherited methods.
 *
 * @return array List of methods.
 */
    public static function get_this_class_methods($class) {
        $methods = array();
        $primary = get_class_methods($class);

        if ($parent = get_parent_class($class)) {
            $secondary = get_class_methods($parent);
            $methods = array_diff($primary, $secondary);
        } else {
            $methods = $primary;
        }

        return $methods;
    }

/**
 * Create Unique Arrays using an md5 hash
 *
 * @param array $array
 * @return array
 */
    public static function arrayUnique($array, $preserveKeys = false) {
        $arrayRewrite = array();
        $arrayHashes = array();

        foreach ($array as $key => $item) {
            $hash = md5(serialize($item));

            if (!isset($arrayHashes[$hash])) {
                $arrayHashes[$hash] = $hash;

                if ($preserveKeys) {
                    $arrayRewrite[$key] = $item;
                } else {
                    $arrayRewrite[] = $item;
                }
            }
        }

        return $arrayRewrite;
    }

/**
 * Strip language prefix from the given URL.
 * e.g.: `http://site.com/eng/some-url` becomes http://site.com/some-url`
 *
 * @param string $url URL to replace.
 * @return string URL with no language prefix.
 */
    public static function strip_language_prefix($url) {
        $url = preg_replace('/\/[a-z]{3}\//', '/', $url);

        return $url;
    }

/**
 * Replace the first ocurrence only.
 *
 * @param string $str_pattern What to find for.
 * @param string $str_replacement The replacement for $str_pattern.
 * @param string $string The original to find and replace.
 * @return string
 */
    public static function str_replace_once($str_pattern, $str_replacement, $string) {
        if (strpos($string, $str_pattern) !== false) {
            $occurrence = strpos($string, $str_pattern);
            return substr_replace($string, $str_replacement, strpos($string, $str_pattern), strlen($str_pattern));
        }

        return $string;
    }

/**
 * Check if the given module name belongs to QA Core installation.
 *
 * @param string $module Module name to check.
 * @return bool TRUE if module is a core module, FALSE otherwise.
 */
    function __moduleIsCore($module) {
        $module = Inflector::camelize($module);

        if (CakePlugin::loaded($module)) {
            $path = CakePlugin::path($module);

            if (strpos($path, APP . 'Plugin' . DS) !== false) {
                return true;
            }
        }

        return false;
    }

/**
 * Check if the given theme name belongs to QA Core installation.
 *
 * @param string $theme Theme name to check.
 * @return bool TRUE if theme is a core theme, FALSE otherwise.
 */
    private static function __themeIsCore($theme) {
        $theme = Inflector::camelize($theme);
        $theme = strpos($theme, 'Theme') !== 0 ? "Theme{$theme}" : $theme;

        if (CakePlugin::loaded($theme)) {
            $app_path = CakePlugin::path($theme);

            if (strpos($app_path, APP . 'View' . DS . 'Themed' . DS) !== false) {
                return true;
            }
        }

        return false;
    }

    private static function __viewIsFrontpage() {
        $params = Router::getParams();

        return (
            $params['plugin'] == 'Node' &&
            $params['action'] == 'index' &&
            !Configure::read('Variable.site_frontpage')
        );
    }

    private static function __viewIsLogin() {
        $params = Router::getParams();

        return (
            $params['plugin'] == 'user' &&
            $params['controller'] == 'user' &&
            in_array($params['action'], array('login', 'admin_login'))
        );
    }

    private static function __viewIsAdmin() {
        $params = Router::getParams();

        return isset($params['admin']) && $params['admin'];
    }

    private static function __userIsAdmin() {
        return in_array(1, (array)self::userRoles());
    }

    private static function __userIsLogged() {
        return CakeSession::check('Auth.User.id');
    }
}