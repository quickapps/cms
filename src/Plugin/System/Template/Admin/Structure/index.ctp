<?php
	echo $this->Menu->render($links,
		[
			'class' => 'list-group',
			'itemCallable' => function ($item, $info, $childContent) {
				$content = '<h4 class="list-group-item-heading">' . $item->title . '</h4>';
				$content .= '<p class="list-group-item-text">' . $item->description . '</p>';

				return
					$this->Html->link($content, $item->url, ['class' => 'list-group-item', 'escape' => false]);
			}
		]
	);
?>