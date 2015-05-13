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
namespace Eav\Shell;

use Cake\Console\Shell;

/**
 * EAV shell.
 *
 * Used to drop or add virtual columns to tables.
 */
class TableShell extends Shell
{

    /**
     * Contains tasks to load and instantiate
     *
     * @var array
     */
    public $tasks = [
        'Eav.Schema',
        'Eav.Info',
    ];

    /**
     * Gets the option parser instance and configures it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser
            ->description('Table schema manipulator.')
            ->addSubcommand('schema', [
                'help' => 'Allows to add or drop virtual columns to tables.',
                'parser' => $this->Schema->getOptionParser(),
            ])
            ->addSubcommand('info', [
                'help' => 'Display information of virtual columns attached to tables.',
                'parser' => $this->Info->getOptionParser(),
            ]);

        return $parser;
    }
}
