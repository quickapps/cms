<?php
	$output = '';

	switch ($menu['region']) {
		case 'management-menu':
			echo $this->Menu->render($menu, array('id' => 'top-menu', 'partialMatch' => true));
		break;

		case 'content':
			echo $this->element('content-menu', array('menu' => $menu));
		break;

		default:
			echo $this->Menu->render($menu);
		break;
	}