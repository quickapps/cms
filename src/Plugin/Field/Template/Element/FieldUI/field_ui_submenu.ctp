<?php
	echo $this->Menu->render(
		[
			['title' => 'Fields List', 'url' => ['plugin' => $this->request->params['plugin'], 'controller' => 'fields', 'action' => 'index', 'prefix' => 'admin']],
			['title' => 'Attach New Field', 'url' => ['plugin' => $this->request->params['plugin'], 'controller' => 'fields', 'action' => 'attach', 'prefix' => 'admin']],
			[
				'title' => 'View Modes',
				'url' => '#',
				'expanded' => true,
				'active_on_type' => 'reg',
				'active_on' => '*/fields/view_modes*',
				'children' => [
					['title' => 'Full', 'url' => ['plugin' => $this->request->params['plugin'], 'controller' => 'fields', 'action' => 'view_modes', 'prefix' => 'admin']]
				]
			],
		],
		[
			'class' => ['nav nav-pills'],
			'templates' => [
				'parent' => '<ul class="dropdown-menu">{{content}}</ul>',
			],
			'itemCallable' => function ($entity, $info, $childContent) {
				if (empty($childContent)) {
					return $this->Menu->formatItem($entity, $info, $childContent);
				} else {
					$entity->title .= ' <span class="caret"></span>';

					return $this->Menu->formatItem($entity, $info, $childContent, [
						'childAttrs' => ['class' => ['dropdown']],
						'linkAttrs' => ['class' => ['dropdown-toggle'], 'data-toggle' => 'dropdown'],
					]);
				}
			}
		]
	);
