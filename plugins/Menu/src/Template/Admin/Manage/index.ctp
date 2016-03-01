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

<div class="row">
    <div class="col-md-12">
        <p class="text-right">
            <?=
                $this->Html->link(__d('menu', 'Create new menu'), [
                    'plugin' => 'Menu',
                    'controller' => 'manage',
                    'action' => 'add'
                ], [
                    'class' => 'btn btn-primary'
                ]);
            ?>
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if ($menus->count() > 0): ?>
            <ul class="list-group">
            <?php foreach ($menus as $menu): ?>
                <li class="list-group-item">
                    <div class="btn-group pull-right">
                        <?=
                            $this->Html->link('', [
                                'plugin' => 'Menu',
                                'controller' => 'manage',
                                'action' => 'edit',
                                $menu->id
                            ], [
                                'title' => __d('menu', 'Edit menu information'),
                                'class' => 'btn btn-sm btn-default glyphicon glyphicon-pencil',
                            ]);
                        ?>
                        <?=
                            $this->Html->link('', [
                                'plugin' => 'Menu',
                                'controller' => 'links',
                                'action' => 'menu',
                                $menu->id
                            ], [
                                'title' => __d('menu', 'Manage links'),
                                'class' => 'btn btn-sm btn-default glyphicon glyphicon-link',
                            ]);
                        ?>
                        <?=
                            $this->Html->link('', [
                                'plugin' => 'Menu',
                                'controller' => 'links',
                                'action' => 'add',
                                $menu->id
                            ], [
                                'title' => __d('menu', 'Add link'),
                                'class' => 'btn btn-sm btn-default glyphicon glyphicon-plus',
                            ]);
                        ?>

                        <?php if ($menu->handler === 'Menu'): ?>
                            <?=
                                $this->Html->link('', [
                                    'plugin' => 'Menu',
                                    'controller' => 'manage',
                                    'action' => 'delete',
                                    $menu->id
                                ], [
                                    'title' => __d('menu', 'Delete this menu'),
                                    'confirm' => __d('menu', 'Delete this menu ? All links within this menu will be lost.'),
                                    'class' => 'btn btn-sm btn-default glyphicon glyphicon-trash',
                                ]);
                            ?>
                        <?php endif; ?>
                    </div>
                    <h4 class="list-group-item-heading"><?= $menu->title; ?></h4>
                    <p class="list-group-item-text"><?= $menu->description; ?></p>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-warning">
                <?= __d('menu', 'There are no menus yet, click on "Create new menu" button to add one.'); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
