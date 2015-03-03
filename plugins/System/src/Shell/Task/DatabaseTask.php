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
     * {@inheritDoc}
     */
    public function startup()
    {
    }

    /**
     * Execution method always used for tasks
     *
     * @return void
     */
    public function main()
    {
        if (array_key_exists('export', $this->params)) {
            $export = empty($this->params['export']) ? [] : explode(',', $this->params['export']);
            array_filter($export);

            if ($this->export($export)) {
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
    }

    /**
     * Export entire database to PHP fixtures.
     *
     * All generated PHP files will be placed in `/tmp/Fixture/` directory.
     *
     * @param array $whiteList List of table names to export. If not given (empty
     *  array) all tables will be exported
     * @return bool
     */
    public function export($whiteList = [])
    {
        $db = ConnectionManager::get('default');
        $db->connect();
        $schemaCollection = $db->schemaCollection();
        $tables = $schemaCollection->listTables();

        if (file_exists(TMP . 'fixture/')) {
            $dst = new Folder(TMP . 'fixture/');
            $dst->delete();
            $this->out(sprintf('Removing existing directory %s', TMP . 'fixture/'), 1, Shell::VERBOSE);
        } else {
            new Folder(TMP . 'fixture/', true);
            $this->out(sprintf('Creating directory %s', TMP . 'fixture/'), 1, Shell::VERBOSE);
        }

        foreach ($tables as $table) {
            if (!empty($whiteList) && !in_array($table, $whiteList)) {
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

            // we need raw data for time, no Time objects
            foreach ($Table->schema()->columns() as $column) {
                $type = $Table->schema()->columnType($column);
                if (in_array($type, ['date', 'datetime', 'time'])) {
                    $Table->schema()->columnType($column, 'string');
                }
            }

            $rows = $Table->find('all');
            foreach ($rows as $row) {
                $records[] = $row->toArray();
            }

            $className = Inflector::camelize($table) . 'Schema';
            $fields = json_decode(str_replace(['(', ')'], ['&#40', '&#41'], json_encode($fields)), true);
            $fields = var_export($fields, true);
            $fields = str_replace(['array (', ')', '&#40', '&#41'], ['[', ']', '(', ')'], $fields);

            $records = json_decode(str_replace(['(', ')'], ['&#40', '&#41'], json_encode($records)), true);
            $records = var_export($records, true);
            $records = str_replace(['array (', ')', '&#40', '&#41'], ['[', ']', '(', ')'], $records);

            $fixture = "<?php\n";
            $fixture .= "trait {$className}Trait\n";
            $fixture .= "{\n";
            $fixture .= "\n";
            $fixture .= "    protected \$_fields = {$fields};\n";
            $fixture .= "\n";
            $fixture .= "    protected \$_records = {$records};\n";
            $fixture .= "\n";
            $fixture .= "    public function fields()\n";
            $fixture .= "    {\n";
            $fixture .= "        foreach (\$this->_fields as \$name => \$info) {\n";
            $fixture .= "            if (!empty(\$info['autoIncrement'])) {\n";
            $fixture .= "                \$this->_fields[\$name]['length'] = null;\n";
            $fixture .= "            }\n";
            $fixture .= "        }\n";
            $fixture .= "        return \$this->_fields;\n";
            $fixture .= "    }\n";
            $fixture .= "\n";
            $fixture .= "    public function records()\n";
            $fixture .= "    {\n";
            $fixture .= "        return \$this->_records;\n";
            $fixture .= "    }\n";
            $fixture .= "}\n\n";

            $fixture .= "class {$className}\n";
            $fixture .= "{\n";
            $fixture .= "\n";
            $fixture .= "    use {$className}Trait;\n";
            $fixture .= "\n";
            $fixture .= "}\n";

            $file = new File(TMP . "fixture/{$className}.php", true);
            $file->write($fixture, 'w', true);
            $this->out(sprintf('Table "%s" exported!', $table), 1, Shell::VERBOSE);
        }

        return true;
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
