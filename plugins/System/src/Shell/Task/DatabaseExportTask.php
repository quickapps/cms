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
 * Export database.
 *
 */
class DatabaseExportTask extends Shell
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
            ->description(__d('system', 'Export database'))
            ->addOption('destination', [
                'short' => 'd',
                'help' => __d('system', 'Where to place the exported tables.'),
                'default' => normalizePath(TMP . '/fixture/'),
            ])
            ->addOption('tables', [
                'short' => 't',
                'help' => __d('system', 'Optional, comma-separated list of table names to export. All tables will be exported if not provided.'),
                'default' => [],
            ])
            ->addOption('no-id', [
                'short' => 'n',
                'help' => __d('system', 'Exclude "id" columns from records, useful for some DB driver such as Postgres.'),
                'boolean' => true,
                'default' => false,
            ])
            ->addOption('fixture', [
                'short' => 'f',
                'help' => __d('system', 'Generates Fixture classes suitable for testing environments.'),
                'boolean' => true,
                'default' => false,
            ])
            ->addOption('mode', [
                'short' => 'm',
                'help' => __d('system', 'What to export, "full" exports schema and records, or "schema" for schema only.'),
                'default' => 'full',
                'choices' => ['full', 'schema'],
            ])
            ->addOption('no-data', [
                'short' => 'n',
                'help' => __d('system', 'List of table for which do not export data (only schema).'),
                'default' => [],
            ]);
        return $parser;
    }

    /**
     * Export entire database to PHP fixtures.
     *
     * By default, all generated PHP files will be placed in `/tmp/fixture/`
     * directory, this can be changed using the `--destination` argument.
     *
     * @return bool
     */
    public function main()
    {
        $options = (array)$this->params;
        $destination = normalizePath("{$options['destination']}/");

        if (is_string($options['tables'])) {
            $options['tables'] = explode(',', $options['tables']);
        }

        if (is_string($options['no-data'])) {
            $options['no-data'] = explode(',', $options['no-data']);
        }

        if (file_exists($destination)) {
            $dst = new Folder($destination);
            $dst->delete();
            $this->out(__d('system', 'Removing existing directory: {0}', $destination), 1, Shell::VERBOSE);
        } else {
            new Folder($destination, true);
            $this->out(__d('system', 'Creating directory: {0}', $destination), 1, Shell::VERBOSE);
        }

        $db = ConnectionManager::get('default');
        $db->connect();
        $schemaCollection = $db->schemaCollection();
        $tables = $schemaCollection->listTables();

        foreach ($tables as $table) {
            if (!empty($options['tables']) && !in_array($table, $options['tables'])) {
                $this->out(__d('system', 'Table "{0}" skipped', $table), 1, Shell::VERBOSE);
                continue;
            }

            $Table = TableRegistry::get($table);
            $Table->behaviors()->reset();
            $fields = [
                '_constraints' => [],
                '_indexes' => [],
                '_options' => (array)$Table->schema()->options(),
            ];
            $columns = $Table->schema()->columns();
            $records = [];
            $primaryKeys = [];

            foreach ($Table->schema()->indexes() as $index) {
                $fields['_indexes'] = $Table->schema()->index($index);
            }

            foreach ($columns as $column) {
                $fields[$column] = $Table->schema()->column($column);
            }

            foreach ($Table->schema()->constraints() as $constraint) {
                $constraintName = in_array($constraint, $columns) ? Inflector::underscore("{$table}_{$constraint}") : $constraint;
                $fields['_constraints'][$constraintName] = $Table->schema()->constraint($constraint);
                if (isset($fields['_constraints']['primary']['columns'])) {
                    $primaryKeys = $fields['_constraints']['primary']['columns'];
                }
            }

            foreach ($fields as $column => $info) {
                if (isset($info['length']) &&
                    in_array($column, $primaryKeys) &&
                    isset($info['autoIncrement']) &&
                    $info['autoIncrement'] === true
                ) {
                    unset($fields[$column]['length']);
                }
            }

            // FIX: We need RAW data for time instead of Time Objects
            $originalTypes = [];
            foreach ($Table->schema()->columns() as $column) {
                $type = $Table->schema()->columnType($column);
                $originalTypes[$column] = $type;
                if (in_array($type, ['date', 'datetime', 'time'])) {
                    $Table->schema()->columnType($column, 'string');
                }
            }

            if ($options['mode'] === 'full') {
                foreach ($Table->find('all') as $row) {
                    $row = $row->toArray();
                    if ($this->params['no-id'] && isset($row['id'])) {
                        unset($row['id']);
                    }
                    $records[] = $row;
                }
            }

            $className = Inflector::camelize($table) . 'Fixture';
            if ($options['fixture'] && in_array($className, ['AcosFixture', 'UsersFixture'])) {
                $records = [];
            }

            // undo changes made by "FIX"
            foreach ($originalTypes as $column => $type) {
                $fields[$column]['type'] = $type;
            }

            $fields = $this->_arrayToString($fields);
            $records = in_array($table, $options['no-data']) ? '[]' : $this->_arrayToString($records);

            $fixture = $this->_classFileHeader($className);
            $fixture .= "{\n";
            $fixture .= "\n";
            $fixture .= "    public \$table = '{$table}';\n";
            $fixture .= "\n";
            $fixture .= "    public \$fields = {$fields};\n";
            $fixture .= "\n";
            $fixture .= "    public \$records = {$records};\n";
            $fixture .= "}\n";

            $file = new File(normalizePath("{$destination}/{$className}.php"), true);
            $file->write($fixture, 'w', true);
            $this->out(__d('system', 'Table "{0}" exported!', $table), 1, Shell::VERBOSE);
        }

        $this->out(__d('system', 'Database exported to: {0}', $destination));

        return true;
    }

    /**
     * Returns correct class file header.
     *
     * @param string $className The name of the class withing the file
     * @return string
     */
    protected function _classFileHeader($className)
    {
        $header = <<<TEXT
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

TEXT;

        if ($this->params['fixture']) {
            $header .= <<<TEXT
namespace CMS\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;


TEXT;
        }

        $header .= $this->params['fixture'] ? "class {$className} extends TestFixture\n" : "\nclass {$className}\n";
        return $header;
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
}
