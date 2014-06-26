<?php
	$view = $this;
	echo $this->Menu->render($types,
		[
			'class' => 'list-group',
			'formatter' => function ($item, $info) use ($view) {
				return $view->element('Types' . DS . 'types_list_item', ['item' => $item, 'info' => $info]);
			}
		]
	);