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
use Installer\Utility\InstallTask;
use QuickApps\Core\Plugin;

/**
 * Represents a single uninstall task.
 *
 * ## Basic Usage:
 *
 * Using `InstallerComponent` on any controller:
 * 
 *     $task = $this->Installer
 *         ->task('uninstall', ['plugin' => 'MyPlugin']);
 *     
 *     if ($task->run()) {
 *         $this->Flash->success('Removed!');
 *     } else {
 *         $errors = $task->errors();
 *     }
 */
class UninstallTask extends InstallTask {

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
	];

/**
 * Starts the uninstall process of the given plugin.
 *
 * @return bool True on success, false otherwise
 */
	public function run() {
		$pluginName = $this->config('plugin');

		if (!is_writable(TMP)) {
			$this->error(__d('install', 'Enable write permissions in /tmp directory before uninstall any plugin'));
			return false;
		}

		if (!$pluginName) {
			$this->error(__d('install', 'No plugin was given to remove.'));
			return false;
		}

		try {
			$info = Plugin::info($pluginName);
		} catch (\Exception $e) {
			$this->error(__d('install', 'Plugin "{0}" was not found.', $pluginName));
			return false;
		}

		if ($info['isCore']) {
			$this->error(__d('install', 'Plugin "{0}" is a core plugin, you cannot remove core\'s plugins.', $info['human_name']));
			return false;
		}

		$requiredBy = Plugin::checkReverseDependency($pluginName);
		if (!empty($requiredBy)) {
			$this->error(__d('install', 'Plugin "{0}" cannot be removed as it is required by: {1}', $info['human_name'], implode(', ', $requiredBy)));
			return false;
		}

		if (!$this->canBeDeleted($info['path'])) {
			return false;
		}

		if ($this->config('callbacks')) {
			$beforeUninstallEvent = $this->invoke("Plugin.{$info['name']}.beforeUninstall");
			if ($beforeUninstallEvent->isStopped() || $beforeUninstallEvent->result === false) {
				return false;
			}
		}

		$plugin = $this->Plugins
			->find()
			->where(['name' => $pluginName])
			->first();

		if (!$this->Plugins->delete($plugin)) {
			$this->error(__d('install', 'Plugin "{0}" could not be unregistered from DB.', $info['human_name']));
			return false;
		}

		$folder = new Folder($info['path']);
		$folder->delete();
		snapshot();

		if ($this->config('callbacks')) {
			$this->invoke("Plugin.{$info['name']}.afterUninstall");
		}

		return true;
	}

}
