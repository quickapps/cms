<?php
	$output = '';

	switch ($menu['region']) {
		case 'management-menu':
			echo $this->Menu->render($menu, array('class' => 'nav', 'partialMatch' => true));
		break;

		case 'content':
			echo $this->element('content-menu', array('menu' => $menu));
		break;

		default:
			echo $this->Menu->render($menu, array('class' => 'nav'));
		break;
	}