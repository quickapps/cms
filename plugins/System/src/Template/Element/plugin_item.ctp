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

$classes = [];
$classes[] = $plugin->status ? 'enabled' : 'danger disabled';
$classes[] = $plugin->isCore ? 'plugin-core' : 'plugin-third-party';
?>
<tr class="<?php echo implode(' ', $classes); ?>">
    <td>
        <p><?php echo $plugin->humanName; ?> (<?php echo $plugin->version(); ?>)</p>
        <div class="btn-group">
            <?php
                echo $this->Html->link('', [
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
                <?php
                    echo $this->Html->link('', [
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
                <?php
                    echo $this->Html->link('', [
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

            <?php if (!$plugin->isCore): ?>
                <?php if (!$plugin->status): ?>
                    <?php
                        echo $this->Html->link('', [
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
                    <?php
                        echo $this->Html->link('', [
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

                <?php
                    echo $this->Html->link('', [
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
        <p><?php echo $plugin->composer['description']; ?></p>
        <p>
            <a href="" class="btn btn-default btn-xs toggler">
                <span class="glyphicon glyphicon-arrow-down"></span> <?php echo __d('system', 'Details'); ?>
            </a>
            <div class="extended-info" style="display:none;">
                <?php echo $this->element('System.composer_details', ['composer' => $plugin->composer]); ?>
                <small class="pull-right"><?php echo __d('system', 'Package location: {0}', "<code>{$plugin->path}</code>"); ?></small>
            </div>
        </p>
    </td>
</tr>