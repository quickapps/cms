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
namespace Installer\Utility;

use Cake\Core\InstanceConfigTrait;
use Cake\Database\Schema\Table as TableSchema;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\File;
use Cake\Filesystem\Folder;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

/**
 * Handles database initialization for QuickAppsCMS's first installations.
 *
 */
class DatabaseInstaller
{

    use InstanceConfigTrait;

    /**
     * Error messages list.
     *
     * @var array
     */
    protected $_errors = [];

    /**
     * Default configuration for this class.
     *
     * - settingsPath: Full path to the "settings.php" file where store connection
     *   information used by QuickAppsCMS. This should NEVER be changed, use with
     *   caution.
     *
     * - schemaPath: Path to directory containing all tables information to be
     *   imported (fixtures).
     *
     * - maxExecutionTime: Time in seconds for PHP's "max_execution_time" directive.
     *   Defaults to 300 (5 minutes).
     *
     * @var array
     */
    protected $_defaultConfig = [
        'settingsPath' => null,
        'schemaPath' => null,
        'maxExecutionTime' => 300,
    ];

    /**
     * Default database connection config.
     *
     * @var array
     */
    protected $_defaultConnection = [
        'className' => 'Cake\Database\Connection',
        'driver' => '',
        'database' => '',
        'username' => '',
        'password' => '',
        'host' => '',
        'prefix' => '',
        'encoding' => 'utf8',
        'timezone' => 'UTC',
        'cacheMetadata' => true,
        'quoteIdentifiers' => false,
        'log' => false,
        'init' => [],
    ];

    /**
     * Constructor.
     *
     * @param array $config Configuration options
     */
    public function __construct($config = [])
    {
        $this->_defaultConfig['settingsPath'] = SITE_ROOT . '/config/settings.php';
        $this->_defaultConfig['schemaPath'] = ROOT . '/plugins/Installer/config/fixture/';
        $this->config($config);

        if (function_exists('ini_set')) {
            ini_set('max_execution_time', (int)$this->config('maxExecutionTime'));
        } elseif (function_exists('set_time_limit')) {
            set_time_limit((int)$this->config('maxExecutionTime'));
        }
    }

    /**
     * Starts the process.
     *
     * @param array $dbConfig Database connection information
     * @return bool True on success, false otherwise
     */
    public function install($dbConfig = [])
    {
        if (!$this->prepareConfig($dbConfig)) {
            return false;
        }

        $conn = $this->getConn();
        if ($conn === false) {
            return false;
        }

        if (!$this->isDbEmpty($conn)) {
            return false;
        }

        if (!$this->importTables($conn)) {
            return false;
        }

        $this->writeSetting();
        return true;
    }

    /**
     * Registers an error message.
     *
     * @param string $message The error message
     * @return void
     */
    public function error($message)
    {
        $this->_errors[] = $message;
    }

    /**
     * Get all error messages.
     *
     * @return array
     */
    public function errors()
    {
        return $this->_errors;
    }

    /**
     * Prepares database configuration attributes.
     *
     * If the file "ROOT/config/settings.php.tmp" exists, and has declared a
     * connection named "default" it will be used.
     *
     * @param array $dbConfig Database connection info coming from POST
     * @return bool True on success, false otherwise
     */
    public function prepareConfig($dbConfig = [])
    {
        if ($this->config('connection')) {
            return true;
        }

        if (is_readable(SITE_ROOT . '/config/settings.php.tmp')) {
            $dbConfig = include SITE_ROOT . '/config/settings.php.tmp';
            if (empty($dbConfig['Datasources']['default'])) {
                $this->error(__d('installer', 'Invalid database information in file "{0}"', SITE_ROOT . '/config/settings.php.tmp'));
                return false;
            }
            $dbConfig = $dbConfig['Datasources']['default'];
        } else {
            if (empty($dbConfig['driver'])) {
                $dbConfig['driver'] = '__INVALID__';
            }
            if (strpos($dbConfig['driver'], "\\") === false) {
                $dbConfig['driver'] = "Cake\\Database\\Driver\\{$dbConfig['driver']}";
            }
        }

        list(, $driverClass) = namespaceSplit($dbConfig['driver']);
        if (!in_array($driverClass, ['Mysql', 'Postgres', 'Sqlite', 'Sqlserver'])) {
            $this->error(__d('installer', 'Invalid database type ({0}).', $driverClass));
            return false;
        }

        $this->config('connection', Hash::merge($this->_defaultConnection, $dbConfig));
        return true;
    }

