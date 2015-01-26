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

use Cake\Core\Configure;
use Cake\Validation\Validation;
use Installer\Task\TaskManager;
use QuickApps\Console\Shell;
use QuickApps\Core\Plugin;

/**
 * Themes manager.
 *
 */
class ThemesTask extends Shell
{

    /**
     * Install a new theme from URL.
     *
     * @return void
     */
    public function install()
    {
        $message = "Enter the URL from where download the theme package (zip)?\n[Q]uit";
        while (true) {
            $url = $this->in($message);
            if (strtoupper($url) === 'Q') {
                $this->err('Installation aborted');
                break;
            } elseif (!Validation::url($url)) {
                $this->err('Invalid URL please try again');
            } else {
                $this->out('Downloading theme...', 0);
                $task = TaskManager::task('install', [
                    'packageType' => 'theme',
                    'validateMime' => false,
                ])->download($url);
                $this->_io->overwrite('Downloading theme... completed!');

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
     * Uninstall a theme.
     *
     * @return void
     */
    public function uninstall()
    {
        $allThemes = Plugin::collection()->match(['isTheme' => true])->toArray();
        $index = 1;
        $this->out();
        foreach ($allThemes as $k => $v) {
            $allThemes[$index] = $v;
            $this->out(sprintf('[%2d] %s', $index, $v['human_name']));
            $index++;
        }
        $this->out();

        $message = "Which theme would you like to uninstall?\n[Q]uit";
        while (true) {
            $in = $this->in($message);
            if (strtoupper($in) === 'Q') {
                $this->err('Operation aborted');
                break;
            } elseif (!isset($allThemes[intval($in)])) {
                $this->err('Invalid option');
            } else {
                $info = Plugin::info($allThemes[$in]['name'], true);
                $this->hr();
                $this->out('<info>The following theme will be uninstalled</info>');
                $this->hr();
                $this->out(sprintf('Name:        %s', $info['name']));
                $this->out(sprintf('Description: %s', $info['composer']['description']));
                $this->out(sprintf('Status:      %s', $info['status'] ? 'In use' : 'Disabled'));
                $this->out(sprintf('Regions:     %s', implode(', ', array_keys($info['composer']['extra']['regions']))));
                $this->out(sprintf('Type:        %s', $info['composer']['extra']['admin'] ? 'Backend' : 'Frontend'));
                $this->out(sprintf('Path:        %s', $info['path']));
                $this->hr();
                $this->out();

                $confirm = $this->in(sprintf('Please type in "%s" to uninstall', $allThemes[$in]['name']));
                if ($confirm === $allThemes[$in]['name']) {
                    $task = TaskManager::task('uninstall', ['plugin' => $allThemes[$in]['name']]);

                    if ($task->run()) {
                        $this->out('Theme uninstalled!');
                    } else {
                        $this->err('Theme could not be uninstalled, se below:', 2);
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
     * Switch site's theme.
     *
     * @return void
     */
    public function change()
    {
        $disabledThemes = Plugin::collection()->match(['status' => 0, 'isTheme' => true])->toArray();
        if (!count($disabledThemes)) {
            $this->err('There are no themes available!');
            $this->out();
            return;
        }

        $index = 1;
        $this->out();
        foreach ($disabledThemes as $k => $v) {
            $disabledThemes[$index] = $v;
            $this->out(sprintf('[%2d] %s', $index, $v['human_name']));
            $index++;
        }
        $this->out();

        $message = "Which theme would you like to use?\n[Q]uit";
        while (true) {
            $in = $this->in($message);
            if (strtoupper($in) === 'Q') {
                $this->err('Operation aborted');
                break;
            } elseif (!isset($disabledThemes[intval($in)])) {
                $this->err('Invalid option');
            } else {
                $task = TaskManager::task('activate_theme')->activate($disabledThemes[$in]['name']);

                if ($task->run()) {
                    $this->out('Theme switched!');
                } else {
                    $this->err('Theme could not be switched, se below:', 2);
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
}
