<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace User\Utility;

use Cake\Datasource\ModelAwareTrait;
use Cake\Error\FatalErrorException;
use Cake\Filesystem\Folder;
use Cake\Routing\Router;
use Cake\Utility\Inflector;
use QuickApps\Core\Plugin;
use QuickApps\Core\StaticCacheTrait;

/**
 * A simple class for handling plugin's ACOs.
 *
 * ### Usage:
 *
 *     $manager = new AcoManager('PluginName');
 *
 * You must indicate the plugin to manage.
 */
class AcoManager
{

    use ModelAwareTrait;
    use StaticCacheTrait;

    /**
     * Name of the plugin being managed.
     *
     * @var string
     */
    protected $_pluginName;

    /**
     * Constructor.
     *
     * @param string $pluginName The plugin being managed
     * @throws \Cake\Error\FatalErrorException When no plugin name is given.
     */
    public function __construct($pluginName = null)
    {
        $this->_pluginName = $pluginName;

        if (!$this->_pluginName) {
            throw new FatalErrorException(__d('user', 'You must provide a Plugin name to manage.'));
        } else {
            $this->_pluginName = Inflector::camelize($this->_pluginName);
        }

        $this->modelFactory('Table', ['Cake\ORM\TableRegistry', 'get']);
        $this->loadModel('User.Acos');
    }

