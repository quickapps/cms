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
use Cake\Core\Configure;
use Cake\Validation\Validation;
use Installer\Task\TaskManager;
use QuickApps\Core\Plugin;

/**
 * Plugins manager.
 *
 */
class PluginsTask extends Shell
{

    /**
     * {@inheritDoc}
     */
    public function startup()
    {
    }

    /**
     * Install a new plugin from URL.
     *
     * @return void
     */
    public function install()
    {
        $message = "Enter the URL from where download the plugin package (zip)?\n[Q]uit";
        while (true) {
            $url = $this->in($message);
            if (strtoupper($url) === 'Q') {
                $this->err('Installation aborted');
                break;
            } elseif (!Validation::url($url)) {
                $this->err('Invalid URL please try again');
            } else {
                $this->out('Downloading plugin...', 0);
                $task = TaskManager::task('install', [
                    'activate' => true,
                    'packageType' => 'plugin',
                    'validateMime' => false,
                ])->download($url);
                $this->_io->overwrite('Downloading plugin... completed!');

                $this->out('Starting installation...', 0);
                $result = $task->run();

                if ($result) {
                    $this->_io->overwrite('Starting installation... successfully installed!', 2);
                    $this->out();
                    break;
                } else {
                    $this->_io->overwrite('Starting installation... failed! see below:', 2);
                    foreach ($task->errors() as $error) {
                        $this->err("\t- " . $error);
                    }
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
    public function uninstall()
    {
        $allPlugins = Plugin::get()
            ->filter(function ($plugin) {
                return !$plugin->isTheme;
            })
            ->toArray();
        $index = 1;
        $this->out();
        foreach ($allPlugins as $plugin) {
            $allPlugins[$index] = $plugin;
            $this->out(sprintf('[%2d] %s', $index, $plugin->human_name));
            $index++;
        }
        $this->out();

        $message = "Which plugin would you like to uninstall?\n[Q]uit";
        while (true) {
            $in = $this->in($message);
            if (strtoupper($in) === 'Q') {
                $this->err('Operation aborted');
                break;
            } elseif (!isset($allPlugins[intval($in)])) {
                $this->err('Invalid option');
            } else {
                $plugin = Plugin::get($allPlugins[$in]->name());
                $this->hr();
                $this->out('<info>The following plugin will be uninstalled</info>');
                $this->hr();
                $this->out(sprintf('Name:        %s', $plugin->name));
                $this->out(sprintf('Description: %s', $plugin->composer['description']));
                $this->out(sprintf('Status:      %s', $plugin->status ? 'Active' : 'Disabled'));
                $this->out(sprintf('Path:        %s', $plugin->path));
                $this->hr();
                $this->out();

                $confirm = $this->in(sprintf('Please type in "%s" to uninstall', $allPlugins[$in]->name));
                if ($confirm === $allPlugins[$in]->name) {
                    $task = TaskManager::task('uninstall', ['plugin' => $allPlugins[$in]->name]);

                    if ($task->run()) {
                        $this->out('Plugin uninstalled!');
                    } else {
                        $this->err('Plugin could not be uninstalled, se below:', 2);
                        foreach ($task->errors() as $error) {
                            $this->err("\t- " . $error);
                        }
                        $this->out();
                    }
                } else {
                    $this->err('Confirmation failure, operation aborted!');
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
    public function enable()
    {
        $disabledPlugins = Plugin::get()
            ->filter(function ($plugin) {
                return !$plugin->status && !$plugin->isTheme;
            })
            ->toArray();

        if (!count($disabledPlugins)) {
            $this->err('There are no disabled plugins!');
            $this->out();
            return;
        }

        $index = 1;
        $this->out();
        foreach ($disabledPlugins as $plugin) {
            $disabledPlugins[$index] = $plugin;
            $this->out(sprintf('[%2d] %s', $index, $plugin->human_name));
            $index++;
        }
        $this->out();

        $message = "Which plugin would you like to activate?\n[Q]uit";
        while (true) {
            $in = $this->in($message);
            if (strtoupper($in) === 'Q') {
                $this->err('Operation aborted');
                break;
            } elseif (!isset($disabledPlugins[intval($in)])) {
                $this->err('Invalid option');
            } else {
                $task = TaskManager::task('toggle')->enable($disabledPlugins[$in]->name());

                if ($task->run()) {
                    $this->out('Plugin enabled!');
                } else {
                    $this->err('Plugin could not be enabled, se below:', 2);
                    foreach ($task->errors() as $error) {
                        $this->err("\t- " . $error);
                    }
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
    public function disable()
    {
        $enabledPlugins = Plugin::get()
            ->filter(function ($plugin) {
                return $plugin->status && !$plugin->isTheme;
            })
            ->toArray();

        if (!count($enabledPlugins)) {
            $this->err('There are no active plugins!');
            $this->out();
            return;
        }

        $index = 1;
        $this->out();
        foreach ($enabledPlugins as $plugin) {
            $enabledPlugins[$index] = $plugin;
            $this->out(sprintf('[%2d] %s', $index, $plugin->human_name));
            $index++;
        }
        $this->out();

        $message = "Which plugin would you like to disable?\n[Q]uit";
        while (true) {
            $in = $this->in($message);
            if (strtoupper($in) === 'Q') {
                $this->err('Operation aborted');
                break;
            } elseif (!isset($enabledPlugins[intval($in)])) {
                $this->err('Invalid option');
            } else {
                $plugin = Plugin::get($enabledPlugins[$in]->name());
                $this->hr();
                $this->out('<info>The following plugin will be uninstalled</info>');
                $this->hr();
                $this->out(sprintf('Name:        %s', $plugin->name));
                $this->out(sprintf('Description: %s', $plugin->composer['description']));
                $this->out(sprintf('Status:      %s', $plugin->status ? 'Active' : 'Disabled'));
                $this->out(sprintf('Path:        %s', $plugin->path));
                $this->hr();
                $this->out();

                $confirm = $this->in(sprintf('Please type in "%s" to disable this plugin', $enabledPlugins[$in]->name));
                if ($confirm === $enabledPlugins[$in]->name) {
                    $task = TaskManager::task('toggle')->disable($enabledPlugins[$in]->name);

                    if ($task->run()) {
                        $this->out('Plugin disabled!');
                    } else {
                        $this->err('Plugin could not be disabled, se below:', 2);
                        foreach ($task->errors() as $error) {
                            $this->err("\t- " . $error);
                        }
                        $this->out();
                    }
                }
                break;
            }
        }

        $this->out();
    }
}
