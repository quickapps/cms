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
use Cake\Event\EventManager;
use Cake\Network\Session;
use Cake\Routing\Router;
use Cake\Utility\Debugger;
use Cake\Utility\Folder;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\ORM\TableRegistry;
use User\Model\Entity\User;

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
 * Available options are:
 *
 * - `node_types`: List of available content type slugs. e.g. ['article', 'page', ...].
 * - `plugins`: Array of plugin information indexed by Plugin Name.
 * - `options`: A set of useful environment variables stored in `options` DB table.
 *
 * @param array $mergeWith Array to merge with snapshot array
 * @return void
 */
function snapshot($mergeWith = []) {
	Cache::clear(false, 'default');
	Cache::clear(false, '_cake_core_');
	Cache::clear(false, '_cake_model_');
	$snapshot = [
		'version' => null,
		'node_types' => [],
		'plugins' => [],
		'options' => [],
		'languages' => []
	];

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

	$corePath = str_replace(['/', DS], '/', ROOT);
	foreach ($plugins as $plugin) {
		$pluginPath = false;

		foreach (App::path('Plugin') as $path) {
			if (is_dir($path . $plugin->name)) {
				$pluginPath = $path . $plugin->name;
				break;
			}
		}

		$pluginPath = str_replace(['/', DS], '/', $pluginPath);
		if ($pluginPath === false || !file_exists($pluginPath . '/composer.json')) {
			Debugger::log(sprintf('Plugin "%s" was found in DB but QuickApps CMS was unable to locate its directory in the file system or its "composer.json" file.', $plugin->name));
			continue;
		}

		$eventsPath = "{$pluginPath}/src/Event/";
		$isCore = strpos($pluginPath, $corePath) !== false;
		$isTheme = str_ends_with($plugin->name, 'Theme');
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
			'status' => $plugin->status,
			'path' => str_replace(['/', DS], '/', $pluginPath),
		];
	}

	if (!empty($mergeWith)) {
		$snapshot = Hash::merge($snapshot, $mergeWith);
	}

	if (file_exists(ROOT . '/VERSION.txt')) {
		$versionFile = file(ROOT . '/VERSION.txt');
		$snapshot['version'] = trim(array_pop($versionFile));
	} else {
		die('Missing file: VERSION.txt');
	}

	Configure::write('QuickApps', $snapshot);
	Configure::dump('snapshot.php', 'QuickApps', ['QuickApps']);
}

/**
 * Shortcut for reading QuickApps's snapshot configuration.
 *
 * For example, `quickapps('variables');` maps to  `Configure::read('QuickApps.variables');`
 *
 * @param string $key
 * @return mixed
 */
	function quickapps($key) {
		return Configure::read("QuickApps.{$key}");
	}

/**
 * Gets current user (logged in or not) as an entity.
 *
 * @return \User\Model\Entity\User
 */
	function user() {
		if (Router::getRequest()->is('user.logged')) {
			$properties = (new Session())->read('user');
			$properties['roles'] = array_unique(array_merge($properties['roles'], ROLE_ID_AUTHENTICATED));
		} else {
			$properties = [
				'id' => null,
				'name' => __d('user', 'Anonymous'),
				'username' => __d('user', 'anonymous'),
				'email' => __d('user', '(no email)'),
				'locale' => null,
				'roles' => [ROLE_ID_ANONYMOUS],
			];
		}

		static $user = null;
		if ($user === null) {
			$user = new User($properties);
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

		$option = TableRegistry::get('Options')
			->find()
			->where(['Options.name' => $name])
			->first();

		if ($option) {
			return $option->value;
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
 * Used to convert composer-like names to plugin names.
 *
 * ### Example:
 *
 *     pluginName('quickapps/my-super-plugin');
 *     // returns: MySuperPlugin
 * 
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
