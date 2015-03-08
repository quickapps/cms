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

use Cake\Console\Shell;
use Cake\Datasource\ConnectionManager;

/**
 * Database shell.
 *
 */
class DatabaseShell extends Shell
{

    /**
     * Contains tasks to load and instantiate
     *
     * @var array
     */
    public $tasks = ['System.DatabaseExport'];

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
            ->addSubcommand('export', [
                'help' => 'Export database to portable format.',
                'parser' => $this->DatabaseExport->getOptionParser(),
            ])
            ->addSubcommand('tables', [
                'help' => 'List all database tables.',
            ])
            ->addSubcommand('connection', [
                'help' => 'Show connection settings.',
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
        $this->out('<info>Database Shell</info>');
        $this->hr();
        $this->out('[E]xport database');
        $this->out('[L]ist tables');
        $this->out('[S]how connection');
        $this->out('[H]elp');
        $this->out('[Q]uit');

        $choice = strtolower($this->in('What would you like to do?', ['E', 'L', 'S', 'H', 'Q']));
        switch ($choice) {
            case 'e':
                $this->dispatchShell('System.database export -m full');
                break;
            case 'l':
                $this->tables();
                break;
            case 's':
                $this->connection();
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
     * Displays a list of all table names in database.
     *
     * @return void
     */
    public function tables()
    {
        $db = ConnectionManager::get('default');
        $db->connect();
        $schemaCollection = $db->schemaCollection();
        $tables = $schemaCollection->listTables();
        foreach ($tables as $table) {
            $this->out(sprintf('- %s', $table));
        }
    }

    /**
     * Display database connection information.
     *
     * @return void
     */
    public function connection()
    {
        $db = ConnectionManager::get('default');
        foreach ($db->config() as $key => $value) {
            if (is_array($value)) {
                continue;
            }
            $this->out(sprintf('- %s: %s', $key, $value));
        }
    }

    /**
     * Export database tables.
     *
     * @return void
     */
    public function export()
    {
        $this->DatabaseExport->main();
    }
}
