<?php
	Router::connect('/user/login', array('plugin' => 'user', 'controller' => 'user', 'action' => 'login'));
	Router::connect('/admin/user/login', array('plugin' => 'user', 'controller' => 'user', 'action' => 'login', 'admin' => true));

	Router::connect('/user/logout', array('plugin' => 'user', 'controller' => 'user', 'action' => 'logout'));
	Router::connect('/admin/user/logout', array('plugin' => 'user', 'controller' => 'user', 'action' => 'logout', 'admin' => true));

	Router::connect('/user/register', array('plugin' => 'user', 'controller' => 'user', 'action' => 'register'));
	Router::connect('/user/activate/*', array('plugin' => 'user', 'controller' => 'user', 'action' => 'activate'));
	Router::connect('/user/cancell/*', array('plugin' => 'user', 'controller' => 'user', 'action' => 'cancell'));

	Router::connect('/user/password_recovery', array('plugin' => 'user', 'controller' => 'user', 'action' => 'password_recovery'));
	Router::connect('/user/profile/*', array('plugin' => 'user', 'controller' => 'user', 'action' => 'profile'));

	Router::connect('/user/my_account', array('plugin' => 'user', 'controller' => 'user', 'action' => 'my_account'));
	Router::connect('/user/my_account/*', array('plugin' => 'user', 'controller' => 'user', 'action' => 'my_account'));