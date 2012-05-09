<?php
	Router::connect('/admin/comment/published', array('prefix' => 'admin', 'plugin' => 'comment', 'controller' => 'list', 'action' => 'show', 'admin' => true, 'published'));
	Router::connect('/admin/comment/unpublished', array('prefix' => 'admin', 'plugin' => 'comment', 'controller' => 'list', 'action' => 'show', 'admin' => true, 'unpublished'));
