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

<tr class="<?= $plugin->status ? 'enabled' : 'danger disabled'; ?>">
    <td>
        <p><?= $plugin->humanName; ?> (<?= $plugin->version(); ?>)</p>
        <div class="btn-group">
            <?=
                $this->Html->link('', [
                    'plugin' => 'User',
                    'controller' => 'permissions',
                    'action' => 'index',
                    'prefix' => 'admin',
                    'expand' => $plugin->name(),
                ], [
                    'title' => __d('system', 'Permissons'),
                    'class' => 'btn btn-default btn-xs glyphicon glyphicon-lock',
                ]);
            ?>

            <?php if ($plugin->status && $plugin->hasHelp): ?>
                <?=
                    $this->Html->link('', [
                        'plugin' => 'System',
                        'controller' => 'help',
                        'action' => 'about',
                        'prefix' => 'admin',
                        $plugin->name(),
                    ], [
                        'title' => __d('system', 'Help'),
                        'class' => 'btn btn-default btn-xs glyphicon glyphicon-question-sign',
                    ]);
                ?>
            <?php endif; ?>

            <?php if ($plugin->hasSettings && $plugin->hasSettings): ?>
                <?=
                    $this->Html->link('', [
                        'plugin' => 'System',
                        'controller' => 'plugins',
                        'action' => 'settings',
                        'prefix' => 'admin',
                        $plugin->name(),
                    ], [
                        'title' => __d('system', 'Settings'),
                        'class' => 'btn btn-default btn-xs glyphicon glyphicon-cog',
                    ]);
                ?>
            <?php endif; ?>

            <?php if ($plugin->requiredBy()->isEmpty()): ?>
                <?php if (!$plugin->status): ?>
                    <?=
                        $this->Html->link('', [
                            'plugin' => 'System',
                            'controller' => 'plugins',
                            'action' => 'enable',
                            'prefix' => 'admin',
                            $plugin->name(),
                        ], [
                            'title' => __d('system', 'Enable'),
                            'class' => 'btn btn-default btn-xs glyphicon glyphicon-ok-circle',
                        ]);
                    ?>
                <?php else: ?>
                    <?=
                        $this->Html->link('', [
                            'plugin' => 'System',
                            'controller' => 'plugins',
                            'action' => 'disable',
                            'prefix' => 'admin',
                            $plugin->name(),
                        ], [
                            'title' => __d('system', 'Disable'),
                            'confirm' => __d('system', 'Disable this this plugin?, are you sure?'),
                            'class' => 'btn btn-default btn-xs glyphicon glyphicon-remove-circle',
                        ]);
                    ?>
                <?php endif; ?>

                <?=
                    $this->Html->link('', [
                        'plugin' => 'System',
                        'controller' => 'plugins',
                        'action' => 'delete',
                        'prefix' => 'admin',
                        $plugin->name(),
                    ], [
                        'title' => __d('system', 'Delete'),
                        'confirm' => __d('system', 'Delete this plugin? This operation cannot be undone!'),
                        'class' => 'btn btn-default btn-xs glyphicon glyphicon-trash',
                    ]);
                ?>
            <?php endif; ?>
        </div>
    </td>
    <td>
        <p>
        <?= $plugin->composer['description']; ?>
        <?php if (!$plugin->requiredBy()->isEmpty()): ?>
            <br />
            <span class="text-muted"><?= __d('system', 'Required by'); ?>:</span>
            <?php foreach ($plugin->requiredBy() as $p): ?>
                <?php if ($p->status): ?>
                    <span class="label label-success"><?= $p->humanName; ?></span>
                <?php else: ?>
                    <span class="label label-danger"><?= $p->humanName; ?></span>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        </p>

        <p>
            <a href="" class="btn btn-default btn-xs toggler">
                <span class="glyphicon glyphicon-arrow-down"></span> <?= __d('system', 'Details'); ?>
            </a>
            <div class="extended-info" style="display:none;">
                <?= $this->element('System.composer_details', ['composer' => $plugin->composer]); ?>
                <small class="pull-right"><?= __d('system', 'Package location: {0}', "<code>{$plugin->path}</code>"); ?></small>
            </div>
        </p>
    </td>
</tr>