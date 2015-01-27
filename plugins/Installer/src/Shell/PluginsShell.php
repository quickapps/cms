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

/**
 * Shell for plugins management.
 *
 */
class PluginsShell extends Shell
{

    /**
     * Contains tasks to load and instantiate
     *
     * @var array
     */
    public $tasks = ['Installer.Plugins'];

    /**
     * {@inheritDoc}
     */
    public function startup()
    {
    }

    /**
     * Override main() for help message hook
     *
     * @return void
     */
    public function main()
    {
        $this->out('<info>Plugins Shell</info>');
        $this->hr();
        $this->out('[1] Install new plugin');
        $this->out('[2] Uinstall existing plugin');
        $this->out('[3] Enable plugin');
        $this->out('[4] Disable plugin');
        $this->out('[H]elp');
        $this->out('[Q]uit');

        $choice = strtolower($this->in('What would you like to do?', [1, 2, 3, 4, 'H', 'Q']));
        switch ($choice) {
            case '1':
                $this->Plugins->install();
                break;
            case '2':
                $this->Plugins->uninstall();
                break;
            case '3':
                $this->Plugins->enable();
                break;
            case '4':
                $this->Plugins->disable();
                break;
            case 'h':
                $this->out($this->OptionParser->help());
                break;
            case 'q':
                return $this->_stop();
            default:
                $this->out('You have made an invalid selection. Please choose a command to execute by entering 1, 2, 3, 4, H, or Q.');
        }
        $this->hr();
        $this->main();
    }
}
