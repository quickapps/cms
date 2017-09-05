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

// Ensure default test connection is defined
if (!getenv('DB')) {
    putenv('DB=mysql');
}

if (getenv('DB') == 'sqlite') {
    $conn = [
        'className' => 'Cake\Database\Connection',
        'driver' => 'Cake\Database\Driver\Sqlite',
        'log' => false,
    ];
} elseif (getenv('DB') == 'mysql') {
    $conn = [
        'className' => 'Cake\Database\Connection',
        'driver' => 'Cake\Database\Driver\Mysql',
        'username' => 'root',
        'password' => '',
        'database' => 'quick_test',
        'log' => false,
    ];
} elseif (getenv('DB') == 'pgsql') {
    $conn = [
        'className' => 'Cake\Database\Connection',
        'driver' => 'Cake\Database\Driver\Postgres',
        'persistent' => false,
        'host' => 'localhost',
        'username' => 'postgres',
        'password' => '',
        'database' => 'quick_test',
        'log' => false,
    ];
}

return [
    'Datasources' => [
        'test' => $conn,
    ],
    'Security' => [
        'salt' => '459dnv028fj20rmv034jv84hv929sadn306139fn)(·%o23',
    ],
    'debug' => true,
];
