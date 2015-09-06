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
namespace Installer\Shell\Task;

use Cake\Console\Shell;
use CMS\Core\Package\PluginPackage;
use CMS\Core\Package\Rule\RuleChecker;
use CMS\Core\Plugin;
use CMS\Event\EventDispatcherTrait;
use Installer\Shell\Task\ListenerHandlerTrait;

/**
 * Plugin toggler, enables or disables a plugin.
 *
 */
class PluginToggleTask extends Shell
{

    use EventDispatcherTrait;
    use ListenerHandlerTrait;

    /**
     * Removes the welcome message.
     *
     * @return void
     */
    public function startup()
    {
    }

    /**
     * Gets the option parser instance and configures it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser
            ->description(__d('installer', 'Enables or disables a plugin.'))
            ->addOption('plugin', [
                'short' => 'p',
                'help' => __d('installer', 'Name of the plugin to enable/disable.'),
            ])
            ->addOption('status', [
                'short' => 's',
                'help' => __d('installer', 'Whether to "enable" or "disable" the given plugin.'),
                'options' => ['enable', 'disable'],
                'default' => 'enable',
            ])
            ->addOption('no-callbacks', [
                'short' => 'c',
                'help' => __d('installer', 'Plugin events will not be trigged.'),
                'boolean' => true,
                'default' => false,
            ]);
        return $parser;
    }

    /**
     * Task main method.
     *
     * @return bool
     */
    public function main()
    {
        $this->loadModel('System.Plugins');

        try {
            $plugin = plugin($this->params['plugin']);
            $existsInDb = (bool)$this->Plugins
                ->find()
                ->where(['name' => $this->params['plugin']])
                ->count();
        } catch (\Exception $e) {
            $plugin = $existsInDb = false;
        }

        if (!$plugin || !$existsInDb) {
            $this->err(__d('installer', 'Plugin "{0}" was not found.', $this->params['plugin']));
            return false;
        }

        if ($this->params['status'] === 'enable') {
            return $this->_enable($plugin);
        }

        return $this->_disable($plugin);
    }

    /**
     * Activates the given plugin.
     *
     * @param \CMS\Core\Package\PluginPackage $plugin The plugin to enable
     * @return bool True on success
     */
    protected function _enable(PluginPackage $plugin)
    {
        $checker = new RuleChecker((array)$plugin->composer['require']);
        if (!$checker->check()) {
            $this->err(__d('installer', 'Plugin "{0}" cannot be enabled as some dependencies are disabled or not installed: {1}', $plugin->humanName, $checker->fail(true)));
            return false;
        }

        // MENTAL NOTE: As plugin is disabled its listeners are not attached to the
        // system, so we need to manually attach them in order to trigger callbacks.
        if (!$this->params['no-callbacks']) {
            $this->_attachListeners($plugin->name, "{$plugin->path}/");
            $trigger = $this->_triggerBeforeEvents($plugin);
            if (!$trigger) {
                return false;
            }
        }

        return $this->_finish($plugin);
    }

    /**
     * Disables the given plugin.
     *
     * @param \CMS\Core\Package\PluginPackage $plugin The plugin to disable
     * @return bool True on success
     */
    protected function _disable(PluginPackage $plugin)
    {
        $requiredBy = Plugin::checkReverseDependency($plugin->name);
        if (!$requiredBy->isEmpty()) {
            $names = [];
            foreach ($requiredBy as $p) {
                $names[] = $p->name();
            }

            $this->err(__d('installer', 'Plugin "{0}" cannot be disabled as it is required by: {1}', $plugin->humanName, implode(', ', $names)));
            return false;
        }

        if (!$this->params['no-callbacks']) {
            $trigger = $this->_triggerBeforeEvents($plugin);
            if (!$trigger) {
                return false;
            }
        }

        return $this->_finish($plugin);
    }

    /**
     * Finish this task.
     *
     * @param \CMS\Core\Package\PluginPackage $plugin The plugin being managed
     *  by this task
     * @return bool True on success
     */
    protected function _finish(PluginPackage $plugin)
    {
        $pluginEntity = $this->Plugins
            ->find()
            ->where(['name' => $plugin->name])
            ->first();
        $pluginEntity->set('status', $this->params['status'] === 'enable' ? true : false);

        if (!$this->Plugins->save($pluginEntity)) {
            if ($this->params['status'] === 'enable') {
                $this->err(__d('installer', 'Plugin "{0}" could not be enabled due to an internal error.', $plugin->humanName));
            } else {
                $this->err(__d('installer', 'Plugin "{0}" could not be disabled due to an internal error.', $plugin->humanName));
            }
            return false;
        }

        snapshot();

        if (!$this->params['no-callbacks']) {
            $this->_triggerAfterEvents($plugin);
        }

        return true;
    }

    /**
     * Triggers plugin's "before<Enable|Disable>" events.
     *
     * @param \CMS\Core\Package\PluginPackahe $plugin The plugin for which
     *  trigger the events
     * @return bool True on success
     */
    protected function _triggerBeforeEvents(PluginPackage $plugin)
    {
        $affix = ucwords($this->params['status']);
        try {
            $event = $this->trigger("Plugin.{$plugin->name}.before{$affix}");
            if ($event->isStopped() || $event->result === false) {
                $this->err(__d('installer', 'Task was explicitly rejected by the plugin.'));
                return false;
            }
        } catch (\Exception $e) {
            $this->err(__d('installer', 'Internal error, plugin did not respond to "before{0}" callback properly.', $affix));
            return false;
        }

        return true;
    }

    /**
     * Triggers plugin's "after<Enable|Disable>" events.
     *
     * @param \CMS\Core\Package\PluginPackahe $plugin The plugin for which
     *  trigger the events
     * @return void
     */
    protected function _triggerAfterEvents(PluginPackage $plugin)
    {
        $affix = ucwords($this->params['status']);
        try {
            $this->trigger("Plugin.{$plugin->name}.after{$affix}");
        } catch (\Exception $e) {
            $this->err(__d('installer', 'Plugin did not respond to "after{0}" callback properly.', $affix));
        }
    }
}
