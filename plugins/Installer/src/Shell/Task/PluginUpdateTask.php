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

/**
 * Plugins updater.
 *
 */
class PluginUpdateTask extends PluginInstallTask
{

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
            ->description(__d('installer', 'Install a new plugin.'))
            ->addOption('source', [
                'short' => 's',
                'help' => __d('installer', 'Either a full path within filesystem to a ZIP file, or path to a directory representing an extracted ZIP file, or an URL from where download plugin package.'),
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
        if (!$this->_init()) {
            $this->_reset();
            return false;
        }

        if (!$this->params['no-callbacks']) {
            // "before" events occurs even before plugins is moved to its destination
            $this->_attachListeners($this->_plugin['name'], "{$this->_workingDir}/");
            try {
                $event = $this->trigger("Plugin.{$this->_plugin['name']}.beforeUpdate");
                if ($event->isStopped() || $event->result === false) {
                    $this->err(__d('installer', 'Task was explicitly rejected by the plugin.'));
                    $this->_reset();
                    return false;
                }
            } catch (\Exception $ex) {
                $this->err(__d('installer', 'Internal error, plugin did not respond to "beforeUpdate" callback correctly.'));
                $this->_reset();
                return false;
            }
        }

        if (!$this->_movePackage(true)) {
            $this->_reset();
            return false;
        }

        if (!$this->params['no-callbacks']) {
            try {
                $event = $this->trigger("Plugin.{$this->_plugin['name']}.afterUpdate");
            } catch (\Exception $e) {
                $this->err(__d('installer', 'Plugin was installed but some errors occur.'));
            }
        }

        $this->_finish();
        return true;
    }

    /**
     * Prepares this task and the package to be installed.
     *
     * @return bool True on success
     */
    protected function _init()
    {
        if (!parent::_init()) {
            return false;
        }

        if (!Plugin::exists($this->_plugin['name'])) {
            $this->err(__d('installer', 'The plugin "{0}" is not installed, you cannot update a plugin that is not installed in your system.', $this->_plugin['name']));
            return false;
        } else {
            $plugin = plugin($this->_plugin['name']);
            if (!$this->canBeDeleted($plugin->path)) {
                $this->err(__d('installer', 'Unable to update, please check write permissions for "{0}".', $plugin->path));
                return false;
            }
        }

        return true;
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
            $this->err(__d('installer', "Some plugin's files or directories cannot be removed from your server:"));
            foreach ($notWritable as $path) {
                $this->err(__d('installer', "  -{0}", $path));
            }
            return false;
        }

        return true;
    }
}
