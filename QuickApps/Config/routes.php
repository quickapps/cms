<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright	 Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link		  http://cakephp.org CakePHP(tm) Project
 * @package	   app.config
 * @since		 CakePHP(tm) v 0.2.9
 * @license	   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */

/**
 * Installer
 *
 */
	if (!file_exists(ROOT . DS . 'Config' . DS . 'database.php') || !file_exists(ROOT . DS . 'Config' . DS . 'install')) {
		Router::connect('/', array('controller' => 'install'));
		Router::connect('/:anything', array('controller' => 'install'), array('anything' => '(?!install).*'));
	} else {
		Router::connect('/', array('plugin' => 'Node', 'controller' => 'node', 'action' => 'index'));
		Router::connect('/admin', array('plugin' => 'System', 'controller' => 'system', 'action' => 'index', 'admin' => true));
   }

/**
 * Load site routes.
 *
 */
	include_once ROOT . DS . 'Config' . DS . 'routes.php';

/**
 * Load all plugin routes.
 *
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 *
 */
	require CAKE . 'Config' . DS . 'routes.php';

/**
 * Add language prefix to each url.
 *
 */
	if (Configure::read('Variable.url_language_prefix')) {
		foreach (Router::$routes as $key => $_route) {
			$route = clone $_route;
			$route->options['language'] = '[a-z]{3}';
			$route->template = "/:language{$route->template}";
			array_splice(Router::$routes, $key, 0, array($route));
		}
	}