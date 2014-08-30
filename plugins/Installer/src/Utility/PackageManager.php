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

use Cake\Error\FatalErrorException;
use Installer\Task\BaseTask;

/**
 * Package tasks factory.
 *
 * ### Basic Usage:
 *
 *     $task = PackageManager::task('install', ['active' => true])->download();
 *     if (!$task->run()) {
 *         $errors = $task->errors();
 *     }
 *
 * Severals callbacks are triggered during each task, for example "install" task
 * may trigger "beforeInstall" & "afterInstall" events which can be caught by the
 * plugin being installed as follow. These events listeners are allowed to run their
 * own tasks, for example:
 *
 *     public function beforeInstall($event) {
 *         // get the task that triggered this event
 *         $runningTask = $event->subject;
 *
 *         // create a new task "thread"
 *         $dependency = $runningTask
 *             ->newTask('install', ['activate' => true, 'callbacks' => false])
 *             ->download('http://example.com/package-this/plugin-depends-on.zip');
 *
 *         // execute the new task
 *         $success = $dependency->run();
 *
 *         // if fails we stop the parent task by returning FALSE
 *         if (!$success) {
 *             // merge error messages
 *             $runningTask->error($dependency->errors());
 *             return false;
 *         }
 *
 *         // if it's OK parent task can continue
 *         return true;
 *     }
 */
class PackageManager {

/**
 * Holds a list of registered and valid task classes.
 *
 * More tasks can be attached using `registerTask()` method.
 * 
 * @var array
 */
	protected static $_tasks = [
		'install' => '\Installer\Task\InstallTask',
		'toggle' => '\Installer\Task\ToggleTask',
		'uninstall' => '\Installer\Task\UninstallTask',
		'update' => '\Installer\Task\UpdateTask',
		'activate_theme' => '\Installer\Task\ThemeActivatorTask',
	];

/**
 * Constructor.
 *
 * @return \Installer\Utility\TaskBase
 * @throws \Cake\Error\FatalErrorException When invalid task is given, or when
 * the task class does not extends "BaseTask".
 */
	public static function task($task, $options = []) {
		if (function_exists('ini_set')) {
			ini_set('max_execution_time', 300);
		} elseif (function_exists('set_time_limit')) {
			set_time_limit(30);
		}

		if (!isset(static::$_tasks[$task])) {
			throw new FatalErrorException(__d('installer', 'Invalid task'));
		}

		$handler = static::$_tasks[$task];
		if (is_callable($handler)) {
			return $handler($options);
		}
		$handler = new $handler($options);

		if ($handler instanceof BaseTask) {
			return $handler;
		}

		throw new FatalErrorException(__d('installer', 'Invalid task object, it must extend "BaseTask" class.'));
	}

/**
 * Registers a new task handler or overwrites existing one.
 *
 * ### Usage:
 *
 *     PackageManager::registerTask('package-validator', function ($options) {
 *         return 'Validator says: ' . $options['message'];
 *     });
 *
 *     $task = PackageManager::task('package-validator', ['message' => 'hello world!']);
 *     echo $task;
 *     // out: Validator says: hello world!
 * 
 * @param string $name name of the task, for later use with `task()` method
 * @param string|callable $handler A string of a valid class name extending
 *  `Installer\Task\BaseTask`, or a callable function.
 *   e.g. `\MyNameSpace\MySuperTask` (must extend BaseTask)
 * @return void
 */
	public static function registerTask($name, $handler) {
		static::$_taks[$name] = $handler;
	}

}
