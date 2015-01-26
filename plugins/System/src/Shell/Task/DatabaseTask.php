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
namespace System\Shell\Task;

use Cake\Filesystem\Folder;
use QuickApps\Console\Shell;

/**
 * Database manager.
 *
 */
class DatabaseTask extends Shell
{
    /**
     * List of tables to export.
     *
     * @var array
     */
    protected $_export = [];

    /**
     * Execution method always used for tasks
     *
     * @return void
     */
    public function main()
    {
        if (isset($this->params['export'])) {
            $this->_export = explode(',', $this->params['export']);
        }

        if (exportFixtures($this->_export)) {
            if (!empty($this->params['destination'])) {
                $src = new Folder(TMP . 'fixture');
                if ($src->move(['to' => $this->params['destination']])) {
                    $this->out(sprintf('Database exported on: %s', $this->params['destination']));
                } else {
                    foreach ($src->errors() as $err) {
                        $this->err($err);
                    }
                }
            } else {
                $this->out(sprintf('Database exported on: %s', TMP . 'fixture'));
            }
        } else {
            $this->err('Unable to export database');
        }
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
            'Database maintenance:'
        )->addOption('export', [
            'help' => 'List of table names to export, if none if given all tables will be exported. e.g. --export users,roles.'
        ])->addOption('destination', [
            'help' => 'Where to place the exported tables. defaults to "TMP/fixture/"',
        ]);

        return $parser;
    }
}
