<?php
    Router::connect('/:type/:slug.html', array('plugin' => 'node', 'controller' => 'node', 'action' => 'details'), array('pass' => array('type', 'slug')));
    Router::connect('/s/:criteria/*', array('plugin' => 'node', 'controller' => 'node', 'action' => 'search'), array('pass' => array('criteria')));
    Router::connect('/s', array('plugin' => 'node', 'controller' => 'node', 'action' => 'search'));