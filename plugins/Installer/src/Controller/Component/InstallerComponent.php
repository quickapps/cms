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
namespace Installer\Controller\Component;

use Cake\Controller\Component;
use Installer\Task\TaskManager;

/**
 * Plugin package handler.
 *
 * ## Basic Usage:
 *
 *     $task = $this->Installer
 *         ->task('install', ['active' => false])
 *         ->download('http://example.com/package.zip');
 *     // ....
 *     if ($task->run()) {
 *         // success
 *     }  else {
 *         // error
 *         $errors = $task->errors();
 *     }
 */
class InstallerComponent extends Component
{

    /**
     * Creates a new install/update task.
     *
     * @param string $task Type of task; 'install', 'update', 'uninstall', etc
     * @param array $args Additional options for the task handler
     * @return \Installer\Task\BaseTask Instance of the request task
     */
    public function task($task, $args = [])
    {
        return TaskManager::task($task, $args);
    }
}
