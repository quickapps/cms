<?php
	Router::connect('/theme/:theme_name/custom_css/:css', array('plugin' => 'system', 'controller' => 'themes', 'action' => 'serve_css'), array('pass' => array('theme_name', 'css')));