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

use Cake\Utility\Folder;
use Installer\Utility\BaseTask;
use QuickApps\Core\Plugin;

/**
 * Represents a single toggle task.
 *
 * Allows to enable or disable a plugin.
 *
 * ## Basic Usage:
 *
 * Using `InstallerComponent` on any controller:
 * 
 *     $task = $this->Installer
 *         ->task('toggle')
 *         ->enable('MyPlugin');
 *     
 *     // or:
 *     $task = $this->Installer
 *         ->task('toggle')
 *         ->configure('plugin', 'MyPlugin');
 *         
 *     // or:
 *     $task = $this->Installer
 *         ->task('toggle', ['plugin' => 'MyPlugin'])
 *         ->enable();
 *     
 *     if ($task->run()) {
 *         $this->Flash->success('Enabled!');
 *     } else {
 *         $errors = $task->errors();
 *     }
 */
class ToggleTask extends BaseTask {

/**
 * Default config
 *
 * These are merged with user-provided configuration when the task is used.
 *
 * @var array
 */
	protected $_defaultConfig = [
		'plugin' => false,
		'callbacks' => true,
		'status' => null,
	];

/**
 * Starts the uninstall process of the given plugin.
 *
 * @return bool True on success, false otherwise
 */
	public function run() {
		$pluginName = $this->config('plugin');
		$status = $this->config('status');

		if (!$pluginName) {
			$this->error(__d('install', 'No plugin was given to enable/disable.'));
			return false;
		}

		if ($status === null) {
			$this->error(__d('install', 'You must indicate the new status of the plugin using `enable()` or `disable()`.'));
			return false;
		} else {
			$status = (bool)$status;
			$callbackSufix = $status ? 'Enable' : 'Disable'; // used later
		}

		try {
			$info = Plugin::info($pluginName, true);
			$pluginEntity = $this->Plugins
				->find()
				->where(['name' => $pluginName])
				->first();
		} catch (\Exception $e) {
			$info = null;
		}

		if (!$info || !$pluginEntity) {
			$this->error(__d('install', 'Plugin "{0}" was not found.', $pluginName));
			return false;
		}

		if ($info['isCore']) {
			$this->error(__d('install', 'Plugin "{0}" is a core plugin, you cannot enable or disable core\'s plugins.', $info['human_name']));
			return false;
		}

		$requiredBy = Plugin::checkReverseDependency($pluginName);
		if (!empty($requiredBy) && $status === false) {
			$this->error(__d('install', 'Plugin "{0}" cannot be disabled as it is required by: {1}', $info['human_name'], implode(', ', $requiredBy)));
			return false;
		}

		// MENTAL NOTE: As plugin is disabled to listeners are attached to the system, so we need
		// to manually attach them in order to trigger callbacks.
		if ($this->config('callbacks') && $status) {
			$this->attachListeners("{$info['path']}/src/Event");
		}

		if ($this->config('callbacks')) {
			$beforeEvent = $this->hook("Plugin.{$info['name']}.before{$callbackSufix}");
			if ($beforeEvent->isStopped() || $beforeEvent->result === false) {
				$this->error(__d('install', 'Task was explicitly rejected by the plugin.'));
				return false;
			}
		}

		$pluginEntity->set('status', $status);
		if (!$this->Plugins->save($pluginEntity)) {
			if ($status) {
				$this->error(__d('install', 'Plugin "{0}" could not be enabled due to an internal error.', $info['human_name']));
			} else {
				$this->error(__d('install', 'Plugin "{0}" could not be disabled due to an internal error.', $info['human_name']));
			}
			return false;
		}

		snapshot();

		if ($this->config('callbacks')) {
			$this->hook("Plugin.{$info['name']}.after{$callbackSufix}");
		}

		return true;
	}

/**
 * Indicates this task should enable the given plugin.
 * 
 * @param string $pluginName
 * @return Installer\Utility\ToggleTask
 */
	public function enable($pluginName = null) {
		if ($pluginName) {
			$this->plugin('plugin', $pluginName);
		}
		return $this->configure('status', true);
	}

/**
 * Indicates this task should disable the given plugin.
 * 
 * @param string $pluginName
 * @return Installer\Utility\ToggleTask
 */
	public function disable($pluginName = null) {
		if ($pluginName) {
			$this->plugin('plugin', $pluginName);
		}
		return $this->configure('status', false);
	}

/**
 * Sets the plugin to enable/disable.
 *
 * Shortcut for `$this->config('plugin', 'MyPlugin')`.
 * 
 * @param string $pluginName
 * @return Installer\Utility\ToggleTask
 */
	public function plugin($pluginName) {
		return $this->configure('plugin', $pluginName);
	}

}
