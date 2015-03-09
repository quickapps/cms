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
use Cake\Filesystem\Folder;
use Installer\Shell\Task\ListenerHandlerTrait;
use QuickApps\Core\Plugin;
use QuickApps\Event\HookAwareTrait;
use User\Utility\AcoManager;

/**
 * Plugin uninstaller.
 *
 */
class PluginUninstallTask extends Shell
{

    use HookAwareTrait;

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
            ->description(__d('installer', 'Uninstall an existing plugin.'))
            ->addOption('plugin', [
                'short' => 'p',
                'help' => __d('system', 'Name of the plugin to uninstall.'),
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
        if (!is_writable(TMP)) {
            $this->err(__d('installer', 'Enable write permissions in /tmp directory before uninstall any plugin'));
            return false;
        }

        if (!$this->params['plugin']) {
            $this->err(__d('installer', 'No plugin was given to remove.'));
            return false;
        }

        $this->loadModel('System.Plugins');

        try {
            $plugin = Plugin::get($this->params['plugin']);
            $pluginEntity = $this->Plugins
                ->find()
                ->where(['name' => $this->params['plugin']])
                ->limit(1)
                ->first();
        } catch (\Exception $ex) {
            $plugin = $pluginEntity = false;
        }

        if (!$plugin || !$pluginEntity) {
            $this->err(__d('installer', 'Plugin "{0}" was not found.', $this->params['plugin']));
            return false;
        }

        if ($plugin->isCore) {
            $this->err(__d('installer', 'Plugin "{0}" is a core plugin, you cannot remove core\'s plugins.', $plugin->human_name));
            return false;
        }

        $requiredBy = Plugin::checkReverseDependency($this->params['plugin']);
        if (!empty($requiredBy)) {
            $this->err(__d('installer', 'Plugin "{0}" cannot be removed as it is required by: {1}', $plugin->human_name, implode(', ', $requiredBy)));
            return false;
        }

        if (!$this->_canBeDeleted($plugin->path)) {
            return false;
        }

        if (!$this->params['no-callbacks']) {
            try {
                $event = $this->trigger("Plugin.{$plugin->name}.beforeUninstall");
                if ($event->isStopped() || $event->result === false) {
                    $this->err(__d('installer', 'Task was explicitly rejected by the plugin.'));
                    return false;
                }
            } catch (\Exception $e) {
                $this->err(__d('installer', 'Internal error, plugin did not respond to "beforeUninstall" callback correctly.'));
                return false;
            }
        }

        if (!$this->Plugins->delete($pluginEntity)) {
            $this->err(__d('installer', 'Plugin "{0}" could not be unregistered from DB.', $plugin->human_name));
            return false;
        }

        $folder = new Folder($plugin->path);

        $folder->delete();
        snapshot();
        $this->_clearAcoPaths();

        if (!$this->params['no-callbacks']) {
            try {
                $this->trigger("Plugin.{$plugin->name}.afterUninstall");
            } catch (\Exception $e) {
                $this->err(__d('installer', 'Plugin did not respond to "afterUninstall" callback.'));
            }
        }

        Plugin::unload($plugin->name);
        Plugin::dropCache();
        return true;
    }

    /**
     * Removes all ACOs created by the plugin being uninstall.
     *
     * @return void
     */
    protected function _clearAcoPaths()
    {
        $this->loadModel('User.Acos');
        $nodes = $this->Acos
            ->find()
            ->where(['plugin' => $this->params['plugin']])
            ->order(['lft' => 'ASC'])
            ->all();

        foreach ($nodes as $node) {
            $this->Acos->removeFromTree($node);
            $this->Acos->delete($node);
        }

        AcoManager::buildAcos(null, true); // clear anything else
    }

    /**
     * Recursively checks if the given directory (and its content) can be deleted.
     *
     * This method automatically registers an error message if validation fails.
     *
     * @param string $path Directory to check
     * @return bool
     */
    protected function _canBeDeleted($path)
    {
        if (!file_exists($path) || !is_dir($path)) {
            $this->err(__d('installer', "Plugin's directory was not found: ", $path));
            return false;
        }

        $folder = new Folder($path);
        $content = $folder->tree();
        $notWritable = [];

        foreach ($content as $foldersOrFiles) {
            foreach ($foldersOrFiles as $element) {
                if (!is_writable($element)) {
                    $notWritable[] = $element;
                }
            }
        }

        if (!empty($notWritable)) {
            $this->err(__d('installer', "Some plugin's files or directories cannot be removed from your server, please check write permissions of:"));
            foreach ($notWritable as $path) {
                $this->err(__d('installer', '  - {0}', $path));
            }
            return false;
        }

        return true;
    }
}
