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
namespace System\Shell;

use QuickApps\Console\Shell;

/**
 * Maintenance shell.
 *
 */
class MaintenanceShell extends Shell
{

    /**
     * Contains tasks to load and instantiate
     *
     * @var array
     */
    public $tasks = ['System.Database'];

    /**
     * Override main() for help message hook
     *
     * @return void
     */
    public function main()
    {
        $this->out('<info>Maintenance Shell</info>');
        $this->hr();
        $this->out('[1] Export database');
        $this->out('[H]elp');
        $this->out('[Q]uit');

        $choice = strtolower($this->in('What would you like to do?', [1, 2, 3, 'H', 'Q']));
        switch ($choice) {
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
     * Gets the option parser instance and configures it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        $parser->description(
            'Provides some useful maintenance commands.'
        )->addSubcommand('database', [
            'help' => 'Provides database maintenance tasks',
            'parser' => $this->Database->getOptionParser()
        ]);

        return $parser;
    }
}
