<?php
    Router::connect('/theme/:theme_name/css/:css@@custom', array('plugin' => 'system', 'controller' => 'themes', 'action' => 'serve_css'), array('pass' => array('theme_name', 'css')));