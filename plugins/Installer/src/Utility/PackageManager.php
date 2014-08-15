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
use Installer\Utility\InstallTask;
use Installer\Utility\UpdateTask;

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
 * Constructor.
 *
 * @return \Installer\Utility\TaskBase
 * @throws \Cake\Error\FatalErrorException When invalid task is given
 */
	public static function task($task, $options = []) {
		if (function_exists('ini_set')) {
			ini_set('max_execution_time', 300);
		} elseif (function_exists('set_time_limit')) {
			set_time_limit(30);
		}

		switch ($task) {
			case 'install':
				$task = new InstallTask($options);
			break;

			case 'uninstall':
				$task = new UninstallTask($options);
			break;

			default:
				throw new FatalErrorException(__d('installer', 'Invalid task'));
			break;
		}

		return $task;
	}

}
