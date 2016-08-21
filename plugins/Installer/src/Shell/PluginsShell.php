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
namespace Installer\Shell;

use Cake\Console\Shell;
use CMS\Core\Plugin;

/**
 * Shell for plugins management.
 *
 */
class PluginsShell extends Shell
{

    /**
     * Contains tasks to load and instantiate.
     *
     * @var array
     */
    public $tasks = [
        'Installer.PluginInstall',
        'Installer.PluginUninstall',
        'Installer.PluginToggle',
    ];

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
            ->description(__d('installer', 'Plugins maintenance commands.'))
            ->addSubcommand('install', [
                'help' => __d('installer', 'Install a new plugin.'),
                'parser' => $this->PluginInstall->getOptionParser(),
            ])
            ->addSubcommand('uninstall', [
                'help' => __d('installer', 'Uninstalls an existing plugin.'),
                'parser' => $this->PluginUninstall->getOptionParser(),
            ])
            ->addSubcommand('toggle', [
                'help' => __d('installer', 'Enable or disable a plugin.'),
                'parser' => $this->PluginToggle->getOptionParser(),
            ]);

        return $parser;
    }

    /**
     * Override main() for help message hook
     *
     * @return void
     */
    public function main()
    {
        $this->out(__d('installer', '<info>Plugins Shell</info>'));
        $this->hr();
        $this->out(__d('installer', '[1] Install new plugin'));
        $this->out(__d('installer', '[2] Remove an existing plugin'));
        $this->out(__d('installer', '[3] Enable plugin'));
        $this->out(__d('installer', '[4] Disable plugin'));
        $this->out(__d('installer', '[H]elp'));
        $this->out(__d('installer', '[Q]uit'));

        $choice = (string)strtolower($this->in(__d('installer', 'What would you like to do?'), ['1', '2', '3', '4', 'H', 'Q']));
        switch ($choice) {
            case '1':
                $this->_install();
                break;
            case '2':
                $this->_uninstall();
                break;
            case '3':
                $this->_enable();
                break;
            case '4':
                $this->_disable();
                break;
            case 'h':
                $this->out($this->OptionParser->help());
                break;
            case 'q':
                return $this->_stop();
            default:
                $this->out(__d('installer', 'You have made an invalid selection. Please choose a command to execute by entering 1, 2, 3, 4, H, or Q.'));
        }
        $this->hr();
        $this->main();
    }

    /**
     * Toogle task.
     *
     * @return bool
     */
    public function toggle()
    {
        return $this->PluginToggle->main();
    }

    /**
     * Install task.
     *
     * @return bool
     */
    public function install()
    {
        return $this->PluginInstall->main();
    }

    /**
     * Uninstall task.
     *
     * @return bool
     */
    public function uninstall()
    {
        return $this->PluginUninstall->main();
    }

    /**
     * Install a new plugin from URL, directory or ZIP.
     *
     * @return void
     */
    protected function _install()
    {
        $message = __d('installer', "Please provide a plugin source, it can be either an URL or a filesystem path to a ZIP/directory within your server?\n[Q]uit");
        while (true) {
            $source = $this->in($message);
            if (strtoupper($source) === 'Q') {
                $this->err(__d('installer', 'Installation aborted'));
                break;
            } else {
                $this->out(__d('installer', 'Starting installation...'), 0);
                $task = $this->dispatchShell("Installer.plugins install -s {$source}");

                if ($task === 0) {
                    $this->_io->overwrite(__d('installer', 'Starting installation... successfully installed!'), 2);
                    $this->out();
                    break;
                } else {
                    $this->_io->overwrite(__d('installer', 'Starting installation... failed!'), 2);
                    $this->out();
                }
            }
        }

        $this->out();
    }

    /**
     * Uninstall a plugin.
     *
     * @return void
     */
    protected function _uninstall()
    {
        $allPlugins = plugin()
            ->filter(function ($plugin) {
                return !$plugin->isTheme;
            })
            ->toArray();
        $index = 1;
        $this->out();
        foreach ($allPlugins as $plugin) {
            $allPlugins[$index] = $plugin;
            $this->out(__d('installer', '[{0, number}] {1}', [$index, $plugin->humanName]));
            $index++;
        }
        $this->out();

        $message = __d('installer', "Which plugin would you like to uninstall?\n[Q]uit");
        while (true) {
            $in = $this->in($message);
            if (strtoupper($in) === 'Q') {
                $this->err(__d('installer', 'Operation aborted'));
                break;
            } elseif (intval($in) < 1 || !isset($allPlugins[intval($in)])) {
                $this->err(__d('installer', 'Invalid option'));
            } else {
                $plugin = plugin($allPlugins[$in]->name());
                $this->hr();
                $this->out(__d('installer', '<info>The following plugin will be UNINSTALLED</info>'));
                $this->hr();
                $this->out(__d('installer', 'Name:        {0}', $plugin->name));
                $this->out(__d('installer', 'Description: {0}', $plugin->composer['description']));
                $this->out(__d('installer', 'Status:      {0}', ($plugin->status ? __d('installer', 'Active') : __d('installer', 'Disabled'))));
                $this->out(__d('installer', 'Path:        {0}', $plugin->path));
                $this->hr();
                $this->out();

                $confirm = $this->in(__d('installer', 'Please type in "{0}" to uninstall', $allPlugins[$in]->name));
                if ($confirm === $allPlugins[$in]->name) {
                    $task = $this->dispatchShell("Installer.plugins uninstall -p {$allPlugins[$in]->name}");

                    if ($task === 0) {
                        $this->out(__d('installer', 'Plugin uninstalled!'));
                        Plugin::dropCache();
                    } else {
                        $this->err(__d('installer', 'Plugin could not be uninstalled.'), 2);
                        $this->out();
                    }
                } else {
                    $this->err(__d('installer', 'Confirmation failure, operation aborted!'));
                }
                break;
            }
        }

        $this->out();
    }

    /**
     * Activates a plugin.
     *
     * @return void
     */
    protected function _enable()
    {
        $disabledPlugins = plugin()
            ->filter(function ($plugin) {
                return !$plugin->status && !$plugin->isTheme;
            })
            ->toArray();

        if (!count($disabledPlugins)) {
            $this->err(__d('installer', '<info>There are no disabled plugins!</info>'));
            $this->out();

            return;
        }

        $index = 1;
        $this->out();
        foreach ($disabledPlugins as $plugin) {
            $disabledPlugins[$index] = $plugin;
            $this->out(__d('installer', '[{0, number, integer}] {1}', [$index, $plugin->humanName]));
            $index++;
        }
        $this->out();

        $message = __d('installer', "Which plugin would you like to activate?\n[Q]uit");
        while (true) {
            $in = $this->in($message);
            if (strtoupper($in) === 'Q') {
                $this->err(__d('installer', 'Operation aborted'));
                break;
            } elseif (intval($in) < 1 || !isset($disabledPlugins[intval($in)])) {
                $this->err(__d('installer', 'Invalid option'));
            } else {
                $task = $this->dispatchShell("Installer.plugins toggle -p {$disabledPlugins[$in]->name} -s enable");

                if ($task === 0) {
                    $this->out(__d('installer', 'Plugin enabled!'));
                    Plugin::dropCache();
                } else {
                    $this->err(__d('installer', 'Plugin could not be enabled.'), 2);
                    $this->out();
                }
                break;
            }
        }

        $this->out();
    }

    /**
     * Disables a plugin.
     *
     * @return void
     */
    protected function _disable()
    {
        $enabledPlugins = plugin()
            ->filter(function ($plugin) {
                return $plugin->status && !$plugin->isTheme;
            })
            ->toArray();

        if (!count($enabledPlugins)) {
            $this->err(__d('installer', '<info>There are no active plugins!</info>'));
            $this->out();

            return;
        }

        $index = 1;
        $this->out();
        foreach ($enabledPlugins as $plugin) {
            $enabledPlugins[$index] = $plugin;
            $this->out(__d('installer', '[{0, number, integer}] {1}', [$index, $plugin->humanName]));
            $index++;
        }
        $this->out();

        $message = __d('installer', "Which plugin would you like to disable?\n[Q]uit");
        while (true) {
            $in = $this->in($message);
            if (strtoupper($in) === 'Q') {
                $this->err(__d('installer', 'Operation aborted'));
                break;
            } elseif (intval($in) < 1 || !isset($enabledPlugins[intval($in)])) {
                $this->err(__d('installer', 'Invalid option'));
            } else {
                $plugin = plugin($enabledPlugins[$in]->name());
                $this->hr();
                $this->out(__d('installer', '<info>The following plugin will be DISABLED</info>'));
                $this->hr();
                $this->out(__d('installer', 'Name:        {0}', $plugin->name));
                $this->out(__d('installer', 'Description: {0}', $plugin->composer['description']));
                $this->out(__d('installer', 'Status:      {0}', $plugin->status ? __d('installer', 'Active') : __d('installer', 'Disabled')));
                $this->out(__d('installer', 'Path:        {0}', $plugin->path));
                $this->hr();
                $this->out();

                $confirm = $this->in(__d('installer', 'Please type in "{0}" to disable this plugin', $enabledPlugins[$in]->name));
                if ($confirm === $enabledPlugins[$in]->name) {
                    $task = $this->dispatchShell("Installer.plugins toggle -p {$enabledPlugins[$in]->name} -s disable");

                    if ($task === 0) {
                        $this->out(__d('installer', 'Plugin disabled!'));
                        Plugin::dropCache();
                    } else {
                        $this->err(__d('installer', 'Plugin could not be disabled.'), 2);
                        $this->out();
                    }
                }
                break;
            }
        }

        $this->out();
    }
}
