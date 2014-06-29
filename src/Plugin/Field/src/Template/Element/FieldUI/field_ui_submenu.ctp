<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since	 2.0.0
 * @author	 Christopher Castro <chris@quickapps.es>
 * @link	 http://www.quickappscms.org
 * @license	 http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
 */
?>

<?php

$children = [];
foreach ($this->viewModes(true) as $slug => $viewMode) {
	$children[] = [
		'title' => $viewMode['name'],
		'url' => [
			'plugin' => $this->request->params['plugin'],
			'controller' => $this->request->params['controller'],
			'action' => 'view_modes',
			'prefix' => 'admin',
			$slug,
		]
	];
}

echo $this->Menu->render(
	[
		['title' => __d('field', 'Fields List'), 'url' => ['plugin' => $this->request->params['plugin'], 'controller' => 'fields', 'action' => 'index', 'prefix' => 'admin']],
		['title' => __d('field', 'Attach New Field'), 'url' => ['plugin' => $this->request->params['plugin'], 'controller' => 'fields', 'action' => 'attach', 'prefix' => 'admin']],
		[
			'title' => __d('field', 'View Modes'),
			'url' => '#',
			'expanded' => true,
			'active_on_type' => 'reg',
			'active_on' => '*/fields/view_modes*',
			'children' => $children,
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
