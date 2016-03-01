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

<li id="menuLink_<?= $link->id; ?>">
    <div>
        <span style="cursor:move;">
            <?php if (!in_array($link->id, $disabledIds) && !in_array($link->parent_id, $disabledIds)): ?>
                <?= $link->title; ?>
            <?php else: ?>
                <del><?= $link->title; ?></del>
            <?php endif; ?>
            &nbsp;&nbsp;&nbsp;
        </span>
        <div class="btn-group">
            <?=
                $this->Html->link('', [
                    'plugin' => 'Menu',
                    'controller' => 'links',
                    'action' => 'edit',
                    $link->id
                ], [
                    'title' => __d('menu', 'Edit link'),
                    'class' => 'btn btn-default btn-xs glyphicon glyphicon-pencil',
                ]);
            ?>

            <?php if (!empty($link->url)): ?>
                <?=
                    $this->Html->link('', $link->url, [
                        'title' => __d('menu', 'Visit URL'),
                        'class' => 'btn btn-default btn-xs glyphicon glyphicon-link',
                        'target' => '_blank',
                    ]);
                ?>
            <?php endif; ?>

            <?=
                $this->Html->link('', [
                    'plugin' => 'Menu',
                    'controller' => 'links',
                    'action' => 'delete',
                    $link->id
                ], [
                    'title' => __d('menu', 'Delete this link'),
                    'class' => 'btn btn-default btn-xs glyphicon glyphicon-trash',
                    'confirm' => __d('menu', 'Remove this link? Children links will be re-assigned to the immediately superior parent link.'),
                ]);
            ?>
        </div>
    </div>

    <p><?= $info['children']; ?></p>
</li>