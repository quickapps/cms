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

<?= $this->fetch('beforeTable'); ?>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-bordered table-responsive">
            <thead>
                <tr>
                    <th><?= __d('field', 'Field label'); ?></th>
                    <th class="hidden-xs"><?= __d('field', 'Machine name'); ?></th>
                    <th class="hidden-xs"><?= __d('field', 'Type'); ?></th>
                    <th><?= __d('field', 'Actions'); ?></th>
                </tr>
            </thead>

            <tbody>
                <?php $count = $instances->count(); ?>
                <?php $k = 0; ?>
                <?php foreach ($instances as $instance): ?>
                <tr>
                    <td>
                        <?php if ($instance->locked): ?>
                            <span class="glyphicon glyphicon-lock" title="<?= __d('field', 'This field is locked and you can not edit it.'); ?>"></span>
                        <?php endif; ?>
                        <?= $instance->label; ?>
                    </td>
                    <td class="hidden-xs"><?= $instance->get('eav_attribute')->get('name'); ?></td>
                    <td class="hidden-xs"><?= $instance->get('handlerName'); ?></td>
                    <td>
                        <div class="btn-group pull-right">
                            <?php if ($k > 0): ?>
                                <?=
                                    $this->Html->link('', [
                                        'plugin' => $this->request->params['plugin'],
                                        'controller' => $this->request->params['controller'],
                                        'action' => 'move',
                                        $instance->id,
                                        'down'
                                    ], [
                                        'title' => __d('field', 'Move Up'),
                                        'class' => 'btn btn-sm btn-default glyphicon glyphicon-arrow-up',
                                    ]);
                                ?>
                            <?php endif; ?>

                            <?php if ($k < $count - 1): ?>
                                <?=
                                    $this->Html->link('', [
                                        'plugin' => $this->request->params['plugin'],
                                        'controller' => $this->request->params['controller'],
                                        'action' => 'move',
                                        $instance->id,
                                        'up'
                                    ], [
                                        'title' => __d('field', 'Move down'),
                                        'class' => 'btn btn-sm btn-default glyphicon glyphicon-arrow-down',
                                    ]);
                                ?>
                            <?php endif; ?>

                            <?php if (!$instance->locked): ?>
                                <?=
                                    $this->Html->link('', [
                                        'plugin' => $this->request->params['plugin'],
                                        'controller' => $this->request->params['controller'],
                                        'action' => 'configure',
                                        $instance->id
                                    ], [
                                        'title' => __d('field', 'Configure'),
                                        'class' => 'btn btn-sm btn-default glyphicon glyphicon-cog',
                                    ]);
                                ?>

                                <?=
                                    $this->Html->link('', [
                                        'plugin' => $this->request->params['plugin'],
                                        'controller' => $this->request->params['controller'],
                                        'action' => 'detach',
                                        $instance->id
                                    ], [
                                        'title' => __d('field', 'Delete'),
                                        'class' => 'btn btn-sm btn-default glyphicon glyphicon-trash',
                                        'confirm' => __d('field', 'Delete this field? This can not be undone, all information stored will be lost.'),
                                    ]);
                                ?>
                            <?php endif; ?>
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