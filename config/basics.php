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
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Event\EventManager;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Error\Debugger;
use Cake\Utility\Inflector;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use QuickApps\Core\Plugin;

if (!function_exists('snapshot')) {
    /**
     * Stores some bootstrap-handy information into a persistent file.
     *
     * Information is stored in `TMP/snapshot.php` file, it contains
     * useful information such as enabled languages, content types slugs, installed
     * plugins, etc.
     *
     * You can read this information using `Configure::read()` as follow:
     *
     *     Configure::read('QuickApps.<option>');
     *
     * Or using the `quickapps()` global function:
     *
     *     quickapps('<option>');
     *
     * @return void
     */
    function snapshot() {
        if (Cache::config('default')) {
            Cache::clear(false, 'default');
        }

        if (Cache::config('_cake_core_')) {
            Cache::clear(false, '_cake_core_');
        }

        if (Cache::config('_cake_model_')) {
            Cache::clear(false, '_cake_model_');
        }

        $corePath = normalizePath(ROOT);
        $snapshot = [
            'version' => null,
            'node_types' => [],
            'plugins' => [],
            'options' => [],
            'languages' => []
        ];

        if (file_exists(ROOT . '/VERSION.txt')) {
            $versionFile = file(ROOT . '/VERSION.txt');
            $snapshot['version'] = trim(array_pop($versionFile));
        } else {
            die('Missing file: VERSION.txt');
        }

        if (ConnectionManager::config('default')) {
            if (!TableRegistry::exists('SnapshotPlugins')) {
                $PluginTable = TableRegistry::get('SnapshotPlugins', ['table' => 'plugins']);
            } else {
                $PluginTable = TableRegistry::get('SnapshotPlugins');
            }

            if (!TableRegistry::exists('SnapshotNodeTypes')) {
                $NodeTypesTable = TableRegistry::get('SnapshotNodeTypes', ['table' => 'node_types']);
            } else {
                $NodeTypesTable = TableRegistry::get('SnapshotNodeTypes');
            }

            if (!TableRegistry::exists('SnapshotLanguages')) {
                $LanguagesTable = TableRegistry::get('SnapshotLanguages', ['table' => 'languages']);
            } else {
                $LanguagesTable = TableRegistry::get('SnapshotLanguages');
            }

            if (!TableRegistry::exists('SnapshotOptions')) {
                $OptionsTable = TableRegistry::get('SnapshotOptions', ['table' => 'options']);
            } else {
                $OptionsTable = TableRegistry::get('SnapshotOptions');
            }

            $PluginTable->schema(['value' => 'serialized']);
            $OptionsTable->schema(['value' => 'serialized']);

            $plugins = $PluginTable->find()
                ->select(['name', 'package', 'status'])
                ->order([
                    'ordering' => 'ASC',
                    'name' => 'ASC',
                ])
                ->all();
            $nodeTypes = $NodeTypesTable->find()
                ->select(['slug'])
                ->all();
            $languages = $LanguagesTable->find()
                ->where(['status' => 1])
                ->order(['ordering' => 'ASC'])
                ->all();
            $options = $OptionsTable->find()
                ->select(['name', 'value'])
                ->where(['autoload' => 1])
                ->all();

            foreach ($nodeTypes as $nodeType) {
                $snapshot['node_types'][] = $nodeType->slug;
            }

            foreach ($options as $option) {
                $snapshot['options'][$option->name] = $option->value;
            }

            foreach ($languages as $language) {
                // mimics Locale\Mode\Entity\Language
                $country = explode('-', $language->code);
                $country = isset($country[1]) ? strtoupper($country[1]) : strtoupper($country[0]);
                $iso = strtolower(explode('-', $language->code)[0]);

                $snapshot['languages'][$language->code] = [
                    'name' => $language->name,
                    'code' => $language->code,
                    'iso' => $iso,
                    'country' => $country,
                    'direction' => $language->direction,
                    'icon' => $language->icon,
                ];
            }
        } else {
            $plugins = [];
            foreach (Plugin::scan() as $plugin => $path) {
                $plugins[] = new Entity([
                    'name' => $plugin,
                    'status' => true,
                    'package' => (is_dir("{$corePath}/plugins/{$plugin}") ? 'quickapps-plugins' : 'unknow-package'),
                ]);
            }
        }

        foreach ($plugins as $plugin) {
            $pluginPath = false;

            if (isset(Plugin::scan()[$plugin->name])) {
                $pluginPath = Plugin::scan()[$plugin->name];
            }

            if ($pluginPath === false || !file_exists($pluginPath . '/composer.json')) {
                Debugger::log(sprintf('Plugin "%s" was found in DB but QuickApps CMS was unable to locate its directory in the file system or its "composer.json" file.', $plugin->name));
                continue;
            }

            $eventsPath = "{$pluginPath}/src/Event/";
            $isCore = strpos($pluginPath, $corePath) !== false;
            $isTheme = str_ends_with($plugin->name, 'Theme');
            $status = $isCore ? true : $plugin->status;
            $eventListeners = [];

            if (is_dir($eventsPath)) {
                $Folder = new Folder($eventsPath);
                foreach ($Folder->read(false, false, true)[1] as $classFile) {
                    $className = basename(preg_replace('/\.php$/', '', $classFile));
                    $namespace = "{$plugin->name}\Event\\";
                    $eventListeners[$namespace . $className] = [
                        'namespace' => $namespace,
                        'path' => dirname($classFile),
                    ];
                }
            }

            $humanName = Inflector::humanize(Inflector::underscore($plugin->name));
            if ($isTheme) {
                $humanName = trim(str_replace_last('Theme', '', $humanName));
            }

            $snapshot['plugins'][$plugin->name] = [
                'name' => $plugin->name,
                'human_name' => $humanName,
                'package' => $plugin->package,
                'isTheme' => $isTheme,
                'isCore' => $isCore,
                'hasHelp' => file_exists($pluginPath . '/src/Template/Element/Help/help.ctp'),
                'hasSettings' => file_exists($pluginPath . '/src/Template/Element/settings.ctp'),
                'eventListeners' => $eventListeners,
                'status' => $status,
                'path' => $pluginPath,
            ];
        }

        Configure::write('QuickApps', $snapshot);
        Configure::dump('snapshot', 'QuickApps', ['QuickApps']);
    }

}

