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
namespace Eav\Shell\Task;

use Cake\Console\Shell;
use Cake\Error\FatalErrorException;
use Cake\ORM\TableRegistry;

/**
 * Table schema info task.
 */
class InfoTask extends Shell
{

    /**
     * Gets the option parser instance and configures it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser
            ->description(__d('system', 'Select target table'))
            ->addOption('use', [
                'short' => 'u',
                'help' => __d('eav', 'The table alias name. e.g. "User.Users".'),
            ])
            ->addOption('bundle', [
                'short' => 'b',
                'help' => __d('eav', 'Indicates the column belongs to a bundle name within the table.'),
                'default' => null,
            ]);
        return $parser;
    }

    /**
     * Adds or drops the specified column.
     *
     * @return bool
     */
    public function main()
    {
        $options = (array)$this->params;
        try {
            $table = TableRegistry::get($options['use']);
        } catch (\Exception $ex) {
            $table = false;
        }

        if (!$table) {
            $this->err(__d('eav', 'The specified table does not exists.'));
            return false;
        } elseif (!$table->behaviors()->has('Eav')) {
            $this->err(__d('eav', 'The specified table is not using EAV behavior.'));
            return false;
        }

        foreach ($table->eavColumns($options['bundle']) as $name => $info) {
             $this->out(__d('eav', '- Column Name: {0}', $name));
             $this->out(__d('eav', '  - type: {0}', $info['type']));
             $this->out(__d('eav', '  - bundle: {0}', $info['bundle']));
             $this->out(__d('eav', '  - searchable: {searchable, select, yes{YES} other{NO}}', [
                'searchable' => ($info['searchable'] ? 'yes' : 'no')
             ]));
        }

        return true;
    }
}
