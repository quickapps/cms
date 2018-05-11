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
if (!getenv('db_dsn')) {
    putenv('db_dsn=sqlite:///:memory:');
}

return [
    'Datasources' => [
        'test' => ['url' => getenv('db_dsn')],
    ],
    'Security' => [
        'salt' => '459dnv028fj20rmv034jv84hv929sadn306139fn)(·%o23',
    ],
    'debug' => true,
];