if (!function_exists('normalizePath')) {
    /**
     * Normalizes the given file system path, makes sure that all DIRECTORY_SEPARATOR
     * are the same, so you won't get a mix of "/" and "\" in your paths.
     *
     * ### Example:
     *
     *     normalizePath('/some/path\to/some\\thing\about.zip');
     *     // output:
     *     /some/path/to/some/thing/about.zip
     *
     * You can indicate which "directory separator" symbol to use using the second
     * argument:
     *
     *     normalizePath('/some/path\to//some\thing\about.zip', '\');
     *     // output:
     *     \some\path\to\some\thing\about.zip
     *
     * By defaults uses DIRECTORY_SEPARATOR as symbol.
     * 
     * @param string $path The path to normalize
     * @param string $ds Directory separator character, defaults to DIRECTORY_SEPARATOR
     * @return string Normalized $path
     */
    function normalizePath($path, $ds = DIRECTORY_SEPARATOR) {
        $path = str_replace(['/', '\\'], $ds, $path);
        return str_replace("{$ds}{$ds}", $ds, $path);
    }

}

if (!function_exists('quickapps')) {
    /**
     * Shortcut for reading QuickApps's snapshot configuration.
     *
     * For example, `quickapps('variables');` maps to 
     * `Configure::read('QuickApps.variables');`. If this function is used with no
     * arguments, `quickapps()`, the entire snapshot will be returned.
     *
     * @param string $key The key to read from snapshot, or null to read the whole
     *  snapshot's info
     * @return mixed
     */
    function quickapps($key = null) {
        if ($key !== null) {
            return Configure::read("QuickApps.{$key}");
        }
        return Configure::read('QuickApps');
    }

}

