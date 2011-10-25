<?php
    Router::connect('/d/:slug/*', array('plugin' => 'node', 'controller' => 'node', 'action' => 'details'), array('pass' => array('slug')));
    Router::connect('/s/:criteria/*', array('plugin' => 'node', 'controller' => 'node', 'action' => 'search'), array('pass' => array('criteria')));
    Router::connect('/s', array('plugin' => 'node', 'controller' => 'node', 'action' => 'search'));