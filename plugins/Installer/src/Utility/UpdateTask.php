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

use Installer\Utility\InstallTask;

/**
 * Represents a single update task.
 *
 * ## Basic Usage:
 *
 * Using `InstallerComponent` on any controller:
 * 
 *     $task = $this->Installer
 *         ->task('update', ['callbacks' => true])
 *         ->download('http://example.com/new-version.zip');
 *     
 *     if ($task->run()) {
 *         $this->Flash->success('Installed!');
 *     } else {
 *         $errors = $task->errors();
 *     }
 */
class UpdateTask extends InstallTask {

/**
 * Default config
 *
 * These are merged with user-provided configuration when the task is used.
 *
 * @var array
 */
	protected $_defaultConfig = [
		'callbacks' => true,
	];

/**
 * Starts the update process of the uploaded/downloaded package.
 *
 * This method should me used after a package has been uploaded or
 * downloaded to the server. An error will be registered otherwise.
 *
 * ### Events triggered:
 *
 * - `beforeUpdate`: Before plugins's old directory is removed from "/plugins"
 * - `afterUpdate`: Before plugins's old directory was removed from "/plugins" and
 *    after placing new directory in its place.
 *
 * @return bool True on success, false otherwise
 */
	public function run() {
		if (!empty($this->_errors)) {
			$this->_rollback();
			return false;
		}

		if (!$this->_packagePath) {
			$this->_rollback();
			$this->error(__d('installer', 'You must set a package before try to install.'));
			return false;
		}

		if (!$this->_unzip()) {
			$this->_rollback();
			return false;
		}

		if (!$this->_validateContent()) {
			$this->_rollback();
			return false;
		}

		if (!$this->_exists()) {
			$this->_rollback();
			$this->error(__d('installer', 'This plugin is not installed, you cannot update a plugin that is not installed in your system.', $this->_pluginName));
			return false;
		}

		$info = Plugin::info($this->_pluginName, true);
		if ($info['isCore']) {
			$this->error(__d('installer', 'Plugin "{0}" is a core plugin, you cannot update system\'s core using this method.', $info['human_name']));
			return false;
		}

		if (!$this->canBeDeleted($info['path'])) {
			return false;
		}

		if ($this->config('callbacks')) {
			$this->_attachListeners("{$this->_extractedPath}src/Event");
			$beforeUpdateEvent = $this->invoke("Plugin.{$this->_pluginName}.beforeUpdate", $this);
			if ($beforeUpdateEvent->isStopped() || $beforeUpdateEvent->result === false) {
				$this->_rollback();
				return false;
			}
		}

		if (!$this->_movePackage(true)) {
			$this->_rollback();
			return false;
		}

		if ($this->config('callbacks')) {
			$this->invoke("Plugin.{$this->_pluginName}.afterUpdate", $this);
		}

		$this->_finish();
		return true;
	}

}