if (!function_exists('option')) {
    /**
     * Shortcut for getting an option value from "options" DB table.
     *
     * The second arguments, $default,  is used as default value to return if no
     * value is found. If not value is found and not default values was given this
     * function will return `false`.
     *
     * **Example:**
     *
     *     option('site_slogan');
     * 
     * @param string $name Name of the option to retrieve. e.g. `front_theme`,
     *  `default_language`, `site_slogan`, etc
     * @param mixed $default The default value to return if no value is found
     * @return mixed Current value for the specified option. If the specified option
     *  does not exist, returns boolean FALSE
     */
    function option($name, $default = false) {
        if (Configure::check("QuickApps.options.{$name}")) {
            return Configure::read("QuickApps.options.{$name}");
        }

        if (ConnectionManager::config('default')) {
            $option = TableRegistry::get('Options')
                ->find()
                ->where(['Options.name' => $name])
                ->first();
            if ($option) {
                return $option->value;
            }
        }

        return $default;
    }

}

if (!function_exists('listeners')) {
    /**
     * Returns a list of all registered event listeners in the system.
     * 
     * @return array
     */
    function listeners() {
        $class = new \ReflectionClass(EventManager::instance());
        $property = $class->getProperty('_listeners');
        $property->setAccessible(true);
        $listeners = array_keys($property->getValue(EventManager::instance()));
        return $listeners;
    }

}

if (!function_exists('pluginName')) {
    /**
     * Used to extract plugin names from composer's package names.
     *
     * ### Example:
     *
     *     pluginName('quickapps/my-super-plugin');
     *     // returns: MySuperPlugin
     *
     * Package names must follow the "author/app-name" pattern, there are two
     * "especial" composer's package names which are handled differently:
     *
     * - `php`: Will return "\_\_PHP\_\_"
     * - `quickapps/cms`: Will return "\_\_QUICKAPPS\_\_"
     * - `cakephp/cakephp`: Will return "\_\CAKEPHP\_\_"
     *
     * @param string $name Package name. e.g. author-name/package-name
     * @return string
     */
    function pluginName($name) {
        $name = strtolower($name);
        if ($name === 'php') {
            return '__PHP__';
        } elseif ($name === 'quickapps/cms') {
            return '__QUICKAPPS__';
        } elseif ($name === 'cakephp/cakephp') {
            return '__CAKEPHP__';
        } elseif (strpos($name, '/') === false) {
            return ''; // invalid
        }
        $parts = explode('/', $name);
        return Inflector::camelize(str_replace('-', '_', end($parts)));
    }

}

if (!function_exists('exportFixtures')) {
    /**
     * Export entire database to PHP fixtures.
     *
     * All generated PHP files will be placed in `/tmp/Fixture/` directory.
     * 
     * @param array $ignoreRecords List of table names to ignore, records will not
     *  be exported to the resulting PHP file (only its schema)
     * @return bool
     */
    function exportFixtures($ignoreRecords = []) {
        $db = ConnectionManager::get('default');
        $db->connect();
        $schemaCollection = $db->schemaCollection();
        $tables = $schemaCollection->listTables();

        if (file_exists(TMP . 'fixture/')) {
            $dst = new Folder(TMP . 'fixture/');
            $dst->delete();
        } else {
            new Folder(TMP . 'fixture/', true);
        }

        foreach ($tables as $table) {
            $Table = TableRegistry::get($table);
            $Table->behaviors()->reset();
            $fields = ['_constraints' => []];
            $records = [];

            foreach ($Table->schema()->columns() as $column) {
                $fields[$column] = $Table->schema()->column($column);
            }

            foreach ($Table->schema()->constraints() as $constraint) {
                $fields['_constraints'][$constraint] = $Table->schema()->constraint($constraint);
            }

            // we need raw data for time, no Time objects
            foreach ($Table->schema()->columns() as $column) {
                $type = $Table->schema()->columnType($column);
                if (in_array($type, ['date', 'datetime', 'time'])) {
                    $Table->schema()->columnType($column, 'string');
                }
            }

            if (!in_array($table, $ignoreRecords)) {
                $rows = $Table->find('all');
                foreach ($rows as $row) {
                    $records[] = $row->toArray();
                }
            }

            $className = Inflector::camelize($table) . 'Fixture';
            $fixture = "<?php
class {$className} {

    public \$fields = " . var_export($fields, true) . ";

    public \$records = " . var_export($records, true) . ";

}

";
            $file = new File(TMP . "fixture/{$className}.php", true);
            $file->write($fixture, 'w', true);
        }

        return true;
    }
}

