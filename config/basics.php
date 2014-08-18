<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
use Cake\Cache\Cache;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Event\EventManager;
use Cake\I18n\I18n;
use Cake\Network\Session;
use Cake\Routing\Router;
use Cake\Utility\Debugger;
use Cake\Utility\Folder;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use User\Model\Entity\UserSession;
use User\Utility\AcoManager;
use QuickApps\Core\Plugin;

/**
 * Stores some bootstrap-handy information into a persistent file.
 *
 * Information is stored in `SITE/tmp/snapshot.php` file, it contains
 * useful information such as installed languages, content types slugs, etc.
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
				->order(['ordering' => 'ASC'])
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
				$snapshot['languages'][$language->code] = [
					'name' => $language->name,
					'native' => $language->native,
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
			$events = [
				'hooks' => [],
				'hooktags' => [],
				'fields' => [],
			];

			if (is_dir($eventsPath)) {
				$Folder = new Folder($eventsPath);
				foreach ($Folder->read(false, false, true)[1] as $classFile) {
					$className = basename(preg_replace('/\.php$/', '', $classFile));
					if (str_ends_with($className, 'Field')) {
						$events['fields']['Field\\' . $className] = [
							'namespace' => 'Field\\',
							'path' => dirname($classFile),
						];
					} elseif (str_ends_with($className, 'Hook')) {
						$events['hooks']['Hook\\' . $className] = [
							'namespace' => 'Hook\\',
							'path' => dirname($classFile),
						];
					} elseif (str_ends_with($className, 'Hooktag')) {
						$events['hooktags']['Hooktag\\' . $className] = [
							'namespace' => 'Hooktag\\',
							'path' => dirname($classFile),
						];
					}
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
				'events' => $events,
				'status' => $status,
				'path' => $pluginPath,
			];
		}

		Configure::write('QuickApps', $snapshot);
		Configure::dump('snapshot.php', 'QuickApps', ['QuickApps']);
	}

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
 * You can indicate which "directory separator" symbol to use using the second argument:
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
		$path = str_replace(['/', DS], $ds, $path);
		return str_replace("{$ds}{$ds}", $ds, $path);
	}

/**
 * Shortcut for reading QuickApps's snapshot configuration.
 *
 * For example, `quickapps('variables');` maps to  `Configure::read('QuickApps.variables');`
 * If this function is used with no arguments, `quickapps()`, the entire snapshot will be returned.
 *
 * @param string $key The key to read from snapshot, or null to read the whole snapshot's info
 * @return mixed
 */
	function quickapps($key = null) {
		if ($key !== null) {
			return Configure::read("QuickApps.{$key}");
		}
		return Configure::read('QuickApps');
	}

/**
 * Gets current user (logged in or not) as an entity.
 *
 * @return \User\Model\Entity\UserSession
 */
	function user() {
		if (Router::getRequest()->is('userLoggedIn')) {
			$properties = (new Session())->read('Auth.User');
			foreach ($properties['roles'] as &$role) {
				unset($role['_joinData']);
				$role = new Entity($role);
			}
			$properties['roles'][] = TableRegistry::get('Roles')->get(ROLE_ID_AUTHENTICATED);
		} else {
			$properties = [
				'id' => null,
				'name' => __d('user', 'Anonymous'),
				'username' => __d('user', 'anonymous'),
				'email' => __d('user', '(no email)'),
				'locale' => I18n::defaultLocale(),
				'roles' => [TableRegistry::get('Roles')->get(ROLE_ID_ANONYMOUS)],
			];
		}

		static $user = null;
		if ($user === null) {
			$user = new UserSession($properties);
		}
		return $user;
	}

/**
 * Shortcut for getting an option value from "options" DB table.
 * 
 * @param string $name Name of the option to retrieve. e.g. `front_theme`, `default_language`
 * @param mixed $default The default value to return if no value is found
 * @return mixed Current value for the specified option. If the specified option does not exist, returns boolean FALSE
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

/**
 * Returns a list of all registered event listeners.
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

/**
 * Used to extract plugin names from composer's package names.
 *
 * ### Example:
 *
 *     pluginName('quickapps/my-super-plugin');
 *     // returns: MySuperPlugin
 *
 *
 * Package names must follow the "author/app-name" pattern, there are two
 * "especial" composer's package names which are handled differently:
 *
 * - `php`: Will return "__PHP__"
 * - `quickapps/cms`: Will return "__QUICKAPPS__"
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
		} elseif (strpos($name, '/') === false) {
			return ''; // invalid
		}
		$parts = explode('/', $name);
		return Inflector::camelize(str_replace('-', '_', end($parts)));
	}

/**
 * Moves the given element by index from a list array of elements.
 *
 * @param array $list Numeric indexed array list of elements
 * @param integer $position The index position of the element you want to move
 * @param string $direction Direction, 'up' or 'down'
 * @return array Reordered original list.
 */
	function array_move(array $list, $position, $direction) {
		if ($direction == 'down') {
			if (count($list) - 1 > $position) {
				$b = array_slice($list, 0, $position, true);
				$b[] = $list[$position + 1];
				$b[] = $list[$position];
				$b += array_slice($list, $position + 2, count($list), true);

				return $b;
			} else {
				return $list;
			}
		} elseif ($direction = 'up') {
			if ($position > 0 and $position < count($list)) {
				$b = array_slice($list, 0, ($position - 1), true);
				$b[] = $list[$position];
				$b[] = $list[$position - 1];
				$b += array_slice($list, ($position + 1), count($list), true);

				return $b;
			} else {
				return $list;
			}
		}

		return $list;
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
 * ### Usage:
 *
 *     echo php_eval('<?php return "Hello {$world}!"; ?>', ['world' => 'WORLD']);
 *     // output: Hello WORLD
 *
 * @param string $code The code to evaluate
 * @param array $args Array of arguments as `key` => `value` pairs, evaluated code 
 * can access this variables
 * @return mixed
 */
	function php_eval($code, $args = []) {
		ob_start();
		extract($args);
		print eval('?>' . $code);
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

/**
 * Return only the methods for the given object.  
 * It will strip out inherited methods.
 *
 * @return array List of methods
 */
	function get_this_class_methods($class) {
		$methods = [];
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
 * Replace the first occurrence only.
 *
 * ### Example:
 *
 *     echo str_replace_once('A', 'a', 'AAABBBCCC');
 *     // out: aAABBCCCC
 *
 * @param string $search The value being searched for
 * @param string $replace The replacement value that replaces found search value
 * @param string $subject The string being searched and replaced on
 * @return string A string with the replaced value
 */
function str_replace_once($search, $replace, $subject) {
	if (strpos($subject, $search) !== false) {
		$occurrence = strpos($subject, $search);
		return substr_replace($subject, $replace, strpos($subject, $search), strlen($search));
	}

	return $subject;
}

/**
 * Replace the last occurrence only.
 *
 * ### Example:
 *
 *     echo str_replace_once('A', 'a', 'AAABBBCCC');
 *     // out: AAaBBCCCC
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
