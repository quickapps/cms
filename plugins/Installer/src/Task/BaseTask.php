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
namespace Installer\Task;

use Cake\Core\InstanceConfigTrait;
use Cake\Error\FatalErrorException;
use Cake\Event\EventManager;
use Cake\Model\ModelAwareTrait;
use Cake\Utility\Folder;
use Installer\Utility\PackageManager;
use QuickApps\Core\HookTrait;
use User\Utility\AcoManager;

/**
 * Base class for tasks.
 *
 */
abstract class BaseTask {

	use InstanceConfigTrait;
	use HookTrait;
	use ModelAwareTrait;

/**
 * Default config
 *
 * @var array
 */
	protected $_defaultConfig = [];

/**
 * List of error messages.
 * 
 * @var array
 */
	protected $_errors = [];

/**
 * Holds the name of the plugin which is running the task.
 * 
 * @var string
 */
	protected $_pluginName = null;

/**
 * Constructor.
 *
 * @return void
 */
	public function __construct($config = []) {
		if (function_exists('ini_set')) {
			ini_set('max_execution_time', 300);
		} elseif (function_exists('set_time_limit')) {
			set_time_limit(300);
		}

		$this->config($config);
		$this->modelFactory('Table', ['Cake\ORM\TableRegistry', 'get']);
		$this->loadModel('System.Plugins');
	}

/**
 * Starts this task.
 * 
 * @return bool True if task executed correctly
 * @throws \Cake\Error\FatalErrorException When task is started before task
 * handler has specified the plugin being managed
 */
	final public function run() {
		$this->init();
		if (!$this->_pluginName) {
			throw new FatalErrorException(__d('installer', 'Internal error ({0}), task cannot be run if no plugin was set before using "_plugin()".', get_called_class()));
		}
		return $this->start();
	}

/**
 * This is where task should initialize all what it needs
 * before it gets started.
 *
 * This method is automatically executed before "run()". Here is where you
 * should indicate which plugin is being handled using the `_plugin()` method.
 * 
 * @return void
 */
	abstract protected function init();

/**
 * This is the main method of every task.
 *
 * It cannot be directly executed, it can only be accessed using "run()".
 * 
 * @return bool
 */
	abstract protected function start();

/**
 * Sets the plugin name being handled.
 *
 * A plugin name must be set before starting the task using "run()" method.
 * 
 * @param string $pluginName
 * @return string The plugin name just set
 */
	final protected function _plugin($pluginName) {
		return $this->_pluginName = $pluginName;
	}

/**
 * Registers a new option in the "options" DB table.
 *
 * IMPORTANT: option names are automatically prefixed with "<PluginName>.",
 * where `<PluginName>` is the name of the plugin running this task. For instance,
 * if we are installing a new theme named "DarkBlue":
 *
 *     $this->addOption('background_color', '#000');
 *
 * Will add the following row to the "options" table:
 *
 * - name: DarkBlue.background_color
 * - value: #000
 * - autoload: true
 * 
 * @param string $name Option name, will be automatically prefixed with "<PluginName>."
 * @param mixed $value Any information this plugin needs for this option
 * @param bool $autoload True if this option should be loaded on bootstrap. Defaults to true
 * @return mixed
 */
	final public function addOption($name, $value, $autoload = true) {
		if (!$this->_pluginName) {
			throw new FatalErrorException(__d('installer', 'Internal error ({0}), cannot use addOption() before "_plugin()".', get_called_class()));
		}

		$this->loadModel('System.Options');
		$name = !str_starts_with($name, "{$this->_pluginName}.") ? "{$this->_pluginName}.{$name}" : $name;
		$option = $this->Options->newEntity(compact('name', 'value', 'autoload'));
		return $this->Options->save($option);
	}

/**
 * Gets an instance of AcoManager.
 * 
 * @return \User\Utility\AcoManager
 */
	final public function aco() {
		if (!$this->_pluginName) {
			throw new FatalErrorException(__d('installer', 'Internal error ({0}), illegal access to AcoManager before using "_plugin()".', get_called_class()));
		}
		return new AcoManager($this->_pluginName);
	}

/**
 * Sets a configuration value.
 * 
 * @param string $key
 * @param mixed $value
 * @return \Installer\Task\BaseTask
 */
	final public function configure($key, $value = null) {
		$this->config($key, $value);
		return $this;
	}

/**
 * Creates a new instance of this class, so we can chain multiple
 * installation/upgrade tasks.
 *
 * This allow plugins to start a new installation "thread" on callbacks
 * (beforeInstall, afterInstall, etc), for instance:
 *
 *     // MyPluginHook.php
 *     public function beforeInstall($event) {
 *         // subject is the instance of installer that fired the event
 *         $installDependency = $event->subject
 *             ->newTask('install', ['active' => false])
 *             ->download('http://example.com/some-package/this/plugins/depends-on.zip')
 *             ->run();
 *         // if false will halt the whole installation
 *         return $installDependency;
 *     }
 *
 * @param string $task Type of task
 * @param array $options Array of options for the task
 * @return \Installer\Task\BaseTask New instance of this class
 */
	final public function newTask($task, $options = []) {
		return PackageManager::task($task, $options);
	}

/**
 * Registers a new error message, or a set of messages at once.
 * 
 * @param array|string $message A single message or an array of messages
 * @return void
 */
	final protected function error($message) {
		if (is_string($message)) {
			$message = [$message];
		}
		foreach ($message as $m) {
			$this->_errors[] = $m;
		}
	}

/**
 * Gets a list of all errors during installation.
 * 
 * @return array
 */
	final public function errors() {
		return $this->_errors;
	}

/**
 * Recursively checks if the given directory (and its content) can be deleted.
 *
 * This method automatically registers an error message if validation fails.
 * 
 * @param string $path Directory to check
 * @return bool
 */
	final protected function canBeDeleted($path) {
		if (!file_exists($path) || !is_dir($path)) {
			$this->error(__d('installer', "Plugin's directory was not found: ", $path));
			return false;
		}

		$folder = new Folder($path);
		$content = $folder->tree();
		$notWritable = [];

		foreach ($content as $foldersOrFiles) {
			foreach ($foldersOrFiles as $element) {
				if (!is_writable($element)) {
					$notWritable[] = $element;
				}
			}
			
		}

		if (!empty($notWritable)) {
			$lis = array_map(function($item) {
					return "<li>{$item}</li>";
			}, $notWritable);

			$ul = '<ul>' . implode("\n", $lis) . '</ul>';
			$this->error(__d('installer', "Some plugin's files or directories cannot be removed from your server, please check write permissions: <br/> {0}", $ul));
			return false;
		}

		return true;
	}

/**
 * Loads and registers plugin's event listeners classes so plugins may respond
 * to `beforeInstall`, `afterInstall`, etc.
 *
 * @param string $path Where to look for listener classes
 * @return void
 */
	final protected function attachListeners($path) {
		global $classLoader;

		if (!$this->_pluginName) {
			throw new FatalErrorException(__d('installer', 'Internal error ({0}), attachListeners() cannot be used if no plugin was set before using "_plugin()".', get_called_class()));
		}

		if (file_exists($path) && is_dir($path)) {
			$EventManager = EventManager::instance();
			$eventsFolder = new Folder($path);

			foreach ($eventsFolder->read(false, false, true)[1] as $classPath) {
				$className = preg_replace('/\.php$/i', '', basename($classPath));
				$namespace = "{$this->_pluginName}\Event\\";
				$classLoader->addPsr4($namespace, dirname($classPath), true);
				$fullClassName = $namespace . $className;

				if (class_exists($fullClassName)) {
					$this->_listeners[] = new $fullClassName;
					$EventManager->attach(end($this->_listeners));
				}
			}
		}
	}

}