if (!function_exists('array_move')) {
    /**
     * Moves up or down the given element by index from a list array of elements.
     *
     * If item could not be moved, the original list will be returned. Valid values
     * for $direction are `up` or `down`.
     *
     * ### Example:
     *
     *     array_move(['a', 'b', 'c'], 1, 'up');
     *     // returns: ['a', 'c', 'b']
     *
     * @param array $list Numeric indexed array list of elements
     * @param integer $index The index position of the element you want to move
     * @param string $direction Direction, 'up' or 'down'
     * @return array Reordered original list.
     */
    function array_move(array $list, $index, $direction) {
        $maxIndex = count($list) - 1;
        if ($direction == 'down') {
            if (0 < $index && $index <= $maxIndex) {
                $item = $list[$index];
                $list[$index] = $list[$index - 1];
                $list[$index - 1] = $item;
            }
        } elseif ($direction == 'up') {
            if ($index >= 0 && $maxIndex > $index) {
                $item = $list[$index];
                $list[$index] = $list[$index + 1];
                $list[$index + 1] = $item;
                return $list;
            }
        }

        return $list;
    }

}

if (!function_exists('php_eval')) {
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
     * ### Usage:
     *
     *     echo php_eval('<?php return "Hello {$world}!"; ?>', ['world' => 'WORLD']);
     *     // output: Hello WORLD
     *
     * @param string $code The code to evaluate
     * @param array $args Array of arguments as `key` => `value` pairs, evaluated
     *  code can access this variables
     * @return string
     */
    function php_eval($code, $args = []) {
        ob_start();
        extract($args);
        print eval('?>' . $code);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

}

if (!function_exists('get_this_class_methods')) {
    /**
     * Return only the methods for the given object. It will strip out inherited
     * methods.
     *
     * @param string $class Class name
     * @return array List of methods
     */
    function get_this_class_methods($class) {
        $primary = get_class_methods($class);

        if ($parent = get_parent_class($class)) {
            $secondary = get_class_methods($parent);
            $methods = array_diff($primary, $secondary);
        } else {
            $methods = $primary;
        }

        return $methods;
    }

}

if (!function_exists('str_replace_once')) {
    /**
     * Replace the first occurrence only.
     *
     * ### Example:
     *
     *     echo str_replace_once('A', 'a', 'AAABBBCCC');
     *     // out: aAABBBCCC
     *
     * @param string $search The value being searched for
     * @param string $replace The replacement value that replaces found search value
     * @param string $subject The string being searched and replaced on
     * @return string A string with the replaced value
     */
    function str_replace_once($search, $replace, $subject) {
        if (strpos($subject, $search) !== false) {
            return substr_replace($subject, $replace, strpos($subject, $search), strlen($search));
        }

        return $subject;
    }

}

if (!function_exists('str_replace_last')) {
    /**
     * Replace the last occurrence only.
     *
     * ### Example:
     *
     *     echo str_replace_once('A', 'a', 'AAABBBCCC');
     *     // out: AAaBBBCCC
     *
     * @param string $search The value being searched for
     * @param string $replace The replacement value that replaces found search value
     * @param string $subject The string being searched and replaced on
     * @return string A string with the replaced value
     */
    function str_replace_last($search, $replace, $subject) {
        $pos = strrpos($subject, $search);
        if($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }
        return $subject;
    }

}

if (!function_exists('str_starts_with')) {
    /**
     * Check if $haystack string starts with $needle string.
     *
     * ### Example:
     *
     *     str_starts_with('lorem ipsum', 'lo'); // true
     *     str_starts_with('lorem ipsum', 'ipsum'); // false
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function str_starts_with($haystack, $needle) {
        return
            $needle === '' ||
            strpos($haystack, $needle) === 0;
    }

}

if (!function_exists('str_ends_with')) {
    /**
     * Check if $haystack string ends with $needle string.
     *
     * ### Example:
     *
     *     str_ends_with('lorem ipsum', 'm'); // true
     *     str_ends_with('dolorem sit amet', 'at'); // false
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    function str_ends_with($haystack, $needle) {
        return
            $needle === '' ||
            substr($haystack, - strlen($needle)) === $needle;
    }

}
