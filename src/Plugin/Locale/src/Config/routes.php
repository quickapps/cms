<?php
namespace Locale\Config;

use Cake\Routing\Router;

Router::plugin('Locale', function($routes) {
	$routes->connect('/:controller', ['action' => 'index']);
	$routes->connect('/:controller/:action/*');
});
