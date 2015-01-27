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
 * Shell for themes management.
 *
 */
class ThemesShell extends Shell
{

    /**
     * Contains tasks to load and instantiate
     *
     * @var array
     */
    public $tasks = ['Installer.Themes'];

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
        $this->out('<info>Themes Shell</info>');
        $this->hr();
        $this->out('[1] Install new theme');
        $this->out('[2] Uinstall existing theme');
        $this->out('[3] Change site theme');
        $this->out('[H]elp');
        $this->out('[Q]uit');

        $choice = strtolower($this->in('What would you like to do?', [1, 2, 3, 'H', 'Q']));
        switch ($choice) {
            case '1':
                $this->Themes->install();
                break;
            case '2':
                $this->Themes->uninstall();
                break;
            case '3':
                $this->Themes->change();
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
}
