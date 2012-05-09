<?php
	Router::connect('/:type/:slug.html', array('plugin' => 'node', 'controller' => 'node', 'action' => 'details'), array('pass' => array('type', 'slug')));
	Router::connect('/:search/:criteria/*', array('plugin' => 'node', 'controller' => 'node', 'action' => 'search'), array('search' => 's|search', 'pass' => array('criteria')));
	Router::connect('/:search', array('plugin' => 'node', 'controller' => 'node', 'action' => 'search'), array('search' => 's|search'));