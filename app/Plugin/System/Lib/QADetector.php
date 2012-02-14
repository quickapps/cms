<?php
/**
 * QADetector class
 *
 * PHP version 5
 *
 * @package  QuickApps.Controller.Plugin.System.Lib
 * @version  1.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://cms.quickapps.es
 */
class QADetector {
/**
 * The built in detectors used with `is()` can be modified with `addDetector()`.
 *
 * @var array
 */
    protected static $_detectors = array(
        'view' => array(
            'frontpage' => array('self', '__viewIsFrontpage'),
            'login' => array('self', '__viewIsLogin')
        ),
        'user' => array(
            'admin' => array('self', '__userIsAdmin'),
            'logged' => array('self', '__userIsLogged')
        )
    );

/**
 * Detector method. Uses the built in detection rules
 * as well as additional rules defined with QADetector::addDetector()
 * Any detector can be called as `is($detect)`.
 *
 * #Built-in detectors:
 * - is('view.frontpage')
 * - is('view.login')
 * - is('user.logged')
 * - is('user.admin')
 *
 * ##Example:
 * Is actual request site frontpage ?
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
 *  QADetector::addDetector('my_module.detector_name', array('MyModuleHookHelper', 'detector_handler'));
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

    public static function userRoles() {
        $roles = array();

        if (!self::__userIsLogged()) {
            $roles[] = 3;
        } else {
            $roles = CakeSession::read('Auth.User.role_id');
        }

        return $roles;
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
            $params['plugin'] == 'User' &&
            $params['controller'] == 'user' &&
            in_array($params['action'], array('login', 'admin_login'))
        );
    }

    private static function __userIsAdmin() {
        return in_array(1, (array)self::userRoles());
    }

    private static function __userIsLogged() {
        return CakeSession::check('Auth.User.id');
    }
}