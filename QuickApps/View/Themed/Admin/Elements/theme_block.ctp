<?php
	$output = '';

	switch ($block['region']) {
		case 'management-menu':
			$output .= "<div id=\"{$block['region']}\" class=\"item-list\">{$block['body']}</div>";
		break;

		case 'toolbar':
			$output =  $block['body'];
		break;

		case 'footer':
			$output =  $block['body'];
		break;

		default:
			$output = $this->_render(APP . 'View' . DS . 'Elements' . DS . 'theme_block.ctp', array('block' => $block));
		break;
	}

	echo $output;