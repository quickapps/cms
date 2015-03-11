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
        'driver' => 'Cake\Database\Driver\Sqlite',
        'log' => true,
    ];
} elseif (getenv('DB') == 'mysql') {
    $conn = [
        'driver' => 'Cake\Database\Driver\Mysql',
        'username' => 'travis',
        'password' => '',
        'database' => 'quick_test',
        'log' => true,
    ];
} elseif (getenv('DB') == 'pgsql') {
    $conn = [
        'driver' => 'Cake\Database\Driver\Postgres',
        'persistent' => false,
        'host' => 'localhost',
        'username' => 'postgres',
        'password' => '',
        'database' => 'quick_test',
        'log' => true,
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
