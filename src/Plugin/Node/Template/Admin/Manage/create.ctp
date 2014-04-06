<?php
	echo $this->Menu->render($types,
		[
			'class' => 'list-group',
			'itemCallable' => function ($item, $info, $childContent) {
				$content = '<h4 class="list-group-item-heading">' . $item->name . '</h4>';
				$content .= '<p class="list-group-item-text">' . $item->description . '</p>';

				return
					$this->Html->link(
						$content,
						[
							'plugin' => 'node',
							'controller' => 'manage',
							'action' => 'add',
							'prefix' => 'admin',
							$item->slug
						],
						['class' => 'list-group-item', 'escape' => false]
					);
			}
		]
	);