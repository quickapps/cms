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
use QuickApps\Core\Plugin;

/**
 * Shell for themes management.
 *
 */
class ThemesShell extends Shell
{

    /**
     * Contains tasks to load and instantiate.
     *
     * @var array
     */
    public $tasks = [
        'Installer.PluginInstall',
        'Installer.PluginUninstall',
        'Installer.ThemeActivation',
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
            ->description('Database maintenance commands.')
            ->addSubcommand('install', [
                'help' => 'Install a new theme.',
                'parser' => $this->PluginInstall->getOptionParser(),
            ])
            ->addSubcommand('uninstall', [
                'help' => 'Uninstalls an existing theme.',
                'parser' => $this->PluginUninstall->getOptionParser(),
            ])
            ->addSubcommand('change', [
                'help' => 'Change theme in use.',
                'parser' => $this->ThemeActivation->getOptionParser(),
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
        $this->out('<info>Themes Shell</info>');
        $this->hr();
        $this->out('[I]nstall new theme');
        $this->out('[R]emove an existing theme');
        $this->out('[C]hange site theme');
        $this->out('[H]elp');
        $this->out('[Q]uit');

        $choice = strtolower($this->in('What would you like to do?', ['I', 'R', 'C', 'H', 'Q']));
        switch ($choice) {
            case 'i':
                $this->_install();
                break;
            case 'r':
                $this->_uninstall();
                break;
            case 'c':
                $this->_change();
                break;
            case 'h':
                $this->out($this->OptionParser->help());
                break;
            case 'q':
                return $this->_stop();
            default:
                $this->out('You have made an invalid selection. Please choose a command to execute by entering 1, 2, 3, H, or Q.');
        }
        $this->hr();
        $this->main();
    }

    /**
     * Activator task.
     *
     * @return bool
     */
    public function change()
    {
        return $this->ThemeActivation->main();
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
     * Installs a new theme.
     *
     * @return void
     */
    protected function _install()
    {
        // TODO: theme shell install UI
    }

    /**
     * Removes an existing theme.
     *
     * @return void
     */
    protected function _uninstall()
    {
        // TODO: theme shell uninstall UI
    }

    /**
     * Switch site's theme.
     *
     * @return void
     */
    protected function _change()
    {
        $disabledThemes = Plugin::get()
            ->filter(function ($theme) {
                return $theme->isTheme && !in_array($theme->name, [option('front_theme'), option('back_theme')]);
            })
            ->toArray();

        if (!count($disabledThemes)) {
            $this->err('<info>There are no disabled themes!</info>');
            $this->out();
            return;
        }

        $index = 1;
        $this->out();
        foreach ($disabledThemes as $theme) {
            $disabledThemes[$index] = $theme;
            $this->out(__d('installer', '[{0,number,integer}] {1} [{2}]', $index, $theme->human_name, $theme->isAdmin ? 'back' : 'front'));
            $index++;
        }
        $this->out();

        $message = "Which theme would you like to activate?\n[Q]uit";
        while (true) {
            $in = $this->in($message);
            if (strtoupper($in) === 'Q') {
                $this->err('Operation aborted');
                break;
            } elseif (!isset($disabledThemes[intval($in)])) {
                $this->err('Invalid option');
            } else {
                $task = $this->dispatchShell("Installer.themes change -t {$disabledThemes[$in]->name}");

                if ($task === 0) {
                    $this->out('Theme changed!');
                    Plugin::dropCache();
                } else {
                    $this->err('Theme could not be changed.', 2);
                    $this->out();
                }
                break;
            }
        }

        $this->out();
    }
}
