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
namespace Installer\Task;

use Cake\Filesystem\Folder;
use QuickApps\Core\Plugin;
use User\Utility\AcoManager;

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
class UninstallTask extends BaseTask
{

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
     * Invoked before "start()".
     *
     * @return void
     */
    public function init()
    {
        $this->plugin($this->config('plugin'));
    }

    /**
     * Starts the uninstall process of the given plugin.
     *
     * ### Events triggered:
     *
     * - `beforeUninstall`: Before plugins is removed from DB and before
     *   plugin's directory is deleted from "/plugins"
     * - `afterUninstall`: After plugins was removed from DB and after
     *   plugin's directory was deleted from "/plugins"
     *
     * @return bool True on success, false otherwise
     */
    public function start()
    {
        if (!is_writable(TMP)) {
            $this->error(__d('installer', 'Enable write permissions in /tmp directory before uninstall any plugin'));
            return false;
        }

        if (!$this->plugin()) {
            $this->error(__d('installer', 'No plugin was given to remove.'));
            return false;
        }

        try {
            $info = Plugin::info($this->plugin(), true);
            $pluginEntity = $this->Plugins
                ->find()
                ->where(['name' => $this->plugin()])
                ->first();
        } catch (\Exception $e) {
            $info = null;
        }

        if (!$info || !$pluginEntity) {
            $this->error(__d('installer', 'Plugin "{0}" was not found.', $this->plugin()));
            return false;
        }

        if ($info['isCore']) {
            $this->error(__d('installer', 'Plugin "{0}" is a core plugin, you cannot remove core\'s plugins.', $info['human_name']));
            return false;
        }

        $requiredBy = Plugin::checkReverseDependency($this->plugin());
        if (!empty($requiredBy)) {
            $this->error(__d('installer', 'Plugin "{0}" cannot be removed as it is required by: {1}', $info['human_name'], implode(', ', $requiredBy)));
            return false;
        }

        if (!$this->canBeDeleted($info['path'])) {
            return false;
        }

        if ($this->config('callbacks')) {
            try {
                $beforeUninstallEvent = $this->trigger("Plugin.{$info['name']}.beforeUninstall");
                if ($beforeUninstallEvent->isStopped() || $beforeUninstallEvent->result === false) {
                    $this->error(__d('installer', 'Task was explicitly rejected by the plugin.'));
                    return false;
                }
            } catch (\Exception $e) {
                $this->error(__d('installer', 'Internal error, plugin did not respond to "beforeUninstall" callback correctly.'));
                return false;
            }
        }

        if (!$this->Plugins->delete($pluginEntity)) {
            $this->error(__d('installer', 'Plugin "{0}" could not be unregistered from DB.', $info['human_name']));
            return false;
        }

        $folder = new Folder($info['path']);
        $folder->delete();
        snapshot();
        $this->_clearAcoPaths();

        if ($this->config('callbacks')) {
            try {
                $this->trigger("Plugin.{$info['name']}.afterUninstall");
            } catch (\Exception $e) {
                $this->error(__d('installer', 'Plugin did not respond to "afterUninstall" callback.'));
            }
        }

        Plugin::unload($info['name']);
        return true;
    }

    /**
     * Removes all ACOs created by this plugin.
     *
     * @return void
     */
    protected function _clearAcoPaths()
    {
        $this->loadModel('User.Acos');
        $nodes = $this->Acos
            ->find()
            ->where(['plugin' => $this->plugin()])
            ->order(['lft' => 'ASC'])
            ->all();

        foreach ($nodes as $node) {
            $this->Acos->removeFromTree($node);
            $this->Acos->delete($node);
        }

        AcoManager::buildAcos(null, true); // clear anything else
    }
}
