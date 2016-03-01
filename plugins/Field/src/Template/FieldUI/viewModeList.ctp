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

<?= $this->fetch('beforeSubmenu'); ?>
<div class="row">
    <div class="col-md-12">
        <?= $this->element('Field.FieldUI/field_ui_submenu'); ?>
    </div>
</div>
<?= $this->fetch('afterSubmenu'); ?>

<div class="row">
    <div class="col-md-12">
        <h2><?= $viewModeInfo['name']; ?></h2>
        <em class="help-block"><?= $viewModeInfo['description']; ?></em>
    </div>
</div>

<?= $this->fetch('beforeTable'); ?>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-bordered table-responsive">
            <thead>
                <tr>
                    <th><?= __d('field', 'Field label'); ?></th>
                    <th><?= __d('field', 'Label visibility'); ?></th>
                    <th><?= __d('field', 'Field visibility'); ?></th>
                    <th><?= __d('field', 'Actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php $count = count($instances->countBy('id')->toArray()); ?>
                <?php $k = 0; ?>
                <?php foreach ($instances as $instance): ?>
                <tr>
                    <td>
                        <?= $instance->label; ?>
                        <br />
                        <em><small><?= $instance->slug; ?></small></em>
                    </td>
                    <td><?= $instance->view_modes[$viewMode]['label_visibility']; ?></td>
                    <td><?= $instance->view_modes[$viewMode]['hidden'] ? __d('field', 'hidden') : __d('field', 'visible'); ?></td>
                    <td>
                        <div class="btn-group">
                            <?php if ($k > 0): ?>
                                <?=
                                    $this->Html->link('', [
                                        'plugin' => $this->request->params['plugin'],
                                        'controller' => $this->request->params['controller'],
                                        'action' => 'view_mode_move',
                                        $viewMode,
                                        $instance->id,
                                        'down'
                                    ], [
                                        'title' => __d('field', 'Move up'),
                                        'class' => 'btn btn-default glyphicon glyphicon-arrow-up'
                                    ]);
                                ?>
                            <?php endif; ?>

                            <?php if ($k < $count - 1): ?>
                                <?=
                                    $this->Html->link('', [
                                        'plugin' => $this->request->params['plugin'],
                                        'controller' => $this->request->params['controller'],
                                        'action' => 'view_mode_move',
                                        $viewMode,
                                        $instance->id,
                                        'up'
                                    ], [
                                        'title' => __d('field', 'Move down'),
                                        'class' => 'btn btn-default glyphicon glyphicon-arrow-down'
                                    ]);
                                ?>
                            <?php endif; ?>

                            <?=
                                $this->Html->link('', [
                                    'plugin' => $this->request->params['plugin'],
                                    'controller' => $this->request->params['controller'],
                                    'action' => 'view_mode_edit',
                                    $viewMode,
                                    $instance->id
                                ], [
                                    'title' => __d('field', 'View mode settings'),
                                    'class' => 'btn btn-default glyphicon glyphicon-eye-open'
                                ]);
                            ?>
                        </div>
                    </td>
                </tr>
                <?php $k++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->fetch('afterTable'); ?>