<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    1.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
namespace User\Config;

use Cake\Routing\Router;

Router::connect('/unauthorized', [
    'plugin' => 'User',
    'controller' => 'Gateway',
    'action' => 'unauthorized',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/user/me', [
    'plugin' => 'User',
    'controller' => 'Gateway',
    'action' => 'me',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/admin/user/me', [
    'plugin' => 'User',
    'controller' => 'Gateway',
    'action' => 'me',
    'prefix' => 'admin',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/user/profile', [
    'plugin' => 'User',
    'controller' => 'Gateway',
    'action' => 'profile',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/admin/user/profile', [
    'plugin' => 'User',
    'controller' => 'Gateway',
    'action' => 'profile',
    'prefix' => 'admin',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/login', [
    'plugin' => 'User',
    'controller' => 'Gateway',
    'action' => 'login',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/admin/login', [
    'plugin' => 'User',
    'controller' => 'Gateway',
    'action' => 'login',
    'prefix' => 'admin',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/logout', [
    'plugin' => 'User',
    'controller' => 'Gateway',
    'action' => 'logout',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);

Router::connect('/admin/logout', [
    'plugin' => 'User',
    'controller' => 'Gateway',
    'action' => 'logout',
    'prefix' => 'admin',
], ['routeClass' => 'Cake\Routing\Route\InflectedRoute']);
