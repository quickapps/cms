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
namespace Installer\Utility;

use Cake\Core\InstanceConfigTrait;
use Cake\Error\FatalErrorException;
use Cake\Event\EventManager;
use Cake\Model\ModelAwareTrait;
use Cake\Utility\Folder;
use Installer\Utility\InstallTask;
use Installer\Utility\PackageManager;
use Installer\Utility\UpdateTask;
use QuickApps\Core\HookTrait;

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
			set_time_limit(30);
		}
		$this->config($config);
		$this->modelFactory('Table', ['Cake\ORM\TableRegistry', 'get']);
		$this->loadModel('System.Plugins');
	}

/**
 * Starts this task.
 * 
 * @return mixed
 */
	abstract public function run();

/**
 * Sets a configuration value.
 * 
 * @param string $key
 * @param mixed $value
 * @return \Installer\Utility\BaseTask
 */
	public function configure($key, $value = null) {
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
 * @return \Installer\Utility\InstallTask New instance of this class
 */
	public function newTask($task, $options = []) {
		return PackageManager::task($task, $options);
	}

/**
 * Registers a new error message, or a set of messages at once.
 * 
 * @param array|string $message A single message or an array of messages
 * @return void
 */
	protected function error($message) {
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
	public function errors() {
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
	protected function canBeDeleted($path) {
		if (!file_exists($path) || !is_dir($path)) {
			$this->error(__d('install', "Plugin's directory was not found: ", $path));
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
			$this->error(__d('install', "Some plugin's files or directories cannot be removed from your server, please check write permissions: <br/> {0}", $ul));
			return false;
		}

		return true;
	}

/**
 * Loads and registers plugin's Hook classes so plugins may respond
 * to `beforeInstall`, `afterInstall`, etc.
 *
 * @param string $path Where to look for listener classes
 * @return void
 */
	protected function attachListeners($path) {
		global $classLoader;

		if (file_exists($path) && is_dir($path)) {
			$EventManager = EventManager::instance();
			$eventsFolder = new Folder($path);

			foreach ($eventsFolder->read(false, false, true)[1] as $classPath) {
				$className = preg_replace('/\.php$/i', '', basename($classPath));
				if (str_ends_with($className, 'Hook')) {
					$classLoader->addPsr4('Hook\\', dirname($classPath), true);
					$class = 'Hook\\' . $className;

					if (class_exists($class)) {
						$this->_listeners[] = new $class;
						$EventManager->attach(end($this->_listeners));
					}
				}
			}
		}
	}

}
