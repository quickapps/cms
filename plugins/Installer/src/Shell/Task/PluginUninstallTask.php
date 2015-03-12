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
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\Folder;
use Installer\Shell\Task\ListenerHandlerTrait;
use QuickApps\Core\Package\PluginPackage;
use QuickApps\Core\Plugin;
use QuickApps\Event\HookAwareTrait;
use User\Utility\AcoManager;

/**
 * Plugin uninstaller.
 *
 * @property \System\Model\Table\PluginsTable $Plugins
 * @property \System\Model\Table\OptionsTable $Options
 */
class PluginUninstallTask extends Shell
{

    use HookAwareTrait;

    /**
     * The plugin being managed by this task.
     *
     * @var \QuickApps\Core\Package\PluginPackage
     */
    protected $_plugin = null;

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
        $connection = ConnectionManager::get('default');
        $result = $connection->transactional(function ($conn) {
            try {
                $result = $this->_runTransactional();
            } catch (\Exception $ex) {
                $this->err(__d('install', 'Something went wrong. Details: {0}', $ex->getMessage()));
                $result = false;
            }

            return $result;
        });

        // ensure snapshot
        snapshot();
        return $result;
    }

    /**
     * Runs uninstallation logic inside a safe transactional thread. This prevent
     * DB inconsistencies on uninstall failure.
     *
     * @return bool True on success, false otherwise
     */
    protected function _runTransactional()
    {
        // to avoid any possible issue
        snapshot();

        if (!is_writable(TMP)) {
            $this->err(__d('installer', 'Enable write permissions in /tmp directory before uninstall any plugin or theme.'));
            return false;
        }

        if (!$this->params['plugin']) {
            $this->err(__d('installer', 'No plugin/theme was given to remove.'));
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
            $this->err(__d('installer', 'Plugin "{name}" was not found.', ['name' => $this->params['plugin']]));
            return false;
        }

        $this->_plugin = $plugin;
        $type = $plugin->isTheme ? 'theme' : 'plugin';
        if ($plugin->isTheme && in_array($plugin->name, [option('front_theme'), option('back_theme')])) {
            $this->err(__d('installer', '{type, select, theme{The theme} other{The plugin}} "{name}" is currently being used and cannot be removed.', ['type' => $type, 'name' => $plugin->human_name]));
            return false;
        }

        if ($plugin->isCore) {
            $this->err(__d('installer', '{type, select, theme{The theme} other{The plugin}} "{name}" is a core plugin, you cannot remove core\'s plugins.', ['type' => $type, 'name' => $plugin->human_name]));
            return false;
        }

        $requiredBy = Plugin::checkReverseDependency($this->params['plugin']);
        if (!empty($requiredBy)) {
            $this->err(__d('installer', '{type, select, theme{The theme} other{The plugin}} "{name}" cannot be removed as it is required by: {required}', ['type' => $type, 'name' => $plugin->human_name, 'required' => implode(', ', $requiredBy)]));
            return false;
        }

        if (!$this->_canBeDeleted($plugin->path)) {
            return false;
        }

        if (!$this->params['no-callbacks']) {
            try {
                $event = $this->trigger("Plugin.{$plugin->name}.beforeUninstall");
                if ($event->isStopped() || $event->result === false) {
                    $this->err(__d('installer', 'Task was explicitly rejected by the {type, select, theme{theme} other{plugin}}.', ['type' => $type]));
                    return false;
                }
            } catch (\Exception $e) {
                $this->err(__d('installer', 'Internal error, the {type, select, theme{theme} other{plugin}} did not respond to "beforeUninstall" callback correctly.', ['type' => $type]));
                return false;
            }
        }

        if (!$this->Plugins->delete($pluginEntity)) {
            $this->err(__d('installer', '{type, select, theme{The theme} other{The plugin}} "{name}" could not be unregistered from DB.', ['type' => $type, 'name' => $plugin->human_name]));
            return false;
        }

        $this->_removeOptions();
        $this->_clearAcoPaths();
        $folder = new Folder($plugin->path);
        $folder->delete();
        snapshot();

        if (!$this->params['no-callbacks']) {
            try {
                $this->trigger("Plugin.{$plugin->name}.afterUninstall");
            } catch (\Exception $e) {
                $this->err(__d('installer', '{type, select, theme{The theme} other{The plugin}} did not respond to "afterUninstall" callback.', ['type' => $type]));
            }
        }

        Plugin::unload($plugin->name);
        Plugin::dropCache();
        return true;
    }

    /**
     * Removes from "options" table any entry registered by the plugin.
     *
     * @return void
     */
    protected function _removeOptions()
    {
        $options = [];
        if (!empty($this->_plugin->composer['extra']['options'])) {
            $this->loadModel('System.Options');
            $options = $this->_plugin->composer['extra']['options'];
        }

        foreach ($options as $option) {
            if (!empty($option['name'])) {
                $this->Options->deleteAll(['name' => $option['name']]);
            }
        }
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
        $type = $this->_plugin->isTheme ? 'theme' : 'plugin';
        if (!file_exists($path) || !is_dir($path)) {
            $this->err(__d('installer', "{type, select, theme{Theme's} other{Plugin's}} directory was not found: {path}", ['type' => $type, 'path' => $path]));
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
            $this->err(__d('installer', "Some {type, select, theme{theme's} other{plugin's}} files or directories cannot be removed from your server, please check write permissions of:", ['type' => $type]));
            foreach ($notWritable as $path) {
                $this->err(__d('installer', '  - {path}', ['path' => $path]));
            }
            return false;
        }

        return true;
    }
}
