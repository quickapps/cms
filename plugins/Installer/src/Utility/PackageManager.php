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
 * Package tasks handler.
 *
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
