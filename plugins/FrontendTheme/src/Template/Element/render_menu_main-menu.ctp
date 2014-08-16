<?php
	echo $this->Menu->render($menu->links, [
		'class' => 'nav navbar-nav',
		'formatter' => function ($item, $info) {
			$options = [];
			if ($info['hasChildren'] && $info['depth'] === 0) {
				$item->title .= ' <span class="caret"></span>';
			}

			if ($info['depth'] > 0) {
				$options['childAttrs']['class'] = ['dropdown-submenu'];
			}

			return $this->Menu->formatter($item, $info, $options);
		},
	]);
