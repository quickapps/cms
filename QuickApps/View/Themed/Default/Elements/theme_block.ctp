<?php
	$output = '';

	switch ($block['region']) {
		case 'main-menu':
			case 'footer':
				case 'slider':
					case 'language-switcher':
						case 'search':
							case 'user-menu':
			$output .= "{$block['body']}";
		break;

		default:
			$output = $this->_render(APP . 'View' . DS . 'Elements' . DS . 'theme_block.ctp', array('block' => $block));
		break;
	}

	echo $output;