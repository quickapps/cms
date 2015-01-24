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
 * Shell for I18N management.
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
     * Override main() for help message hook
     *
     * @return void
     */
    public function main()
    {
        $this->out('<info>Plugins Shell</info>');
        $this->hr();
        $this->out('[I]nstall new plugin');
        $this->out('[U]install existing plugin');
        $this->out('[E]nable plugin');
        $this->out('[D]isable plugin');
        $this->out('[H]elp');
        $this->out('[Q]uit');

        $choice = strtolower($this->in('What would you like to do?', ['I', 'U', 'E', 'D', 'H', 'Q']));
        switch ($choice) {
            case 'i':
                $this->Plugins->install();
                break;
            case 'u':
                $this->Plugins->uninstall();
                break;
            case 'd':
                $this->Plugins->disable();
                break;
            case 'e':
                $this->Plugins->enable();
                break;
            case 'h':
                $this->out($this->OptionParser->help());
                break;
            case 'q':
                return $this->_stop();
            default:
                $this->out('You have made an invalid selection. Please choose a command to execute by entering E, I, H, or Q.');
        }
        $this->hr();
        $this->main();
    }
}