    /**
     * Generates a new connection to DB.
     *
     * @return \Cake\Database\Connection|bool A connection object, or false on
     *  failure. On failure error messages are automatically set
     */
    public function getConn()
    {
        if (!$this->config('connection.className') ||
            !$this->config('connection.database') ||
            !$this->config('connection.username')
        ) {
            $this->error(__d('installer', 'Database name and username cannot be empty.'));
            return false;
        }

        try {
            ConnectionManager::config('installation', $this->config('connection'));
            $conn = ConnectionManager::get('installation');
            $conn->connect();
            return $conn;
        } catch (\Exception $ex) {
            $this->error(__d('installer', 'Unable to connect to database, please check your information. Details: {0}', '<p>' . $ex->getMessage() . '</p>'));
            return false;
        }
    }

    /**
     * Imports tables schema and populates them.
     *
     * @param \Cake\Database\Connection $conn Database connection to use
     * @return bool True on success, false otherwise. On failure error messages
     *  are automatically set
     */
    public function importTables($conn)
    {
        $Folder = new Folder($this->config('schemaPath'));
        $schemaFiles = $Folder->read(false, false, true)[1];
        try {
            return (bool)$conn->transactional(function ($connection) use ($schemaFiles) {
                foreach ($schemaFiles as $schemaPath) {
                    // IMPORT
                    require $schemaPath;
                    $className = str_replace('.php', '', basename($schemaPath));
                    $tableName = (string)Inflector::underscore(str_replace_last('Fixture', '', $className));
                    $fixture = new $className;
                    $fields = (array)$fixture->fields;
                    $records = (array)$fixture->records;
                    $constraints = [];

                    if (isset($fields['_constraints'])) {
                        $constraints = $fields['_constraints'];
                        unset($fields['_constraints']);
                    }

                    $tableSchema = new TableSchema($tableName, $fields);
                    if (!empty($constraints)) {
                        foreach ($constraints as $constraintName => $constraintAttrs) {
                            $tableSchema->addConstraint($constraintName, $constraintAttrs);
                        }
                    }

                    if ($connection->execute($tableSchema->createSql($connection)[0])) {
                        foreach ($records as $row) {
                            if (!$connection->insert($tableName, $row)) {
                                return false;
                            }
                        }
                    } else {
                        return false;
                    }
                }

                return true;
            });
        } catch (\Exception $ex) {
            $this->error(__d('installer', 'Unable to import database information. Details: {0}', '<p>' . $ex->getMessage() . '</p>'));
            return false;
        }
    }

    /**
     * Checks whether connected database is empty or not.
     *
     * @param \Cake\Database\Connection $conn Database connection to use
     * @return bool True if database if empty and tables can be imported, false if
     *  there are some existing tables
     */
    public function isDbEmpty($conn)
    {
        $Folder = new Folder($this->config('schemaPath'));
        $existingSchemas = $conn->schemaCollection()->listTables();
        $newSchemas = array_map(function ($item) {
            return Inflector::underscore(str_replace('Schema.php', '', $item));
        }, $Folder->read()[1]);
        $result = !array_intersect($existingSchemas, $newSchemas);
        if (!$result) {
            $this->error(__d('installer', 'A previous installation of QuickApps CMS already exists, please drop your database tables or change the prefix.'));
        }
        return $result;
    }

    /**
     * Creates site's "settings.php" file.
     *
     * @return bool True on success
     */
    public function writeSetting()
    {
        $config = [
            'Datasources' => [
                'default' => $this->config('connection'),
            ],
            'Security' => [
                'salt' => $this->salt()
            ],
            'debug' => false,
        ];

        $filePath = $this->config('settingsPath');
        if (!str_ends_with(strtolower($filePath), '.tmp')) {
            $filePath .= '.tmp';
        }

        $settingsFile = new File($filePath, true);
        return $settingsFile->write("<?php\n return " . var_export($config, true) . ";\n");
    }

    /**
     * Generates a random string suitable for security's salt.
     *
     * @return string
     */
    public function salt()
    {
        $space = '$%&()=!#@~0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($space), 0, rand(40, 60));
    }
}
