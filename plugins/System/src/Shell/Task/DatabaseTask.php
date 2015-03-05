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

use Cake\Console\Shell;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

/**
 * Database manager.
 *
 */
class DatabaseTask extends Shell
{

    /**
     * Export entire database to PHP fixtures.
     *
     * By default, all generated PHP files will be placed in `/tmp/Fixture/`
     * directory, this can be changed using the `--destination` argument.
     *
     * ### Parameters:
     *
     * - tables [t]: List of table names to export. If not given all tables will be
     *   exported.
     *
     * - destination [d]: Where to place the exported tables.
     *
     * @return bool
     */
    public function export()
    {
        $options = (array)$this->params;
        if (file_exists(TMP . 'fixture/')) {
            $dst = new Folder(TMP . 'fixture/');
            $dst->delete();
            $this->out(sprintf('Removing existing directory %s', TMP . 'fixture/'), 1, Shell::VERBOSE);
        } else {
            new Folder(TMP . 'fixture/', true);
            $this->out(sprintf('Creating directory %s', TMP . 'fixture/'), 1, Shell::VERBOSE);
        }

        $db = ConnectionManager::get('default');
        $db->connect();
        $schemaCollection = $db->schemaCollection();
        $tables = $schemaCollection->listTables();
        foreach ($tables as $table) {
            if (!empty($options['tables']) && !in_array($table, $options['tables'])) {
                $this->out(sprintf('Table "%s" skipped', $table), 1, Shell::VERBOSE);
                continue;
            }

            $Table = TableRegistry::get($table);
            $Table->behaviors()->reset();
            $fields = ['_constraints' => []];
            $columns = $Table->schema()->columns();
            $records = [];

            foreach ($columns as $column) {
                $fields[$column] = $Table->schema()->column($column);
            }

            foreach ($Table->schema()->constraints() as $constraint) {
                $constraintName = in_array($constraint, $columns) ? Inflector::underscore("{$table}_{$constraint}") : $constraint;
                $fields['_constraints'][$constraintName] = $Table->schema()->constraint($constraint);
            }

            // we need raw data for time instead of Time Objects
            foreach ($Table->schema()->columns() as $column) {
                $type = $Table->schema()->columnType($column);
                if (in_array($type, ['date', 'datetime', 'time'])) {
                    $Table->schema()->columnType($column, 'string');
                }
            }

            $rows = $Table->find('all');
            foreach ($rows as $row) {
                $row = $row->toArray();
                if (isset($row['id'])) {
                    unset($row['id']);
                }
                $records[] = $row;
            }

            $className = Inflector::camelize($table) . 'Fixture';
            $fields = $this->_arrayToString($fields);
            $records = $this->_arrayToString($records);

            $fixture = "<?php\n";
            $fixture .= "class {$className}\n";
            $fixture .= "{\n";
            $fixture .= "\n";
            $fixture .= "    public \$fields = {$fields};\n";
            $fixture .= "\n";
            $fixture .= "    public \$records = {$records};\n";
            $fixture .= "}\n";

            $file = new File(normalizePath("{$options['destination']}/{$className}.php"), true);
            $file->write($fixture, 'w', true);
            $this->out(sprintf('Table "%s" exported!', $table), 1, Shell::VERBOSE);
        }

        $this->out(sprintf('Database exported on: %s', TMP . 'fixture'));

        return true;
    }

    /**
     * Converts an array to code-string representation.
     *
     * @param array $var The array to convert
     * @return string
     */
    protected function _arrayToString(array $var)
    {
        $var = json_decode(str_replace(['(', ')'], ['&#40', '&#41'], json_encode($var)), true);
        $var = var_export($var, true);
        return str_replace(['array (', ')', '&#40', '&#41'], ['[', ']', '(', ')'], $var);
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
            ->description(__d('system', 'Database maintenance tasks'))
            ->addSubcommand('export', [
                'help' => __d('system', 'Use this command to persist your database into portable files.'),
                'parser' => [
                    'options' => [
                        'destination' => [
                            'short' => 'd',
                            'help' => __d('system', 'Where to place the exported tables.'),
                            'default' => normalizePath(TMP . '/fixture/'),
                        ],
                        'tables' => [
                            'short' => 't',
                            'help' => __d('system', 'Optional, comma-separated list of table names to export. All tables will be exported if not provided.'),
                            'default' => false,
                        ],
                    ]
                ]
            ]);

        return $parser;
    }
}