    /**
     * Grants permissions to all users within $roles over the given $aco.
     *
     * ### Aco path format:
     *
     * - `ControllerName/`: Maps to \<PluginName>\Controller\ControllerName::index()
     * - `ControllerName`: Same.
     * - `ControllerName/action_name`: Maps to \<PluginName>\Controller\ControllerName::action_name()
     * - `Prefix/ControllerName/action_name`: Maps to \<PluginName>\Controller\Prefix\ControllerName::action_name()
     *
     * @param string $path ACO path as described above
     * @param array $roles List of user roles to grant access to. If not given,
     *  $path cannot be used by anyone but "administrators"
     * @return bool True on success
     */
    public function add($path, $roles = [])
    {
        $path = $this->_parseAco($path);
        if (!$path) {
            return false;
        }

        // path already exists
        $nodes = $this->Acos->node($path);
        $nodes = is_object($nodes) ? $nodes->extract('alias')->toArray() : $nodes;
        if ($nodes) {
            if (implode('/', $nodes) === $path) {
                return true;
            }
        }

        $parent = null;
        $current = null;
        $parts = explode('/', $path);

        $this->Acos->connection()->transactional(function () use ($parts, $current, &$parent, $path) {
            foreach ($parts as $alias) {
                $current[] = $alias;
                $pathSegment = implode('/', $current);
                $node = $this->Acos->node($pathSegment);
                if ($node) {
                    $parent = $node->first();
                } else {
                    $acoEntity = $this->Acos->newEntity([
                        'parent_id' => isset($parent->id) ? $parent->id : null,
                        'plugin' => $this->_pluginName,
                        'alias' => $alias,
                        'alias_hash' => md5($alias),
                    ]);
                    $parent = $this->Acos->save($acoEntity);
                }
            }
        });

        if ($parent) {
            $action = $parent;

            // register roles
            if (!empty($roles)) {
                $this->loadModel('User.Permissions');
                $roles = $this->Acos->Roles
                    ->find()
                    ->select(['id'])
                    ->where(['Roles.slug IN' => $roles])
                    ->all();

                foreach ($roles as $role) {
                    $permissionEntity = $this->Permissions->newEntity([
                        'aco_id' => $action->id,
                        'role_id' => $role->id,
                    ]);
                    $this->Permissions->save($permissionEntity);
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Removes the given ACO and its permissions.
     *
     * @param string $path ACO path e.g. `ControllerName/action_name`
     * @return bool True on success, false if path was not found
     */
    public function remove($path)
    {
        $nodes = $this->Acos->node($path);

        if (!$nodes) {
            return false;
        }

        $node = $nodes->first();
        $this->Acos->removeFromTree($node);
        $this->Acos->delete($node);
        return true;
    }

    /**
     * This method should never be used unless you know what are you doing.
     *
     * Populates the "acos" DB with information of every installed plugin, or
     * for the given plugin. It will automatically extracts plugin's controllers
     * and actions for creating a tree structure as follow:
     *
     * - PluginName
     *   - Admin
     *     - PrivateController
     *       - index
     *       - private_action
     *   - ControllerName
     *     - index
     *     - another_action
     *
     * After tree is created you should be able to change permissions using
     * User's permissions section in backend.
     *
     * @param string $for Optional, build ACOs for the given plugin, or
     *  all plugins if not given
     * @param bool $sync Whether to sync the tree or not. When syncing all invalid
     *  ACO entries will be removed from the tree, also new ones will be added. When
     *  syn is set to false only new ACO entries will be added, any invalid entry will
     *  remain in the tree. Defaults to false
     * @return bool True on success, false otherwise
     */
    public static function buildAcos($for = null, $sync = false)
    {
        if (function_exists('ini_set')) {
            ini_set('max_execution_time', 300);
        } elseif (function_exists('set_time_limit')) {
            set_time_limit(300);
        }

        if ($for === null) {
            $plugins = Plugin::collection()->toArray();
        } else {
            try {
                $plugins = [Plugin::info($for)];
            } catch (\Exception $e) {
                return false;
            }
        }

        $added = [];
        foreach ($plugins as $plugin) {
            $aco = new AcoManager($plugin['name']);
            $controllerDir = normalizePath("{$plugin['path']}/src/Controller/");
            $folder = new Folder($controllerDir);
            $controllers = $folder->findRecursive('.*Controller\.php');

            foreach ($controllers as $controller) {
                $controller = str_replace([$controllerDir, '.php'], '', $controller);
                $className = $plugin['name'] . '\\' . 'Controller\\' . str_replace(DS, '\\', $controller);

                if (class_exists($className)) {
                    $methods = get_this_class_methods($className);
                    if ($methods) {
                        $path = explode('Controller\\', $className)[1];
                        $path = str_replace_last('Controller', '', $path);
                        $path = str_replace('\\', '/', $path);

                        foreach ($methods as $method) {
                            if (!str_starts_with($method, '_')) {
                                if ($aco->add("{$path}/{$method}")) {
                                    $added[] = "{$plugin['name']}/{$path}/{$method}";
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($sync) {
            $aco->Acos->recover();
            $existingPaths = static::paths($for);
            foreach ($existingPaths as $exists) {
                if (!in_array($exists, $added)) {
                    $aco->remove($exists);
                }
            }
            $validLeafs = $aco->Acos
                ->find()
                ->select(['id'])
                ->where(['id NOT IN' => $aco->Acos
                    ->find()
                    ->select(['parent_id'])
                    ->where(['parent_id IS NOT' => null])
                    ->all()
                    ->extract('parent_id')
                    ->toArray()
                ])
                ->all();
            $aco->Acos->Permissions->deleteAll([
                'aco_id NOT IN' => $validLeafs->extract('id')->toArray()
            ]);
        }

        return true;
    }

    /**
     * Gets a list of existing ACO paths for the given plugin, or the entire list
     * if no plugin is given.
     *
     * @param string $for Optional plugin name. e.g. `Taxonomy`
     * @return array All registered ACO paths
     */
    public static function paths($for = null)
    {
        if ($for !== null) {
            try {
                $info = Plugin::info($for);
                $for = $info['name'];
            } catch (\Exception $e) {
                return [];
            }
        }

        $cacheKey = "paths({$for})";
        $paths = static::cache($cacheKey);

        if ($paths === null) {
            $paths = [];
            $aco = new AcoManager('__dummy__');
            $aco->loadModel('User.Acos');
            $leafs = $aco->Acos
                ->find()
                ->select(['id'])
                ->where([
                    'Acos.id NOT IN' => $aco->Acos
                        ->find()
                        ->select(['parent_id'])
                        ->where(['parent_id IS NOT' => null])
                ])
                ->all();

            foreach ($leafs as $leaf) {
                $path = $aco->Acos
                    ->find('path', ['for' => $leaf->id])
                    ->extract('alias')
                    ->toArray();
                $path = implode('/', $path);

                if ($for === null ||
                    ($for !== null && str_starts_with($path, "{$for}/"))
                ) {
                    $paths[] = $path;
                }
            }
            static::cache($cacheKey, $paths);
        }

        return $paths;
    }

    /**
     * Sanitizes the given ACO path.
     *
     * This methods can return an array with the following keys if `$string` option
     * is set to false:
     *
     * - `plugin`: The name of the plugin being managed by this class.
     * - `prefix`: ACO prefix, for example `Admin` for controller within /Controller/Admin/
     *    it may be empty, if not prefix is found.
     * - `controller`: Controller name. e.g.: `MySuperController`
     * - `action`: Controller's action. e.g.: `mini_action`, `index` by default
     *
     * For example:
     *
     *     `Admin/Users/`
     *
     * Returns:
     *
     * - plugin: YourPlugin
     * - prefix: Admin
     * - controller: Users
     * - action: index
     *
     * Where "YourPlugin" is the plugin name passed to this class's constructor.
     *
     * @param string $aco An ACO path to parse
     * @param bool $string Indicates if it should return a string format path (/Controller/action)
     * @return bool|array|string An array as described above or false if an invalid $aco was given
     */
    protected function _parseAco($aco, $string = true)
    {
        $aco = preg_replace('/\/{2,}/', '/', trim($aco, '/'));
        $parts = explode('/', $aco);
        if (!$parts) {
            return false;
        }

        if (count($parts) === 1) {
            $controller = Inflector::camelize($parts[0]);

            return [
                'prefix' => '',
                'controller' => $controller,
                'action' => 'index',
            ];
        }

        $prefixes = $this->_routerPrefixes();
        $prefix = Inflector::camelize($parts[0]);
        if (!in_array($prefix, $prefixes)) {
            $prefix = '';
        } else {
            array_shift($parts);
        }

        if (count($parts) == 2) {
            list($controller, $action) = $parts;
        } else {
            $controller = array_shift($parts);
            $action = 'index';
        }

        $plugin = $this->_pluginName;
        $result = compact('plugin', 'prefix', 'controller', 'action');

        if ($string) {
            $result = implode('/', array_values($result));
            $result = str_replace('//', '/', $result);
        }

        return $result;
    }

    /**
     * Gets a CamelizedList of all existing router prefixes.
     *
     * @return array
     */
    protected function _routerPrefixes()
    {
        $cache = static::cache('_routerPrefixes');
        if (!$cache) {
            $prefixes = [];
            foreach (Router::routes() as $route) {
                if (empty($route->defaults['prefix'])) {
                    continue;
                } else {
                    $prefix = Inflector::camelize($route->defaults['prefix']);
                    if (!in_array($prefix, $prefixes)) {
                        $prefixes[] = $prefix;
                    }
                }
            }

            $cache = static::cache('_routerPrefixes', $prefixes);
        }

        return $cache;
    }
}
