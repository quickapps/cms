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

use QuickApps\Core\Package\RuleChecker;
use QuickApps\Core\Plugin;

/**
 * Represents a single toggle task.
 *
 * Allows to enable or disable a plugin.
 *
 * ## Usage Examples:
 *
 * Using `InstallerComponent` on any controller:
 *
 *     $task = $this->Installer
 *         ->task('toggle')
 *         ->enable('MyPlugin');
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
class ToggleTask extends BaseTask
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
        'status' => null,
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
     * Starts the enable/disable process of the given plugin.
     *
     * @return bool True on success, false otherwise
     */
    public function start()
    {
        $status = $this->config('status');

        if ($status === null) {
            $this->error(__d('installer', 'You must indicate the new status of the plugin using `enable()` or `disable()`.'));
            return false;
        } else {
            $status = (bool)$status;
            $callbackSufix = $status ? 'Enable' : 'Disable'; // used later
        }

        try {
            $plugin = Plugin::get($this->plugin());
            $pluginEntity = $this->Plugins
                ->find()
                ->where(['name' => $this->plugin()])
                ->first();
        } catch (\Exception $e) {
            $plugin = $pluginEntity = false;
        }

        if (!$plugin || !$pluginEntity) {
            $this->error(__d('installer', 'Plugin "{0}" was not found.', $this->plugin()));
            return false;
        }

        if ($plugin->isCore) {
            $this->error(__d('installer', 'Plugin "{0}" is a core plugin, you cannot enable or disable core\'s plugins.', $plugin->human_name));
            return false;
        }

        $checker = new RuleChecker((array)$plugin->composer['require']);
        if ($status === true && !$checker->check()) {
            $this->error(__d('installer', 'Plugin "{0}" cannot be enabled as some dependencies are disabled or not installed: {1}', $plugin->human_name, $checker->fail(true)));
            return false;
        }

        $requiredBy = Plugin::checkReverseDependency($this->plugin());
        if ($status === false && !empty($requiredBy)) {
            $this->error(__d('installer', 'Plugin "{0}" cannot be disabled as it is required by: {1}', $plugin->human_name, implode(', ', $requiredBy)));
            return false;
        }

        // MENTAL NOTE: As plugin is disabled its listeners are not attached to the system, so we need
        // to manually attach them in order to trigger callbacks.
        // If `$status` is true means plugin is disabled and we are trying to enable it again.
        if ($this->config('callbacks') && $status) {
            $this->attachListeners("{$plugin->path}/src/Event");
        }

        if ($this->config('callbacks')) {
            try {
                $beforeEvent = $this->trigger("Plugin.{$plugin->name}.before{$callbackSufix}");
                if ($beforeEvent->isStopped() || $beforeEvent->result === false) {
                    $this->error(__d('installer', 'Task was explicitly rejected by the plugin.'));
                    return false;
                }
            } catch (\Exception $e) {
                $this->error(__d('installer', 'Internal error, plugin did not respond to "before{0}" callback correctly.', $callbackSufix));
                return false;
            }
        }

        $pluginEntity->set('status', $status);
        if (!$this->Plugins->save($pluginEntity)) {
            if ($status) {
                $this->error(__d('installer', 'Plugin "{0}" could not be enabled due to an internal error.', $plugin->human_name));
            } else {
                $this->error(__d('installer', 'Plugin "{0}" could not be disabled due to an internal error.', $plugin->human_name));
            }
            return false;
        }

        snapshot();

        if ($this->config('callbacks')) {
            try {
                $this->trigger("Plugin.{$plugin->name}.after{$callbackSufix}");
            } catch (\Exception $e) {
                $this->error(__d('installer', 'Plugin did not respond to "after{0}" callback.', $callbackSufix));
            }
        }

        return true;
    }

    /**
     * Indicates this task should enable the given plugin.
     *
     * @param string|null $pluginName Plugin's name
     * @return $this
     */
    public function enable($pluginName = null)
    {
        if ($pluginName !== null) {
            $this->config('plugin', $pluginName);
            $this->config('status', true);
            $this->plugin($pluginName);
        }
        return $this;
    }

    /**
     * Indicates this task should disable the given plugin.
     *
     * @param string|null $pluginName Plugin's name
     * @return $this
     */
    public function disable($pluginName = null)
    {
        if ($pluginName !== null) {
            $this->config('plugin', $pluginName);
            $this->config('status', false);
            $this->plugin($pluginName);
        }
        return $this;
    }
}
