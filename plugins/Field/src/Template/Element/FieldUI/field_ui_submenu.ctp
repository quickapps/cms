<?php
/**
 * Licensed under The GPL-3.0 License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @since    2.0.0
 * @author   Christopher Castro <chris@quickapps.es>
 * @link     http://www.quickappscms.org
 * @license  http://opensource.org/licenses/gpl-3.0.html GPL-3.0 License
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
            'action' => 'view_mode_list',
            'prefix' => 'admin',
            $slug,
        ]
    ];
}
?>

<p>
    <?php
        echo $this->Menu->render([
            [
                'title' => '<span class="glyphicon glyphicon-list"></span> ' . __d('field', 'Fields List'),
                'url' => [
                    'plugin' => $this->request->params['plugin'],
                    'controller' => 'fields',
                    'action' => 'index',
                    'prefix' => 'admin'
                ]
            ],
            [
                'title' => '<span class="glyphicon glyphicon-plus"></span> ' . __d('field', 'Attach New Field'),
                'url' => [
                    'plugin' => $this->request->params['plugin'],
                    'controller' => 'fields',
                    'action' => 'attach',
                    'prefix' => 'admin'
                ]
            ],
            [
                'title' => '<span class="glyphicon glyphicon-eye-open"></span> ' . __d('field', 'View Modes'),
                'url' => '#',
                'expanded' => true,
                'activation' => 'any',
                'active' => '*/fields/view_mode*',
                'children' => $children,
            ],
        ], [
            'class' => ['nav nav-pills'],
            'dropdown' => true,
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
        ]);
    ?>
</p>