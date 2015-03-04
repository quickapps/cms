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

use Cake\Core\StaticConfigTrait;
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

    use StaticConfigTrait;

    /**
     * Error messages list.
     *
     * @var array
     */
    protected static $_errors = [];

    /**
     * Database connection config schema.
     *
     * @var array
     */
    protected static $_defaultConfig = [
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
     * Starts the process.
     *
     * @param array $dbConfig Database connection information
     * @return bool True on success, false otherwise
     */
    public static function init($dbConfig)
    {
        static::clear();
        if (!in_array($dbConfig['driver'], ['Mysql', 'Postgres', 'Sqlite', 'Sqlserver'])) {
            static::_error(__d('installer', 'Invalid database type'));
            return false;
        }

        if (function_exists('ini_set')) {
            ini_set('max_execution_time', 300);
        } elseif (function_exists('set_time_limit')) {
            set_time_limit(300);
        }

        static::_prepareConfig($dbConfig);
        $conn = static::_getConn();

        if ($conn === false) {
            return false;
        }

        if (!static::_isDbEmpty($conn)) {
            static::_error(__d('installer', 'A previous installation of QuickApps CMS already exists, please drop your database tables or change the prefix.'));
            return false;
        }

        if (!static::_importTables($conn)) {
            return false;
        }

        static::_initSetting();
        return true;
    }

    /**
     * Get all error messages.
     *
     * @return array
     */
    public static function errors()
    {
        return static::$_errors;
    }

    /**
     * Clear all values stored in DatabaseInstaller.
     *
     * @return bool Success
     */
    public static function clear()
    {
        static::$_errors = [];
        static::$_config = [];
        return true;
    }

    /**
     * Prepares database configuration attributes.
     *
     * If the file "ROOT/config/settings.php.tmp" exists, and has declared a
     * connection named "default" it will be used.
     *
     * @param array $dbConfig Database connection info coming from POST
     * @return void
     */
    protected static function _prepareConfig($dbConfig)
    {
        if (is_readable(SITE_ROOT . '/config/settings.php.tmp')) {
            include_once SITE_ROOT . '/config/settings.php.tmp';
            if (isset($config['Datasources']['default'])) {
                static::config($config['Datasources']['default']);
                return;
            }
        }

        $dbConfig['driver'] = "Cake\\Database\\Driver\\{$dbConfig['driver']}";
        static::config(Hash::merge(static::$_defaultConfig, $dbConfig));
    }

    /**
     * Registers an error message.
     *
     * @param string $message The error message
     * @return void
     */
    protected static function _error($message)
    {
        static::$_errors[] = $message;
    }

    /**
     * Generates a new connection to DB.
     *
     * @return \Cake\Database\Connection|bool A connection object, or false on
     *  failure. On failure error messages are automatically set
     */
    protected static function _getConn()
    {
        if (!static::config('className') ||
            !static::config('database') ||
            !static::config('username')
        ) {
            static::_error(__d('installer', 'Database name and username cannot be empty.'));
            return false;
        }

        try {
            ConnectionManager::config('installation', static::$_config);
            $conn = ConnectionManager::get('installation');
            $conn->connect();
            return $conn;
        } catch (\Exception $ex) {
            static::_error(__d('installer', 'Unable to connect to database, please check your information. Details: {0}', '<p>' . $ex->getMessage() . '</p>'));
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
    protected static function _importTables($conn)
    {
        $Folder = new Folder(ROOT . '/config/Schema/');
        $schemaFiles = $Folder->read(false, false, true)[1];
        try {
            return (bool)$conn->transactional(function ($connection) use ($schemaFiles) {
                foreach ($schemaFiles as $schemaPath) {
                    // IMPORT
                    require $schemaPath;
                    $className = str_replace('.php', '', basename($schemaPath));
                    $tableName = (string)Inflector::underscore(str_replace('Schema', '', $className));
                    $fixture = new $className;
                    $fields = $fixture->fields();
                    $records = $fixture->records();
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
                        if (!empty($records)) {
                            foreach ($records as $row) {
                                if (!$connection->insert($tableName, $row)) {
                                    return false;
                                }
                            }
                        }
                    } else {
                        return false;
                    }
                }

                return true;
            });
        } catch (\Exception $ex) {
            static::_error(__d('installer', 'Unable to import database information. Details: {0}', '<p>' . $ex->getMessage() . '</p>'));
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
    protected static function _isDbEmpty($conn)
    {
        $Folder = new Folder(ROOT . '/config/Schema/');
        $existingSchemas = $conn->schemaCollection()->listTables();
        $newSchemas = array_map(function ($item) {
            return Inflector::underscore(str_replace('Schema.php', '', $item));
        }, $Folder->read()[1]);
        return !array_intersect($existingSchemas, $newSchemas);
    }

    /**
     * Creates site's "settings.php" file.
     *
     * @return void
     */
    protected static function _initSetting()
    {
        $config = [
            'Datasources' => [
                'default' => static::$_config,
            ],
            'Security' => [
                'salt' => static::_salt()
            ],
            'debug' => false,
        ];
        $settingsFile = new File(SITE_ROOT . '/config/settings.php.tmp', true);
        $settingsFile->write('<?php $config = ' . var_export($config, true) . ';');
    }

    /**
     * Generates a random string suitable for security's salt.
     *
     * @return string
     */
    protected static function _salt()
    {
        $space = '$%&()=!#@~0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle($space), 0, rand(40, 60));
    }
}
